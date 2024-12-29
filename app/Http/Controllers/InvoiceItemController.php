<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function index(Request $request)
    {
        //
        $clientsearch = '';
        if ($request->has('invoiceitemsearch')) {
            //$posts = Post::search($request->get('search'))->get();
            //$clients = Client::search($request->get('clientsearch'))->paginate(20);
            $invoices = InvoiceItem::sortable()->where('description', 'LIKE', $request->clientsearch)->orderBy('description', 'desc')->paginate(20);
//            /$clients = Client::search(['roepnaam', 'achternaam'], $request->clientsearch)->paginate(20);
            $invoicesearch = $request->invoicesearch;
            //$assets = Asset::orderBy('id', 'desc')->paginate(20);
        } else {
            //$assets = Client::with('assetlogs')->orderBy('clients.id', 'desc')->paginate(20);
            // AssetLog::with('assetlogs.asset_id')->where('asset_id', $asset->id)->latest()->get();
            $invoices = InvoiceItem::sortable()->with('invoice')->orderBy('description', 'asc')->paginate(10);
//            /dd($invoices);
            $invoicesearch = '';
        }


        return view('invoices.index', compact('invoices', 'invoicesearch'));
    }

    public function create(Request $request)
    {
        //

        $invoiceitem = Invoice::max('invoicenumber');

        $newinvoicenumber = $invoice + 1;
        return view('invoices.create', compact('clients', 'newinvoicenumber'));
    }

    public function edit(Request $request)
    {
        dd($request);
        $clientid = $request->client_id;
        $newinvoicenumber = $request->invoicenumber;
        return view('invoices.edit', compact('clientid', 'newinvoicenumber'));
    }
}
