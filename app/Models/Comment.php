<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'user_name', 'message'];
    public function content()
    {
        return $this->belongsTo(Gallery::class);
    }
}
