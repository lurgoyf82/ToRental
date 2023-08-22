<form action="{{ route('login') }}" method="POST">
    @csrf
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
</form>

<!-- script
<script>
    $(document).ready(function() {
        $('#id_tipo').change(function() {
            var id_tipo = $(this).val();
            // AJAX request
            $.ajax({
                url: '/getMarca/' + id_tipo,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        var option = "<option value=''>Select Marca</option>";
                        for (var i = 0; i < len; i++) {
                            option += "<option value='" +
                                response['data'][i].id + "'>" +
                                response['data'][i].nome + "</option>";
                        }
                        $("#id_marca").html(option);
                    }
                }
            });
        });
    });
</script>

-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var marcaDropdown = document.getElementById('marca');
        var modelloDropdown = document.getElementById('modello');

        marcaDropdown.addEventListener('change', function() {
            var marcaID = this.value;

            if (marcaID) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/get-modello-by-marca/' + marcaID, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        updateModelloDropdown(response);
                    }
                };
                xhr.send();
            } else {
                clearModelloDropdown();
            }
        });

        function updateModelloDropdown(data) {
            clearModelloDropdown();
            var defaultOption = document.createElement('option');
            defaultOption.text = 'Select';
            modelloDropdown.add(defaultOption);

            for (var key in data) {
                var option = document.createElement('option');
                option.value = key;
                option.text = data[key];
                modelloDropdown.add(option);
            }
        }

        function clearModelloDropdown() {
            while (modelloDropdown.firstChild) {
                modelloDropdown.removeChild(modelloDropdown.firstChild);
            }
        }
    });

</script>

