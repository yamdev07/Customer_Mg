<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 10, 2)->nullable();
            $table->integer('jour_reabonnement')->nullable();
            $table->date('date_reabonnement')->nullable();
            $table->boolean('a_paye')->default(false);
            $table->timestamps();

            $table->unique(['client_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_service');
    }
};
