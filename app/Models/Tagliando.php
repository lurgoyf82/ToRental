<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagliando extends AlertBase
{
	use HasFactory;

	protected $table = 'tagliando';
	public static string $tableName = 'tagliando';
	protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];
}
