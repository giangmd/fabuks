<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

use App\Models\TradeHistory;
use App\Models\UserBalance;

use Auth;

class TradeHistoryController extends ApiController
{
    protected $model;
    protected $user_balance;
    protected $user;

    public function __construct(TradeHistory $model, UserBalance $user_balance) {
        $this->model = $model;
        $this->user_balance = $user_balance;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->user = Auth::user();
        $userId = $this->user->id;
        $trade = $this->model->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(config('settings.limit'));

        $acceptNew = false;
        $tradePendding = $this->model->where('user_id', $userId)->where('status', 0)->count();
        if ($tradePendding < 5) {
            $acceptNew = true;
        }

        return $this->jsonRender(['trade' => $trade, 'accept_new' => $acceptNew, 'user_balance' => $this->user->balance]);
    }

    public function offer(Request $request)
    {
        $this->user = Auth::user();
        $userId = $this->user->id;
        $params = $request->all();
        $params['user_id'] = $userId;
        $params['status'] = 0;

        // check balance from currency exchange
        $fromCurrency = $this->user_balance->where('user_id', $userId)->where('type', $params['from'])->first();

        if ($fromCurrency->balance < $params['amount']) {
            return $this->jsonRender(['alert' => 'The Balance of '.$params['from'].' is not enough for exchange']);
        }

        // decrease from currency
        $fromCurrency->balance = $fromCurrency->balance - $params['amount'];
        $fromCurrency->save();

        // increase to currency
        $toCurrency = $this->user_balance->where('user_id', $userId)->where('type', $params['to'])->first();
        $toCurrency->balance = $toCurrency->balance + $params['amount'];
        $toCurrency->save();

        // create trade history
        $trade = $this->model->create($params);

        $acceptNew = false;
        $tradePendding = $this->model->where('user_id', $userId)->where('status', 0)->count();
        if ($tradePendding < 5) {
            $acceptNew = true;
        }

        return $this->jsonRender(['trade' => $trade, 'accept_new' => $acceptNew, 'user_balance' => $this->user->balance]);
    }

    public function refetch(Request $request)
    {
        $this->user = Auth::user();
        $userId = $this->user->id;
        $dataRate = $request->data_rate;

        $this->resolveTrade($userId, $dataRate);

        $acceptNew = false;
        $tradePendding = $this->model->where('user_id', $userId)->where('status', 0)->count();
        if ($tradePendding < 5) {
            $acceptNew = true;
        }

        // refetch list trade
        $trade = $this->model->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(config('settings.limit'));

        return $this->jsonRender(['trade' => $trade, 'accept_new' => $acceptNew, 'user_balance' => $this->user->balance]);
    }

    protected function resolveTrade($userId, $dataRate)
    {
        $date = new \DateTime();
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        $records = $this->model->where('user_id', $userId)->where('status', 0)->where('created_at', '<=', $formatted_date)->get();
        if (count($records) > 0) {
            foreach ($records as $key => $record) {
                $money = $record->amount * $record->price_order;

                // update trade offer
                $priceDone = null;
                if ($record->from == config('settings.fabuk_symbool')) {
                    $priceDone = $dataRate[$record->to];
                } elseif ($record->to == config('settings.fabuk_symbool')) {
                    $priceDone = $dataRate[$record->from];
                }

                $record->status = 1;
                $record->price_done = $priceDone;
                $record->save();

                // update balance for user
                $this->updateBalance($userId, $money);
            }
        }
    }

    protected function updateBalance($userId, $money=0)
    {
        $total = $this->user_balance->where('user_id', $userId)->where('type', 'total')->first();
        $total->balance = $total->balance + $money;
        $total->save();
    }
}
