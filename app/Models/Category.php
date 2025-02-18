<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Jika kategori memiliki banyak konten (opsional)
    public function contents()
    {
        return $this->hasMany(Gallery::class);
    }
}
