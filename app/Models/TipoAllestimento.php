<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoAllestimento extends BaseModel
	{
		use HasFactory;

		protected $table = 'tipo_allestimento';
		protected $fillable = ['nome'];
	}
