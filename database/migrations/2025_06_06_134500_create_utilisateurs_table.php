<?php
// 📁 database/migrations/2024_01_01_000001_create_utilisateurs_table.php

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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->string('nom', 100);
            $table->string('email', 150)->unique();
            $table->string('mot_de_passe', 255);
            $table->enum('role', ['admin', 'operateur']);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('email');
            $table->index('role');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};