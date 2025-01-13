<?php

namespace App\Http\Controllers;

use App\Models\ClientEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\InvoiceItem;
use App\Models\InvoiceTemplate;
use App\Models\Setting;

use App\Mail\InvoiceReminderMail;


use App\Mail\InvoiceMail;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with(['client', 'items'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::orderBy('companyname', 'asc')->get();
        $previousItems = InvoiceItem::select('name', 'price', 'tax_percentage')
            ->distinct()
            ->orderBy('name')
            ->get();
        $templates = InvoiceTemplate::all();

        return view('invoices.create', compact('clients', 'previousItems', 'templates'));
    }

    public function store(Request $request)
    {
//dd($request);
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.notes' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.tax_percentage' => 'required|in:6,21'
        ]);

        try {
            DB::beginTransaction();

            // Calculate amounts
            $amounts = $this->calculateInvoiceAmounts($validated['items']);

            // Create invoice
            $invoice = Invoice::create([
                'client_id' => $validated['client_id'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'amount_excl' => $amounts['amount_excl'],
                'amount_incl' => $amounts['amount_incl']
            ]);

            // Create invoice items
            foreach ($validated['items'] as $item) {
                //dd($item);
                $invoice->items()->create([
                    'name' => $item['name'],
                    'notes' => $item['notes'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax_percentage' => $item['tax_percentage']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('invoice.index')
                ->with('success', 'Invoice created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Creation Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error creating invoice: ' . $e->getMessage()]);
        }
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['client', 'items']); // Eager load relationships
        $clients = Client::orderBy('companyname', 'asc')->get();
        $previousItems = InvoiceItem::select('name', 'price', 'tax_percentage')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('invoices.edit', compact('invoice', 'clients', 'previousItems'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        //dd($request);
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.notes' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.tax_percentage' => 'required|in:6,21',
            'items.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate amounts
            $amounts = $this->calculateInvoiceAmounts($validated['items']);

            // Update invoice
            $invoice->update([
                'client_id' => $validated['client_id'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'amount_excl' => $amounts['amount_excl'],
                'amount_incl' => $amounts['amount_incl']
            ]);

            // Delete existing items
            $invoice->items()->delete();

            // Create new invoice items
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'],
                    'tax_percentage' => $item['tax_percentage']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('invoice.edit', $invoice->id)
                ->with('success', 'Invoice updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Update Error:', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error updating invoice: ' . $e->getMessage()]);
        }
    }

    public function print(Invoice $invoice)
    {
        $template = $invoice->template;
        $settings = Setting::where('group', '=', 'company')->get();
        $settings = [];
        $rawsettings = Setting::where('group', '=', 'company')->get();
        foreach ($rawsettings as $key => $value) {
            $settings[$value['key']] = $value['value'];
        }
        $imageUrl = /*env('APP_URL').'/storage/' .*/ $template->logo_path;

        $pdf = PDF::loadView('invoices.print', [
            'invoice' => $invoice,
            'client' => $invoice->client,
            'items' => $invoice->items,
            'template' => $template,
            'settings' => $settings,
            'imageUrl' => $imageUrl
        ]);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items']); // Eager load relationships
        $template = $invoice->template;

        $rawsettings = Setting::where('group', '=', 'company')->get();
        foreach ($rawsettings as $key => $value) {
            $settings[$value['key']] = $value['value'];
        }
        $imageUrl = '/storage/' . $template->logo_path;
        $clients = Client::orderBy('companyname', 'asc')->get();
        $previousItems = InvoiceItem::select('name', 'price', 'tax_percentage')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('invoices.show', compact('invoice', 'clients', 'previousItems', 'settings', 'imageUrl'));
    }

    private function calculateInvoiceAmounts(array $items): array
    {
        $amount_excl = 0;
        $amount_incl = 0;

        foreach ($items as $item) {
            $quantity = floatval($item['quantity']);
            $price = floatval($item['price']);
            $tax_percentage = floatval($item['tax_percentage']);

            $subtotal = $quantity * $price;
            $tax = $subtotal * ($tax_percentage / 100);

            $amount_excl += $subtotal;
            $amount_incl += ($subtotal + $tax);
        }

        return [
            'amount_excl' => round($amount_excl, 2),
            'amount_incl' => round($amount_incl, 2)
        ];
    }

    private function updateInvoiceAmounts(Invoice $invoice): void
    {
        $amount_excl = 0;
        $amount_incl = 0;

        foreach ($invoice->items as $item) {
            $subtotal = $item->quantity * $item->price;
            $tax = $subtotal * ($item->tax_percentage / 100);

            $amount_excl += $subtotal;
            $amount_incl += ($subtotal + $tax);
        }

        $invoice->update([
            'amount_excl' => round($amount_excl, 2),
            'amount_incl' => round($amount_incl, 2)
        ]);

        $invoice->refresh();
    }

    private function generateInvoiceNumber(): int
    {
        $lastInvoice = Invoice::orderBy('created_at', 'desc')->first();
        $year = Carbon::now()->format('Y');

        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number ?? '0', 5);
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function emailInvoice($id)
    {

        try {
            $invoice = Invoice::with(['client', 'items'])->findOrFail($id);

            $template = $invoice->template;
            $settings = Setting::where('group', '=', 'company')->get();
            $settings = [];
            $rawsettings = Setting::where('group', '=', 'company')->get();
            foreach ($rawsettings as $key => $value) {
                $settings[$value['key']] = $value['value'];
            }
            $imageUrl = /*env('APP_URL').'/storage/' .*/
                $template->logo_path;

            $pdf = PDF::loadView('invoices.print', [
                'invoice' => $invoice,
                'client' => $invoice->client,
                'items' => $invoice->items,
                'template' => $template,
                'settings' => $settings,
                'imageUrl' => $imageUrl
            ]);



            $pdfPath = storage_path('app/temp/invoice-' . $invoice->invoice_number . '.pdf');
            $pdf->save($pdfPath);
//dd($pdfPath);
            Mail::to($invoice->client->email)
                ->send(new InvoiceMail($invoice, $pdfPath));



            unlink($pdfPath);

            $invoice->update(['status' => 'sent']);

            return redirect()->back()->with('success', 'Invoice has been emailed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send invoice: ' . $e->getMessage());
        }
    }

    public function sendReminder(Invoice $invoice)
    {
        try {
            $template = $invoice->template;
            $settings = Setting::where('group', '=', 'company')->get();
            $settings = [];
            $rawsettings = Setting::where('group', '=', 'company')->get();
            foreach ($rawsettings as $key => $value) {
                $settings[$value['key']] = $value['value'];
            }
            $imageUrl = public_path('/storage/' . $template->logo_path);

            $pdf = PDF::loadView('invoices.print', [
                'invoice' => $invoice,
                'client' => $invoice->client,
                'items' => $invoice->items,
                'template' => $template,
                'settings' => $settings,
                'imageUrl' => $imageUrl
            ]);

            $pdfPath = storage_path('app/temp/invoice-' . $invoice->invoice_number . '.pdf');
            $pdf->save($pdfPath);

            Mail::to($invoice->client->email)
                ->send(new InvoiceReminderMail($invoice, $pdfPath));

            unlink($pdfPath);

            return redirect()->back()->with('success', 'Reminder email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            DB::beginTransaction();

            // Delete all related invoice items first
            $invoice->items()->delete();

            // Delete the invoice
            $invoice->delete();

            DB::commit();

            return redirect()
                ->route('invoice.index')
                ->with('success', 'Invoice deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Deletion Error:', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Error deleting invoice: ' . $e->getMessage()]);
        }
    }
}
