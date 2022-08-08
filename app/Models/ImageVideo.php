<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UuidTrait;

class ImageVideo extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'images_video';

    protected $fillable = [
        'path',
        'type',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
