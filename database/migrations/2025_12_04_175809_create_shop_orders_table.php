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
    Schema::create('shop_orders', function (Blueprint $table) {
        $table->id();

        // id stripe ou woocommerce (order_id)
        $table->string('external_order_id')->nullable();

        // identifiant du joueur issu d'ArkShop
        $table->string('player_id'); 

        // produit acheté
        $table->unsignedBigInteger('product_id');

        // statut
        $table->enum('status', [
            'pending',       // créé mais pas payé
            'paid',          // paiement validé
            'delivered',     // item envoyé IG
            'failed',        // erreur livraison
            'refunded'
        ])->default('pending');

        // détails
        $table->json('meta')->nullable();

        $table->timestamps();

        // relations
        $table->foreign('product_id')->references('id')->on('shop_products');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
