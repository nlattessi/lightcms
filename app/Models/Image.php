<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'name',
        'filename',
    ];

    public static $mimeTypes = [
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];

    public static function getAllowedExtensions($string = false)
    {
        $allowedExtensions = array_keys(self::$mimeTypes);

        if ($string == true) {
            $allowedExtensions = implode('|', $allowedExtensions);
        }

        return $allowedExtensions;
    }

    public function slides()
    {
        return $this->hasMany('App\Models\Slide');
    }

    public function marcas()
    {
        return $this->hasMany('App\Models\Marca');
    }
}
