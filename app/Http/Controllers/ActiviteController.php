<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActiviteController extends Controller
{
    /**
     * Journal d'activité : historique des actions importantes.
     */
    public function __invoke(Request $request): View
    {
        $query = Activite::with(['user', 'client'])->latest();

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $activites = $query->paginate(20)->withQueryString();

        return view('activites.index', [
            'activites' => $activites,
            'actionFiltre' => $action,
            'meta' => Activite::META,
        ]);
    }
}
