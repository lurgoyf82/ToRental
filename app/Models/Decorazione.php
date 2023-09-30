<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Decorazione extends AlertBase
	{
		use HasFactory;

		protected $table = 'decorazione';
		public static string $tableName = 'decorazione';
		protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];
	}
