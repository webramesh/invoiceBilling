<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    /**
     * Display the specified invoice publicly.
     */
    public function show($hash)
    {
        $invoice = Invoice::where('hash', $hash)->with(['client', 'subscription', 'items', 'payments'])->firstOrFail();

        return view('invoices.public-show', compact('invoice'));
    }

    /**
     * Download the PDF publicly.
     */
    public function downloadPdf($hash)
    {
        $invoice = Invoice::where('hash', $hash)->with(['client', 'subscription', 'items'])->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }
}
