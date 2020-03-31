<?php
namespace App\Http\Controllers\Api;

use \App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use \App\Models\User;

class ApiController extends Controller {
	protected function jsonRender($data = []) {
		$this->compacts['message'] = [
			'code' => 200,
			'status' => true,
		];

		$compacts = array_merge($data, $this->compacts);

		return response()->json($compacts);
	}
}
