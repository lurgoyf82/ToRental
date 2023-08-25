<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronotachigrafo extends Model
{
    use HasFactory;

    protected $table = 'cronotachigrafo';
    protected $fillable = ['veicolo_id','anno','data_pagamento','inizio_validita','fine_validita','importo'];
}
