<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\ClientEmail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats(): JsonResponse
    {
        // Create separate Carbon instances for start and end dates
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Get this month's invoice stats
        $monthlyStats = Invoice::whereBetween('issue_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('
            COUNT(*) as count,
            COALESCE(SUM(amount_incl), 0) as totalAmount,
            SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as pending
        ')
            ->first();

        // Debug log
        \Log::info('Monthly Stats:', [
            'start_date' => $startOfMonth->toDateTimeString(),
            'end_date' => $endOfMonth->toDateTimeString(),
            'stats' => $monthlyStats
        ]);

        // Rest of your code remains the same...
        $recentInvoices = Invoice::with('client:id,companyname')
            ->orderBy('issue_date', 'desc')
            ->take(5)
            ->get([
                'id',
                'invoice_number',
                'client_id',
                'amount_incl',
                'issue_date',
                'status',
                'due_date'
            ]);

        $recentEmails = ClientEmail::with('client:id,companyname')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentClients = Client::withCount('invoices as total_invoices')
            ->withSum('invoices as total_amount', 'amount_incl')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get([
                'id',
                'companyname',
                'created_at'
            ]);

        $monthlyInvoiceAmounts = Invoice::select(
            DB::raw('DATE_FORMAT(issue_date, "%Y-%m") as month'),
            DB::raw('COALESCE(SUM(amount_incl), 0) as total_amount')
        )
            ->where('issue_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $response = [
            'thisMonth' => [
                'totalAmount' => (float)($monthlyStats->totalAmount ?? 0),
                'count' => (int)($monthlyStats->count ?? 0),
                'pending' => (int)($monthlyStats->pending ?? 0),
                'paid' => (int)($monthlyStats->paid ?? 0)
            ],
            'recentInvoices' => $recentInvoices,
            'recentEmails' => $recentEmails,
            'recentClients' => $recentClients,
            'monthlyAmounts' => $monthlyInvoiceAmounts
        ];

        // Debug log
        \Log::info('Response data:', $response);

        return response()->json($response);
    }
}
