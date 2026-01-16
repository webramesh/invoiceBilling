<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'subscription', 'items', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Mark the invoice as paid.
     */
    public function markAsPaid(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice is already paid.');
        }

        DB::transaction(function () use ($invoice, $request) {
            $invoice->update(['status' => 'paid']);

            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_date' => now(),
                'amount' => $invoice->total,
                'payment_method' => $request->input('payment_method', 'manual'),
                'transaction_reference' => $request->input('reference'),
                'notes' => 'Marked manually as paid',
            ]);
        });

        return back()->with('success', 'Invoice marked as paid and payment recorded.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'subscription', 'items']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot delete a paid invoice.');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
