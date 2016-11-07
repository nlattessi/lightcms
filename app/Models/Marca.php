<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';

    protected $fillable = [
        'order',
        'name',
        'link',
    ];

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}
