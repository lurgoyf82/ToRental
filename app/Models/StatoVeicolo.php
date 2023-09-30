<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class StatoVeicolo extends Model
	{
		use HasFactory;

		protected $table = 'stato_veicolo';
		protected $fillable = ['nome'];
	}
