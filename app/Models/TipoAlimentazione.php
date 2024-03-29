<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoAlimentazione extends BaseModel
	{
		use HasFactory;

		protected $table = 'tipo_alimentazione';
		protected $fillable = ['nome'];
	}
