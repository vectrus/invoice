<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTemplate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class InvoiceTemplateController extends Controller
{
    public function index()
    {
        $templates = InvoiceTemplate::all();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'html' => 'required|string',
            'is_default' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validated['is_default']) {
            InvoiceTemplate::where('is_default', true)
                ->update(['is_default' => false]);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        InvoiceTemplate::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'html' => $validated['html'],
            'is_default' => $validated['is_default'] ?? false,
            'logo_path' => $logoPath
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully');
    }


    public function edit(InvoiceTemplate $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, InvoiceTemplate $template)
    {
       // dd($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'html' => 'required|string',
            'is_default' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validated['is_default']) {
            InvoiceTemplate::where('is_default', true)
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        $data = [
            'name' => $validated['name'],
            'content' => $validated['content'],
            'html' => $validated['html'],
            'is_default' => $validated['is_default'] ?? false,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo if exists

            if ($template->logo_path) {
                Storage::disk('public')->delete($template->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $template->update($data);

        return redirect()->route('templates.index')
            ->with('success', 'Template updated successfully');
    }

    public function destroy(InvoiceTemplate $template)
    {
        if ($template->is_default) {
            return back()->with('error', 'Cannot delete the default template');
        }

        if ($template->invoices()->exists()) {
            return back()->with('error', 'Cannot delete template that is being used by invoices');
        }

        // Delete logo file if exists
        if ($template->logo_path) {
            Storage::disk('public')->delete($template->logo_path);
        }

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully');
    }

    public function preview(InvoiceTemplate $template)
    {
        $settings = [];
        $rawsettings = Setting::where('group', '=', 'company')->get();
        foreach($rawsettings as $key => $value) {
            //dd($value['key']);
            //$settings->$value['key'] = $value['value'];
            $settings[$value['key']] =
                       $value['value']
                  ;
        }

        //$imageUrl = public_path('/storage/' . $template->logo_path);
        $imageUrl = /*env('API_URL').'/storage/' .*/ $template->logo_path;
//dd($imageUrl);
        // Get the latest invoice for preview
        $invoice = \App\Models\Invoice::with(['client', 'items'])
            ->latest()
            ->first();

        if (!$invoice) {
            // Create dummy data for preview
            $invoice = new \App\Models\Invoice([
                'invoice_number' => 'PREVIEW-001',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'paymentUrl' => generatePaymentUrl()
            ]);

            //dd($invoice->issue_date);

            $invoice->client = new \App\Models\Client([
                'name' => 'Sample Client',
                'email' => 'client@example.com',
                'address' => "123 Client Street\nCity, Country",
                'vat_number' => 'BE0123456789'
            ]);

            $invoice->items = collect([
                new \App\Models\InvoiceItem([
                    'name' => 'Sample Service',
                    'quantity' => 10,
                    'price' => 100.00,
                    'tax_percentage' => 21
                ])
            ]);
        }

        return view('invoices.print', compact('invoice', 'template', 'settings', 'imageUrl'));
    }
}
