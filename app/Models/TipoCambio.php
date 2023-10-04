<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoCambio extends BaseModel
	{
		use HasFactory;

		protected $table = 'tipo_cambio';
		protected $fillable = ['nome'];
	}
