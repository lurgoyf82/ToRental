<form action="{{ route('store_assicurazione') }}" method="POST">
	@csrf
	<!--begin::Content-->
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="app-container container-fluid">
			<!--begin::Row-->
			<div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
				<!--begin::Col-->
				<div class="col-xxl-6 mb-5 mb-xl-10">
					<!--begin::Chart widget 8-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header pt-5">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Inserisci Dati Del Veicolo</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">Selezione dati Essenziali</span>
							</h3>
							<!--end::Title-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-6">
							<!--begin::Tab content-->
							<div class="tab-content">
								<!--begin::Tab pane-->
								<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
									<!--begin::Items-->
									@if ($errors->any())
										<div class="form-group">
											<div class="alert alert-danger" role="alert">
												@foreach ($errors->all() as $error)
													{{ $error }}<br/>
												@endforeach
											</div>
										</div>
									@endif
									@if (Session::has('success'))
										<div class="form-group">
											<div class="alert alert-success" role="alert">
												{{ Session::get('success') }}
											</div>
										</div>
									@endif


									<!-- Search Input for Veicolo -->
									<div class="form-group">
										<label for="searchInput">Veicolo</label>
										<input list="veicoli" name="searchInput" id="searchInput" class="form-control"
													 value="{{ old('searchInput') }}" placeholder="Cerca Veicolo">
										<datalist id="veicoli">
											<!-- Options will be dynamically loaded -->
										</datalist>
									</div>

									<!-- Hidden Field for the actual Veicolo ID -->
									<input type="hidden" name="id_veicolo" id="id_veicolo" value="{{ old('id_veicolo') }}">

									<!-- Anno Field -->
									<div class="form-group">
										<label for="anno">Anno</label>
										<input type="number" name="anno" id="anno" class="form-control" value="{{ old('anno') }}" placeholder="Anno">
									</div>

									<!-- Data Pagamento Field -->
									<div class="form-group">
										<label for="data_pagamento">Data Pagamento</label>
										<input type="date" name="data_pagamento" id="data_pagamento" class="form-control" value="{{ old('data_pagamento') }}">
									</div>

									<!-- Importo Field -->
									<div class="form-group">
										<label for="importo">Importo</label>
										<input type="number" step="0.01" name="importo" id="importo" class="form-control" value="{{ old('importo') }}" placeholder="Importo">
									</div>

									<!-- Agenzia Field -->
									<div class="form-group">
										<label for="agenzia">Agenzia</label>
										<input type="text" name="agenzia" id="agenzia" class="form-control" value="{{ old('agenzia') }}" placeholder="Agenzia">
									</div>

									<!-- Polizza Field -->
									<div class="form-group">
										<label for="polizza">Polizza</label>
										<input type="text" name="polizza" id="polizza" class="form-control" value="{{ old('polizza') }}" placeholder="Polizza">
									</div>

									<!-- Inizio Validità Field -->
									<div class="form-group">
										<label for="inizio_validita">Inizio Validità</label>
										<input type="date" name="inizio_validita" id="inizio_validita" class="form-control" value="{{ old('inizio_validita') }}">
									</div>

									<!-- Tipo Scadenza Field -->
									<div class="form-group">
										<label for="tipo_scadenza">Tipo Scadenza</label>
										<select name="tipo_scadenza" id="tipo_scadenza" class="form-control">
											<option value="">Selezionare Tipo Scadenza</option>
											<option value="Quadrimestrale" @if(old('tipo_scadenza') == 'Quadrimestrale') selected @endif>Quadrimestrale</option>
											<option value="Semestrale" @if(old('tipo_scadenza') == 'Semestrale') selected @endif>Semestrale</option>
											<option value="Annuale" @if(old('tipo_scadenza') == 'Annuale') selected @endif>Annuale</option>
										</select>
									</div>

									<!-- Fine Validità Field -->
									<div class="form-group">
										<label for="fine_validita">Fine Validità</label>
										<input type="date" name="fine_validita" id="fine_validita" class="form-control" value="{{ old('fine_validita') }}">
									</div>


									<?php

										/*								<div class="form-group">
																			<label for="targa">Targa</label>
																			<input type="text" name="targa" id="targa" class="form-control" required>
																		</div>

																		<!-- Example input for stato_id -->
																		<div class="form-group">
																			<label for="stato_id">Stato ID</label>
																			<input type="text" name="stato_id" id="stato_id" class="form-control" required>
																		</div>*/
									?>

										<!-- Add other input fields for veicolo attributes here -->
									<!--ed::Items-->


									<div class="clearfix"></div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Inserisci I Dati Nel DB</button>
									</div>
									<!--ed::Items-->
								</div>
								<!--end::Tab pane-->
							</div>
							<!--end::Tab content-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Chart widget 8-->
				</div>
				<!--end::Col-->
			</div>
		</div>
	</div>
