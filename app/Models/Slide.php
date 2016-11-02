<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = 'slides';

    protected $fillable = [
        'order',
        'name',
        'caption',
    ];

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}
