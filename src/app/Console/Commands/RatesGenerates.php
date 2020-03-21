<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Option;

class RatesGenerates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:generates {turns_count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rates generates command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->main_symbool = 'USD';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $opt = Option::where('key', 'turns_count')->first();
        if (!empty($opt)) {
            $opt->value = $arguments['turns_count'];
        } else {
            $dataRows = [
                [
                    'key' => 'turns_count',
                    'value' => $arguments['turns_count'],
                ]
            ];

            $currenies = config('settings.currenies');
            $amount = 1/$arguments['turns_count'];
            $rates = $this->getExchangeRate($this->main_symbool, $currenies, $amount);

            foreach($currenies as $key => $value) {
                if ($value == $this->main_symbool) {
                    $rate = $amount;
                } else {
                    $k = strtoupper($this->main_symbool).strtoupper($value);
                    $rate = (isset($rates[$k])) ? $rates[$k] : 0;
                    $rate = $rate * $amount;
                }
                array_push($dataRows, ['key' => $value, 'rate' => $rate]);
            }

            Option::insert($dataRows);
        }

        $this->info('Rates generates successfully.');
    }

    protected function getExchangeRate($from, $to, $amount) {
        $amount = urlencode($amount);
        $from = urlencode($from);
        $to = urlencode(implode(',', $to));
        $url = "http://apilayer.net/api/live?access_key=5c784710c1c7de92cdb5ea41743aac4e&currencies=$to&source=$from&format=1";
        $json = json_decode(file_get_contents($url), true);
        if ($json['success']) {
            return $json['quotes'];
        }

        return [];
    }
}
