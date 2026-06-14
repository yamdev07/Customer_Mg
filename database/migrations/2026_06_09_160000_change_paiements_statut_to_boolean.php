<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * paiements.statut était un enum('payé','non') alors que tout le code applicatif
 * (cast booléen du modèle, scopes ->where('statut', true/false), actions de paiement)
 * la traite comme un booléen. Cet écart cassait la détection des impayés et
 * provoquait une erreur « Data truncated » dès qu'on écrivait false.
 * On convertit la colonne en booléen (tinyint) en préservant les données.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Étape intermédiaire en texte pour libérer la contrainte enum, puis mapping.
            DB::statement("ALTER TABLE paiements MODIFY statut VARCHAR(10) NOT NULL DEFAULT 'non'");
            DB::statement("UPDATE paiements SET statut = CASE WHEN statut = 'payé' THEN '1' ELSE '0' END");
            DB::statement('ALTER TABLE paiements MODIFY statut TINYINT(1) NOT NULL DEFAULT 0');

            return;
        }

        // Autres SGBD (SQLite pour les tests) : recréation propre de la colonne.
        if (Schema::hasColumn('paiements', 'statut')) {
            Schema::table('paiements', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }

        Schema::table('paiements', function (Blueprint $table) {
            $table->boolean('statut')->default(false);
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE paiements MODIFY statut VARCHAR(10) NOT NULL DEFAULT 'non'");
            DB::statement("UPDATE paiements SET statut = CASE WHEN statut = '1' THEN 'payé' ELSE 'non' END");
            DB::statement("ALTER TABLE paiements MODIFY statut ENUM('payé','non') NOT NULL DEFAULT 'non'");

            return;
        }

        if (Schema::hasColumn('paiements', 'statut')) {
            Schema::table('paiements', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }

        Schema::table('paiements', function (Blueprint $table) {
            $table->string('statut')->default('non');
        });
    }
};
