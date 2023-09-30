<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoVeicolo extends Model
	{
		use HasFactory;

		protected $table = 'tipo_veicolo';
		protected $fillable = ['nome'];
	}
