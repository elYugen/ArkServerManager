<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('shop_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('points_cost')->nullable(); // option ArkShop
                $table->json('rcon_commands'); // liste des commandes envoyées en jeu
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('shop_categories')->nullOnDelete();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
