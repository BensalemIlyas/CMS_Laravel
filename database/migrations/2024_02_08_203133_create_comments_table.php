<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('contenu');
            $table->boolean('statut')->default(true); // true pour visible, false pour non visible
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('posts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
