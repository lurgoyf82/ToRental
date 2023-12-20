<?php

	namespace App\Http\Controllers;

	use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
	use Illuminate\Foundation\Validation\ValidatesRequests;
	use Illuminate\Routing\Controller as BaseController;
	use Illuminate\Http\Request;

	class Controller extends BaseController
	{
		use AuthorizesRequests, ValidatesRequests;

		protected $model;
		public function search(Request $request, $search=false, $searchField=false)
		{
			$result = $this->model::search($search, $searchField);
			return response()->json($result);
		}
	}
