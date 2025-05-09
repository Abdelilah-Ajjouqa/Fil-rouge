<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'path',
        'type',
    ];

    public function post()
    {
        return $this->belongsTo(Posts::class);
    }
}
