<?php
namespace App\Http\Controllers\Auth;

use \App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use \App\Providers\RouteServiceProvider;
use \Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Support\Facades\Validator;
use \App\Services\Passport;
use \Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::HOME;

	protected $passport;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Passport $passport) {
		$this->middleware('guest')->except('logout');

		$this->passport = $passport;
	}

	public function login(Request $request) {
		$data = $request->only(['email', 'password']);
		$rules = [
			'email' => 'required|email|max:255',
			'password' => 'required|min:8|max:128',
		];

		$validator = Validator::make($data, $rules);
		if ($validator->fails()) {
			return redirect()->to('login')
				->withErrors($validator)
				->withInput($request->except('password'));
		} else {
			if ($this->attemptLogin($request)) {
				$response = $request->has('refresh_token') ?
					$this->passport->refreshGrantToken($request->refresh_token) :
					$this->passport->passwordGrantToken($data);

				Session::put('passport', $response);

				if (Auth::user()->role == 'user') {
					return redirect()->to('/');
				} else {
					return redirect()->to(route('admin.dashboard'));
				}
			} else {
				return redirect()->to('login');
			}
		}
	}
}
