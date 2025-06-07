<?php
// 📁 database/migrations/2024_01_01_000004_create_distribution_messages_table.php

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
        Schema::create('distribution_messages', function (Blueprint $table) {
            $table->id('id_distribution');
            $table->foreignId('id_message')->constrained('messages', 'id_message')->onDelete('cascade');
            $table->foreignId('id_service')->constrained('services', 'id_service')->onDelete('cascade');
            $table->timestamp('date_distribution')->useCurrent();
            $table->enum('statut_lecture', ['non_lu', 'lu'])->default('non_lu');
            $table->timestamps();
            
            // Index composites pour optimiser les requêtes
            $table->index(['id_message', 'id_service']);
            $table->index(['id_service', 'statut_lecture']);
            $table->index(['id_message', 'statut_lecture']);
            $table->index('date_distribution');
            
            // Contrainte unique : un message ne peut être distribué qu'une seule fois à un service
            $table->unique(['id_message', 'id_service'], 'unique_message_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_messages');
    }
};