<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bombole extends Model
{
    use HasFactory;

    protected $table = 'bombole';
    protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];
}
