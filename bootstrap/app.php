<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Une route d'action (PATCH/POST/DELETE) atteinte en GET — typiquement via
        // la barre d'adresse, un favori ou l'historique — ne doit pas afficher une
        // erreur 405 brute : on redirige vers la liste avec un message clair.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            // Redirection vers la liste : la requête suivante (re)passe par le
            // middleware d'auth qui renverra vers /login si nécessaire.
            return redirect()->to(route('clients.actifs'))->with(
                'warning',
                "Cette action doit être déclenchée via un bouton de l'application, pas en ouvrant l'URL directement."
            );
        });
    })->create();
