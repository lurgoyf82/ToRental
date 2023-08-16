<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veicolo extends Model
{
    use HasFactory;

    // If the table name is not pluralized, or different from the class name, you can specify it:
    // protected $table = 'veicolo';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'stato_id'
        // Other attributes related to veicolo
    ];

    // Define relationships, mutators, and other model functionality here if needed
}
