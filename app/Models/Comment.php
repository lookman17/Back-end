<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['gallery_id', 'user_id', 'content'];

    // Relasi ke Gallery (Setiap komentar terkait ke satu galeri)
    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id', 'id');
    }

    // Relasi ke User (Setiap komentar ditulis oleh satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
