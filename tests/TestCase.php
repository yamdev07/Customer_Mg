<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Les tests fonctionnels n'ont pas besoin des assets compilés par Vite.
        // Évite l'erreur « Vite manifest not found » quand public/build est absent
        // (ex. en CI, où npm run build n'est pas exécuté).
        $this->withoutVite();
    }
}
