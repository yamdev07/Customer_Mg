<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration consolidée pour la table users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('commercial')->after('email');
            }
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable()->after('phone');
            }

            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'first_name', 'phone', 'company']);
        });
    }
};
