<?php
// 📁 database/migrations/2024_01_01_000005_create_pieces_jointes_table.php

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
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id('id_piece');
            $table->foreignId('id_message')->constrained('messages', 'id_message')->onDelete('cascade');
            $table->string('nom_fichier', 255);
            $table->string('chemin_fichier', 500);
            $table->string('type_mime', 100)->nullable();
            $table->bigInteger('taille_fichier')->nullable(); // en octets
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('id_message');
            $table->index('nom_fichier');
            $table->index('type_mime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};