//form that points to the create_veicolo route
<form action="{{ route('login') }}" method="POST">
//list all accessible routes from this file
    @csrf
    <!-- Example input for targa -->
    <div class="form-group">
        <label for="id_proprietario">Proprietario</label>
        <select name="id_proprietario" id="id_proprietario" class="form-control">
            @foreach($lista_societa as $societa)
                <option value="{{ $societa->id }}">{{ $societa->nome }}</option>
            @endforeach
        </select>
    </div>
    <?php
        /*
    <div class="form-group">
        <label for="id_tipo">Tipo</label>
        <select name="id_tipo" id="id_tipo" class="form-control">
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
            @endforeach
        </select>
    </div>
        */
    ?>
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
