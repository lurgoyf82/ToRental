
<form action="{{ url()->current() }}" method="POST">
    @csrf
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->
            <div class="row gx-5 gx-xl-10">
                <!--begin::Col-->
                <div class="col-xxl-6 mb-5 mb-xl-10">
                    <!--begin::Chart widget 8-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Performance Overview</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Users from all channels</span>
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

                                    <!-- Select for Societa -->
                                    <div class="form-group">
                                        <label for="id_proprietario">Proprietario</label>
                                        <select name="id_proprietario" id="id_proprietario" class="form-control">
                                            @foreach($lista_societa as $societa)
                                                <option value="{{ $societa->id }}">{{ $societa->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo -->
                                    <div class="form-group">
                                        <label for="id_tipo">Tipo</label>
                                        <select name="id_tipo" id="id_tipo" class="form-control">
                                            @foreach($lista_tipo_veicolo as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Select for Marca -->
                                    <div class="form-group">
                                        <label for="id_marca">Marca</label>
                                        <select name="id_marca" id="id_marca" class="form-control">
                                            @foreach($lista_marca as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Modello -->
                                    <div class="form-group">
                                        <label for="id_modello">Modello</label>
                                        <select name="id_modello" id="id_modello" class="form-control">
                                            @foreach($lista_modello as $modello)
                                                <option value="{{ $modello->id }}">{{ $modello->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <!-- Select for Tipo Allestimento -->
                                    <div class="form-group">
                                        <label for="id_tipo_allestimento">Tipo Allestimento</label>
                                        <select name="id_tipo_allestimento" id="id_tipo_allestimento" class="form-control">
                                            @foreach($lista_tipo_allestimento as $tipo_allestimento)
                                                <option value="{{ $tipo_allestimento->id }}">{{ $tipo_allestimento->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Asse -->
                                    <div class="form-group">
                                        <label for="id_tipo_asse">Tipo Asse</label>
                                        <select name="id_tipo_asse" id="id_tipo_asse" class="form-control">
                                            @foreach($lista_tipo_asse as $tipo_asse)
                                                <option value="{{ $tipo_asse->id }}">{{ $tipo_asse->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Cambio -->
                                    <div class="form-group">
                                        <label for="id_tipo_cambio">Tipo Cambio</label>
                                        <select name="id_tipo_cambio" id="id_tipo_cambio" class="form-control">
                                            @foreach($lista_tipo_cambio as $tipo_cambio)
                                                <option value="{{ $tipo_cambio->id }}">{{ $tipo_cambio->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Alimentazione -->
                                    <div class="form-group">
                                        <label for="id_tipo_alimentazione">Tipo Alimentazione</label>
                                        <select name="id_tipo_alimentazione" id="id_tipo_alimentazione" class="form-control">
                                            @foreach($lista_alimentazione as $tipo_alimentazione)
                                                <option value="{{ $tipo_alimentazione->id }}">{{ $tipo_alimentazione->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Destinazione D'Uso -->
                                    <div class="form-group">
                                        <label for="id_destinazione_uso">Destinazione D'Uso</label>
                                        <select name="id_destinazione_uso" id="id_destinazione_uso" class="form-control">
                                            @foreach($lista_destinazione_uso as $destinazione_uso)
                                                <option value="{{ $destinazione_uso->id }}">{{ $destinazione_uso->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    id
                                    id_proprietario
                                    id_tipo_veicolo
                                    id_tipo_allestimento
                                    id_marca
                                    id_modello
                                    colore
                                    lunghezza_esterna
                                    larghezza_esterna
                                    massa
                                    portata
                                    cilindrata
                                    potenza
                                    numero_assi
                                    tipo_asse
                                    tipo_cambio
                                    alimentazione
                                    destinazione_uso
                                    created_at
                                    updated_at


                                    <div class="form-group">
                                        <label for="targa">Targa</label>
                                        <input type="text" name="targa" id="targa" class="form-control" required>
                                    </div>

                                    <!-- Example input for stato_id -->
                                    <div class="form-group">
                                        <label for="stato_id">Stato ID</label>
                                        <input type="text" name="stato_id" id="stato_id" class="form-control" required>
                                    </div>

                                    <!-- Add other input fields for veicolo attributes here -->

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Create Veicolo</button>
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
                <!--begin::Col-->
                <div class="col-xxl-6 mb-5 mb-xl-10">
                    <!--begin::Chart widget 8-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Performance Overview</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Users from all channels</span>
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

                                    <!-- Select for Societa -->
                                    <div class="form-group">
                                        <label for="id_proprietario">Proprietario</label>
                                        <select name="id_proprietario" id="id_proprietario" class="form-control">
                                            @foreach($lista_societa as $societa)
                                                <option value="{{ $societa->id }}">{{ $societa->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo -->
                                    <div class="form-group">
                                        <label for="id_tipo">Tipo</label>
                                        <select name="id_tipo" id="id_tipo" class="form-control">
                                            @foreach($lista_tipo_veicolo as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Select for Marca -->
                                    <div class="form-group">
                                        <label for="id_marca">Marca</label>
                                        <select name="id_marca" id="id_marca" class="form-control">
                                            @foreach($lista_marca as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Modello -->
                                    <div class="form-group">
                                        <label for="id_modello">Modello</label>
                                        <select name="id_modello" id="id_modello" class="form-control">
                                            @foreach($lista_modello as $modello)
                                                <option value="{{ $modello->id }}">{{ $modello->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <!-- Select for Tipo Allestimento -->
                                    <div class="form-group">
                                        <label for="id_tipo_allestimento">Tipo Allestimento</label>
                                        <select name="id_tipo_allestimento" id="id_tipo_allestimento" class="form-control">
                                            @foreach($lista_tipo_allestimento as $tipo_allestimento)
                                                <option value="{{ $tipo_allestimento->id }}">{{ $tipo_allestimento->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Asse -->
                                    <div class="form-group">
                                        <label for="id_tipo_asse">Tipo Asse</label>
                                        <select name="id_tipo_asse" id="id_tipo_asse" class="form-control">
                                            @foreach($lista_tipo_asse as $tipo_asse)
                                                <option value="{{ $tipo_asse->id }}">{{ $tipo_asse->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Cambio -->
                                    <div class="form-group">
                                        <label for="id_tipo_cambio">Tipo Cambio</label>
                                        <select name="id_tipo_cambio" id="id_tipo_cambio" class="form-control">
                                            @foreach($lista_tipo_cambio as $tipo_cambio)
                                                <option value="{{ $tipo_cambio->id }}">{{ $tipo_cambio->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Tipo Alimentazione -->
                                    <div class="form-group">
                                        <label for="id_tipo_alimentazione">Tipo Alimentazione</label>
                                        <select name="id_tipo_alimentazione" id="id_tipo_alimentazione" class="form-control">
                                            @foreach($lista_alimentazione as $tipo_alimentazione)
                                                <option value="{{ $tipo_alimentazione->id }}">{{ $tipo_alimentazione->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select for Destinazione D'Uso -->
                                    <div class="form-group">
                                        <label for="id_destinazione_uso">Destinazione D'Uso</label>
                                        <select name="id_destinazione_uso" id="id_destinazione_uso" class="form-control">
                                            @foreach($lista_destinazione_uso as $destinazione_uso)
                                                <option value="{{ $destinazione_uso->id }}">{{ $destinazione_uso->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    id
                                    id_proprietario
                                    id_tipo_veicolo
                                    id_tipo_allestimento
                                    id_marca
                                    id_modello
                                    colore
                                    lunghezza_esterna
                                    larghezza_esterna
                                    massa
                                    portata
                                    cilindrata
                                    potenza
                                    numero_assi
                                    tipo_asse
                                    tipo_cambio
                                    alimentazione
                                    destinazione_uso
                                    created_at
                                    updated_at


                                    <div class="form-group">
                                        <label for="targa">Targa</label>
                                        <input type="text" name="targa" id="targa" class="form-control" required>
                                    </div>

                                    <!-- Example input for stato_id -->
                                    <div class="form-group">
                                        <label for="stato_id">Stato ID</label>
                                        <input type="text" name="stato_id" id="stato_id" class="form-control" required>
                                    </div>

                                    <!-- Add other input fields for veicolo attributes here -->

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Create Veicolo</button>
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
