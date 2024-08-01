<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public function berita()
    {
        return $this->belongsToMany(
            Berita::class,
            "tag_beritas",
            "id_tag",
            "id_berita"
        );
    }
}
