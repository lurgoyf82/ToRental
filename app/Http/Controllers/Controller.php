<?php

	namespace App\Http\Controllers;

	use App\Models\Targa;
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


		/**
		 * Display a listing of the resource.
//		 */
//		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
//		{
//			$search = $request->input('search',null);
//			$order  = $request->input('order','id');
//			$page   = $request->input('page', 1);  // default to 1 if not provided
//
//
//			$result = $this->model::index($search, $order, $page);
//
//			$targaList= Targa::getTargaListByIdVeicolo();
//			foreach ($result as $key=>$alert) {
//				if(isset($targaList[$alert->id_veicolo])) {
//					$result[$key]->targa = $targaList[$alert->id_veicolo]->targa;
//				}
//			}
//			return view('list_bollo', ['list' => $result]);
//		}
//

		// In your BaseController

		public function indexView(Request $request, $model, $viewName, $additionalData = []): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search', null);
			$order  = $request->input('order', 'id');
			$page   = $request->input('page', 1);  // default to page 1

			$result = $model::index($search, $order, $page);

			$targaList = Targa::getTargaListByIdVeicolo();
			foreach ($result as $key => $alert) {
				if (isset($targaList[$alert->id_veicolo])) {
					$result[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}

			// Merge result with any additional data needed for the view
			$data = array_merge(['list' => $result], $additionalData);

			return view($viewName, $data);
		}


	}
