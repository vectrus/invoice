<?php

namespace App\Http\Controllers;

use App\Models\ArchivedInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivedInvoiceController extends Controller
{
    public function index()
    {
        $invoices = ArchivedInvoice::with('client')->paginate(15);
        return view('archived-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = \App\Models\Client::all();
        return view('archived-invoices.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'amount_incl' => 'required|numeric',
            'amount_excl' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id'
        ]);

        ArchivedInvoice::create($validated);

        return redirect()->route('archived-invoices.index')
            ->with('success', 'Invoice archived successfully');
    }

    public function import(Request $request)
    {

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // Max 10MB
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            // Parse CSV with proper encoding handling
            $content = $this->detectAndConvertEncoding(file_get_contents($path));
            $csvData = array_map(function ($line) {
                return str_getcsv($line, ',', '"', '\\');
            }, explode("\n", $content));

            // Validate headers
            $headers = array_map('strtolower', array_map('trim', array_filter($csvData[0])));

            $requiredColumns = [
                'amount_excl', 'amount_incl', 'invoice_date', 'invoice_number',
                'client_id', 'id', 'client_name', 'description', 'client_email', 'invoice_number'
            ];

            $missingColumns = array_diff($requiredColumns, $headers);


            if (!empty($missingColumns)) {
                return back()->with('error', 'Missing required columns: ' . implode(', ', $missingColumns));
            }

            // Remove header row and empty rows
            array_shift($csvData);



            $csvData = array_filter($csvData, function ($row) {
                return count(array_filter($row, 'strlen')) > 0;
            });

            $importResults = [
                'success' => 0,
                'failed' => 0,
                'errors' => [],
                'updated' => 0,
                'created' => 0
            ];

            DB::beginTransaction();

            // Process in chunks to handle large files
            foreach (array_chunk($csvData, 100) as $chunk) {

                foreach ($chunk as $index => $row) {
                    try {
                        if (count($row) !== count($headers)) {
                            throw new \Exception("Invalid number of columns in row " . ($index + 2));
                        }

                        $data = array_combine($headers, array_map('trim', $row));

                        // Validate data
                        $this->validateImportRow($data, $index);

                        // Format and sanitize data
                        $data = $this->sanitizeImportData($data);

                        // Create or update client.... only if client_ids are identical
                        $client = \App\Models\Client::where("companyname", '=', $data['client_name'])->first();

                        //dd("Got it", $client);
                        // Create or update invoice
                        $invoice = array(
                            ['id' => $data['id'],

                                'invoice_date' => $data['invoice_date'],
                                'amount_incl' => $data['amount_incl'],
                                'amount_excl' => $data['amount_excl'],
                                'tax_amount' => $data['amount_incl'] - $data['amount_excl'],
                                'description' => $data['description'],
                                'client_name' => $data['client_name'],
                                'client_email' => $data['client_email'],
                                'client_id' => $data['client_id'],
                                'invoice_number' => $data['invoice_number'],
                            ]
                        );
                        //dd($invoice);
                        $invoice = ArchivedInvoice::create(
                            ['id' => $data['id'],

                                'invoice_date' => $data['invoice_date'],
                                'amount_incl' => $data['amount_incl'],
                                'amount_excl' => $data['amount_excl'],
                                'tax_amount' => $data['amount_incl'] - $data['amount_excl'],
                                'description' => $data['description'],
                                'client_name' => $data['client_name'],
                                'client_email' => $data['client_email'],
                                'client_id' => $data['client_id'],
                                'invoice_number' => $data['invoice_number'],
                            ]
                        );
                        //dd("Got it", $invoice);
                        $importResults[isset($invoice->wasRecentlyCreated) ? 'created' : 'updated']++;
                        $importResults['success']++;

                    } catch (\Exception $e) {
                        $importResults['failed']++;
                        $importResults['errors'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
dd($importResults);
                        // If we have too many errors, abort
                        if (count($importResults['errors']) > 50) {
                            throw new \Exception('Too many errors encountered during import.');
                        }
                    }
                }
            }

            DB::commit();

            session()->flash('importResults', $importResults);

            if ($importResults['failed'] > 0) {
                return redirect()->route('archived-invoices.index')
                    ->with('warning', "Import completed with {$importResults['failed']} errors. " .
                        "Successfully processed: {$importResults['success']} records " .
                        "(Created: {$importResults['created']}, Updated: {$importResults['updated']})");
            }

            return redirect()->route('archived-invoices.index')
                ->with('success', "Successfully imported {$importResults['success']} records " .
                    "(Created: {$importResults['created']}, Updated: {$importResults['updated']})");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error importing CSV file: ' . $e->getMessage());
        }
    }

    private function detectAndConvertEncoding($content)
    {
        $encoding = mb_detect_encoding($content, ['UTF-8', 'UTF-16', 'ISO-8859-1'], true);
        return mb_convert_encoding($content, 'UTF-8', $encoding ?: 'UTF-8');
    }

    private function validateImportRow($data, $index)
    {
        $rowNumber = $index + 2; // Adding 2 to account for 1-based index and header row

        // Validate required fields
        foreach (['amount_excl', 'amount_incl', 'invoice_date', 'client_id', 'id'] as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Field '{$field}' cannot be empty");
            }
        }

        // Validate numeric fields
        foreach (['amount_excl', 'amount_incl'] as $field) {
            if (!is_numeric($data[$field])) {
                throw new \Exception("Field '{$field}' must be a number");
            }
            if ($data[$field] < 0) {
                throw new \Exception("Field '{$field}' cannot be negative");
            }
        }

        // Validate amounts
        if ($data['amount_excl'] > $data['amount_incl']) {
            throw new \Exception("Amount excluding tax cannot be greater than amount including tax");
        }

        // Validate date
        $date = date_create_from_format('Y-m-d', $data['invoice_date']);
        if (!$date) {
            throw new \Exception("Invalid date format for invoice_date. Use YYYY-MM-DD format");
        }

        // Validate email
        if (!filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format for client_email");
        }

        // Validate IDs
        if (!is_numeric($data['id']) || !is_numeric($data['client_id'])) {
            throw new \Exception("ID fields must be numeric");
        }
    }

    private function sanitizeImportData($data)
    {
        return [
            'id' => (int)$data['id'],
            'client_id' => (int)$data['client_id'],
            'invoice_date' => date('Y-m-d', strtotime($data['invoice_date'])),
            'amount_excl' => (float)str_replace(['$', ','], '', $data['amount_excl']),
            'amount_incl' => (float)str_replace(['$', ','], '', $data['amount_incl']),
            'description' => trim($data['description']),
            'client_name' => trim($data['client_name']),
            'client_email' => strtolower(trim($data['client_email']))
        ];
    }

    public function edit(ArchivedInvoice $archivedInvoice)
    {
        $clients = \App\Models\Client::all();
        return view('archived-invoices.edit', compact('archivedInvoice', 'clients'));
    }

    public function update(Request $request, ArchivedInvoice $archivedInvoice)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'amount_incl' => 'required|numeric',
            'amount_excl' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id'
        ]);

        $archivedInvoice->update($validated);

        return redirect()->route('archived-invoices.index')
            ->with('success', 'Invoice updated successfully');
    }

    public function show(ArchivedInvoice $archivedInvoice)
    {
        return view('archived-invoices.show', compact('archivedInvoice'));
    }

    public function destroy(ArchivedInvoice $archivedInvoice)
    {
        $archivedInvoice->delete();
        return redirect()->route('archived-invoices.index')
            ->with('success', 'Invoice deleted successfully');
    }
}
