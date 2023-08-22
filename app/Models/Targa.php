<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Targa extends Model
{
    use HasFactory;

    protected $table = 'targa';
    protected $fillable = ['id_veicolo', 'targa', 'data_immatricolazione'];
}
