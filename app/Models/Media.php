<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UuidTrait;

class Media extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'medias_video';

    protected $fillable = [
        'file_path',
        'encoded_path',
        'media_status',
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
