<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoAsse extends BaseModel
	{
		use HasFactory;

		protected $table = 'tipo_asse';
		protected $fillable = ['nome'];
	}
