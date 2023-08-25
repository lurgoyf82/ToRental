<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tachigrafo extends Model
{
    use HasFactory;

    protected $table = 'tachigrafo';
    protected $fillable = ['veicolo_id','anno','data_pagamento','inizio_validita','fine_validita','importo'];
}
