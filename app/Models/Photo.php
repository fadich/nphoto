<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photo';

    protected $fillable = [
        'title',
        'description',
        'base_path',
        'filename',
        'client_filename',
        'original_path',
    ];
}
