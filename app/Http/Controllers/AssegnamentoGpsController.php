<?php

	namespace App\Http\Controllers;

	use App\Models\AssegnamentoGps;
	use Illuminate\Http\Request;

	class AssegnamentoGpsController extends Controller
	{
		protected $model=AssegnamentoGps::class;
		public function __construct()
		{
			//$this->model = AssegnamentoGps::class;
		}

		public function store(Request $request)
		{
			// Validate and store the new assignment
		}
	}
