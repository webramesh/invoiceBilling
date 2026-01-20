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
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'subscription.service']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_outstanding' => Invoice::where('status', 'unpaid')->sum('total'),
            'total_paid_month' => Payment::whereMonth('payment_date', now()->month)->sum('amount'),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
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

    public function sendEmail(Invoice $invoice)
    {
        try {
            \Illuminate\Support\Facades\Mail::to($invoice->client->email)->send(new \App\Mail\InvoiceMailable($invoice));
            return back()->with('success', 'Invoice email sent successfully to ' . $invoice->client->email);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendWhatsApp(Invoice $invoice)
    {
        $template = \App\Models\Setting::get('whatsapp_template', "Hello {name},\n\nYour invoice #{invoice_number} for {service_name} is ready.\n\nAmount: Rs. {amount}\nDue Date: {due_date}\n\nView Invoice: {invoice_url}\n\nThank you!");

        $replacements = [
            '{name}' => $invoice->client->name,
            '{invoice_number}' => $invoice->invoice_number,
            '{service_name}' => $invoice->subscription->service->name ?? 'Service',
            '{amount}' => number_format($invoice->total, 2),
            '{due_date}' => $invoice->due_date->format('M d, Y'),
            '{invoice_url}' => route('invoices.public.show', $invoice->hash),
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $template);

        $phone = preg_replace('/[^0-9]/', '', $invoice->client->whatsapp_number ?? $invoice->client->phone);

        if (empty($phone)) {
            return back()->with('error', 'Client does not have a WhatsApp number.');
        }

        $url = "https://wa.me/{$phone}?text=" . urlencode($message);

        return redirect()->away($url);
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