</form>


<script>
	function calculateFineValidita() {
		var inizioValidita = document.getElementById('inizio_validita').value;
		var tipoScadenza = document.getElementById('tipo_scadenza').value;
		var fineValiditaInput = document.getElementById('fine_validita');

		if (inizioValidita && tipoScadenza) {
			var startDate = new Date(inizioValidita);
			switch (tipoScadenza) {
				case 'Quadrimestrale':
					startDate.setMonth(startDate.getMonth() + 4);
					break;
				case 'Semestrale':
					startDate.setMonth(startDate.getMonth() + 6);
					break;
				case 'Annuale':
					startDate.setFullYear(startDate.getFullYear() + 1);
					break;
			}

			var dd = String(startDate.getDate()).padStart(2, '0');
			var mm = String(startDate.getMonth() + 1).padStart(2, '0'); // Months are 0-based
			var yyyy = startDate.getFullYear();

			fineValiditaInput.value = yyyy + '-' + mm + '-' + dd;
		}
	}
	// Function to fetch and set vehicle data based on search input
	function fetchAndSetVehicleData(searchInput,exactId=false) {
		if (searchInput.length >= 1) {
			if(exactId) {
				var searchUrl = "/veicolo/id/";
			} else {
				var searchUrl = "/veicolo/search/";
			}

			$.ajax({
				url: searchUrl + encodeURIComponent(searchInput),
				type: 'GET',
				success: function (data) {
					$('#veicoli').empty();
					vehicles = {};
					data.forEach(function (vehicle) {
						var optionText = '( ID: '+vehicle.id + ') ' + vehicle.targa + ' - ' +	vehicle.marca_nome + ' ' + vehicle.modello_nome + ' - ' + vehicle.colore;
						$('#veicoli').append($('<option>').val(optionText));
						vehicles[optionText] = vehicle.id;
						if(exactId) {
							$('#searchInput').val(optionText);
						}
					});
				}
			});
		}
	}

	$(document).ready(function () {
		var oldSearchInput = "{{ old('searchInput') }}";
		var oldVeicoloId = "@if(old('id_veicolo')) {{ old('id_veicolo') }}
			@elseif(isset($id_veicolo)) {{ $id_veicolo }} @endif";

		// If there's an old 'searchInput', repopulate the fields
		if (oldVeicoloId) {
			//$('#searchInput').val(oldSearchInput);
			$('#id_veicolo').val(oldVeicoloId);
			fetchAndSetVehicleData(oldVeicoloId,true);
		}

		//var vehicles = {};

		$('#searchInput').on('input', function () {
			fetchAndSetVehicleData($(this).val());
		});

		// When an option is selected from the datalist
		$('#searchInput').on('input', function () {
			var selectedText = $(this).val();
			var vehicleId = vehicles[selectedText];

			if (vehicleId) {
				$('#id_veicolo').val(vehicleId); // Update hidden field with the vehicle ID
			} else {
				$('#id_veicolo').val(''); // Clear hidden field if no vehicle is selected
			}
		});

		document.getElementById('inizio_validita').addEventListener('change', calculateFineValidita);
		document.getElementById('tipo_scadenza').addEventListener('change', calculateFineValidita);
	});

</script>
