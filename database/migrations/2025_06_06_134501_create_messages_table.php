<?php
// 📁 database/migrations/2024_01_01_000003_create_messages_table.php

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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->string('numero_reference', 50)->unique();
            $table->string('expediteur', 200);
            $table->string('email_expediteur', 150)->nullable();
            $table->string('objet', 200);
            $table->text('contenu');
            $table->timestamp('date_reception')->useCurrent();
            $table->enum('statut', ['recu', 'distribue', 'traite'])->default('recu');
            $table->enum('priorite', ['normale', 'haute', 'urgente'])->default('normale');
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('numero_reference');
            $table->index('expediteur');
            $table->index('statut');
            $table->index('priorite');
            $table->index('date_reception');
            $table->index(['statut', 'priorite']); // Index composite
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};