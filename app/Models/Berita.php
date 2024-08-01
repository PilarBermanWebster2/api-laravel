<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, "id_kategori");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "id_user");
    }
    public function tag()
    {
        return $this->belongsToMany(
            Tag::class,
            "tag_beritas",
            "id_berita",
            "id_tag"
        );
    }
}
