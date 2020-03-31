<?php
namespace App\Http\Controllers\Api;

use \App\Http\Controllers\Controller;
use \App\Http\Controllers\Api\ApiController;
use \Illuminate\Http\Request;
use \App\Models\Option;

class RateController extends ApiController {
	protected $opt_model;

	public function __construct(Option $opt_model) {
		$this->opt_model = $opt_model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$currenies = config('settings.currenies');
		$data = [];
		$data['rates'] = [];
		foreach ($currenies as $key => $value) {
			$item = $this->opt_model->where('key', $value)->first();
			if (!empty($item)) {
				array_push($data['rates'], ['key' => $item->key, 'value' => $item->value]);
			}
		}

		return $this->jsonRender($data);
	}
}
