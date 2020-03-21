<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserBalance;

class DashboardController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');

        $this->user = $user;
    }

    public function index()
    {
        return view('admin.index');
    }

    public function userList()
    {
        $data['users'] = $this->user->where('role', '!=', 0)->paginate(config('settings.limit'));;
        return view('admin.user', $data);
    }

    public function userEdit($id)
    {
        $user = $this->user->find($id);
        if (empty($user)) {
            return redirect()->back();
        }

        return view('admin.user-update', compact('user'));
    }

    public function userUpdate($id, Request $request)
    {
        $user = $this->user->find($id);
        if (empty($user)) {
            return redirect()->back();
        }

        $user->role = $request->role;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->last_name . ' ' . $user->first_name;
        $user->save();

        return redirect()->to(route('admin.user'));
    }

    public function userOffers($id)
    {
        $user = $this->user->find($id);
        if (empty($user)) {
            return redirect()->back();
        }

        $balanceTotal = UserBalance::where('user_id', $user->id)->where('type', 'total')->first();
        return view('admin.user-offers', compact('user', 'balanceTotal'));
    }
}
