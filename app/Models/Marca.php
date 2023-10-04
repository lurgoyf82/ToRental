<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Marca extends BaseModel
	{
		use HasFactory;

		protected $table = 'marca';
		protected $fillable = ['nome'];
	}
