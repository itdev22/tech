<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $appends = ['orginal_url'];

    protected $table = 'media';

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'mime_type',
        'size',
        'collection_name',
        'disk',
        'conversions_disk',
        'uuid',
        'model_type',
        'model_id',
        'manipulations',
        'custom_properties',
        'responsive_images',
        'order_column',
        'created_at',
        'updated_at'
    ];

    protected
        $casts = [
        'manipulations' => 'array',
        'custom_properties' => 'array',
        'responsive_images' => 'array',
    ];


    // add original_url attribute
    public function getOrginalUrlAttribute()
    {
        return env('APP_URL').'/storage/1/'.$this->file_name;
    }

}
