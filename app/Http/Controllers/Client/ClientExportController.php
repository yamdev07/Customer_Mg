<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ClientExportController extends Controller
{
    /**
     * Exporter les clients en PDF.
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'all');

        $clients = match ($type) {
            'expired' => Client::actifs()
                ->where('a_paye', false)
                ->whereDate('date_reabonnement', '<', now())
                ->get(),
            'active' => Client::actifs()->get(),
            default => Client::all(),
        };

        $pdf = Pdf::loadView('clients.export_pdf', compact('clients'));

        return $pdf->download('clients_'.$type.'.pdf');
    }

    /**
     * Exporter les clients actifs en PDF (ancienne méthode).
     */
    public function exportActivePdf()
    {
        $clients = Client::actifs()->get();
        $pdf = Pdf::loadView('clients.pdf', compact('clients'));

        return $pdf->download('clients_actifs.pdf');
    }
}
