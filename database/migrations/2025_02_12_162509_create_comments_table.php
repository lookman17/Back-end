<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_id'); // Foreign key ke galleries
            $table->unsignedBigInteger('user_id'); // Foreign key ke users
            $table->text('content');
            $table->timestamps();
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Referensi ke users
        });
    }

    public function down() {
        Schema::dropIfExists('comments');
    }
};
