<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class DestinazioneUso extends Model
	{
		use HasFactory;

		protected $table = 'destinazione_uso';
		protected $fillable = ['nome'];
	}
