<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assicurazione extends Model
{
    use HasFactory;

    protected $table = 'assicurazione';
    protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo','agenzia','polizza','tipo_scadenza'];
}
