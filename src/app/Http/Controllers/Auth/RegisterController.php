<?php
namespace App\Http\Controllers\Auth;

use \App\Http\Controllers\Controller;
use \App\Providers\RouteServiceProvider;
use \App\Models\User;
use \Illuminate\Foundation\Auth\RegistersUsers;
use \Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Session;
use \App\Services\Passport;

class RegisterController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
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
		$this->middleware('guest');

		$this->passport = $passport;
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'phone_number' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data) {
		$user = User::create([
			'last_name' => $data['last_name'],
			'first_name' => $data['first_name'],
			'name' => $data['last_name'] . ' ' . $data['first_name'],
			'phone_number' => $data['phone_number'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'role' => 2,
		]);

		$pdata = [
			'email' => $data['email'],
			'password' => $data['password'],
		];
		$stt = $this->passport->passwordGrantToken($pdata);

		Session::put('passport', $stt);

		return $user;
	}
}
