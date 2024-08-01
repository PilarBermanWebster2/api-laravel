<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("beritas", function (Blueprint $table) {
            $table->id();
            $table->string("nama_berita");
            $table->string("slug");
            $table->string("foto");
            $table->text("deskripsi");
            $table
                ->foreignId("id_kategori")
                ->onDelete("cascade")
                ->constrained("kategoris");
            $table
                ->foreignId("id_user")
                ->onDelete("cascade")
                ->constrained("users");
            $table->timestamps();
        });
        Schema::create("tag_beritas", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("id_tag")
                ->onDelete("cascade")
                ->constrained("tags");
            $table
                ->foreignId("id_berita")
                ->onDelete("cascade")
                ->constrained("beritas");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("beritas");
        Schema::dropIfExists("tag_beritas");
    }
};
