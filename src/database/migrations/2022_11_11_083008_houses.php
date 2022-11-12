<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', static function(Blueprint $table) {
           $table->id();
           $table->string('title')->unique();
           $table->unsignedInteger('price')->default(0)->index();
           $table->unsignedInteger('bedrooms')->default(0)->index();
           $table->unsignedInteger('bathrooms')->default(0)->index();
           $table->unsignedInteger('storeys')->default(0)->index();
           $table->unsignedInteger('garages')->default(0)->index();
           $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('houses');
    }
};
