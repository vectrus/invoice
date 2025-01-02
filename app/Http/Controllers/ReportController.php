<?php


namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.income');
    }

    public function generateReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $invoices = Invoice::whereBetween('issue_date', [$start_date, $end_date])
            ->with('items')
            ->get();

        $total_income = $invoices->sum('amount_incl');
        $total_tax = $invoices->sum('tax_amount');
        $total_subtotal = $invoices->sum('amount_excl');

        return view('reports.income-report', compact('invoices', 'total_income', 'total_tax', 'total_subtotal'));
    }

    public function generatePdf(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $invoices = Invoice::whereBetween('issue_date', [$start_date, $end_date])
            ->with('items')
            ->get();
        //dd($invoices);
        $total_income = $invoices->sum('amount_incl');
        $total_tax = $invoices->sum('tax_amount');
        $total_subtotal = $invoices->sum('amount_excl');
        $logo = env('APP_URL').'/'.config('settings.logo');

        $pdf = PDF::loadView('reports.income-pdf', compact('invoices', 'total_income', 'total_tax', 'total_subtotal', 'start_date', 'end_date', 'logo'));
        return $pdf->download('income-report.pdf');
    }
}
