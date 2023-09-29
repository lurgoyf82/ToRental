<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modello extends Model
{
    use HasFactory;

    protected $table = 'modello';
    public static string $tableName = 'modello';
    protected $fillable = ['nome', 'id_marca'];
}
