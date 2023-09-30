<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class TipoAsse extends Model
	{
		use HasFactory;

		protected $table = 'tipo_asse';
		protected $fillable = ['nome'];
	}
