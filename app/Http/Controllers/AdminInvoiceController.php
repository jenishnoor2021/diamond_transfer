<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Diamond;
use App\Models\Invoice;
use App\Models\Invoicedata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::get();
        return view('admin.invoice.index', compact('invoices'));
    }

    public function add(Request $request)
    {
        $company = Company::first();
        $invoice = Invoice::latest()->first();

        $invoicePrefix = $company->invoice_prefix ?? 'INV';

        if ($invoice) {
            $seriseNo = $invoice->serise_no + 1;
        } else {
            $seriseNo = $company->invoice_series ?? 1;
        }

        $invoiceNo = $invoicePrefix . '-' . str_pad($seriseNo, 5, '0', STR_PAD_LEFT);

        return view('admin.invoice.add', compact('company', 'seriseNo', 'invoiceNo'));
    }

    public function addDiamond(Request $request)
    {
        $barcode = trim($request->barcode);
        $diamond = Diamond::with('sale')
            ->where('barcode_number', $barcode)
            ->first();

        if (!$diamond) {
            return response()->json(['error' => 'Diamond not found']);
        }

        if (!$diamond->sale) {
            return response()->json(['error' => 'Diamond not sold yet']);
        }

        $exists = Invoicedata::where('diamond_id', $diamond->id)->exists();

        if ($exists) {
            return response()->json(['error' => 'Diamond already used in invoice']);
        }

        // Save item
        $item = Invoicedata::create([
            'invoice_id' => $request->invoice_id,
            'diamond_id' => $diamond->id,
            'weight' => $diamond->weight,
            'rate' => $diamond->sale->price / $diamond->weight,
            'amount' => $diamond->sale->price,
        ]);

        $totals = $this->updateInvoiceTotals($request->invoice_id);

        $row = view('admin.invoice.partials.row', compact('item'))->render();

        return response()->json([
            'success' => true,
            'row_html' => $row,
            'sub_total' => $totals['sub_total'],
            'tax' => $totals['tax'],
            'grand_total' => $totals['grand_total']
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'invoice_no' => $request->invoice_no,
                'serise_no' => $request->serise_no,
                'invoice_date' => $request->invoice_date,
                'client_state' => $request->client_state,
                'client_name' => $request->client_name,
                'client_address' => $request->client_address,
                'client_mobile' => $request->client_mobile,
                'client_gst_no' => $request->client_gst_no,
                'client_gst_name' => $request->client_gst_name ?? null,
                'sub_total' => 0,
                'tax' => 0,
                'grand_total' => 0,
            ]);

            DB::commit();

            // Redirect to edit page
            return redirect()->route('admin.invoice.edit', $invoice->id)
                ->with('success', 'Client details saved, now add diamonds');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    // public function getSaleDiamond(Request $request)
    // {
    //     $diamond = Diamond::with('sale')
    //         ->where('barcode_number', $request->barcode)
    //         ->first();

    //     if (!$diamond) {
    //         return response()->json(['error' => 'Diamond not found']);
    //     }

    //     if (!$diamond->sale) {
    //         return response()->json(['error' => 'Diamond not sold yet']);
    //     }

    //     // Already invoiced check
    //     $exists = Invoicedata::where('diamond_id', $diamond->id)->exists();

    //     if ($exists) {
    //         return response()->json(['error' => 'Diamond already used in invoice']);
    //     }

    //     return response()->json([
    //         'id' => $diamond->id,
    //         'weight' => $diamond->weight,
    //         'stock_no' => $diamond->stock_no,
    //         'rate' => $diamond->sale->price / $diamond->weight,
    //         'price' => $diamond->sale->price
    //     ]);
    // }

    public function preview($id)
    {
        $invoice = Invoice::with('items.diamond')->findOrFail($id);
        $company = Company::first();

        $pdf = Pdf::loadView('admin.invoice.pdf', compact('invoice', 'company'));

        return $pdf->stream('invoice.pdf'); // preview open karega
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $company = Company::first();

        return view('admin.invoice.edit', compact('invoice', 'company'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $request->validate([
            'client_name' => 'required',
            'client_mobile' => 'required|digits:10',
            'client_address' => 'required',
            'client_state' => 'required',
            'invoice_date' => 'required|date',
        ]);

        try {
            $invoice = Invoice::findOrFail($id);

            // Update client info भी कर सकते हो
            $invoice->update([
                'client_name' => $request->client_name,
                'client_mobile' => $request->client_mobile,
                'client_address' => $request->client_address,
                'client_state' => $request->client_state,
                'sub_total' => $request->sub_total,
                'tax' => $request->tax,
                'grand_total' => $request->grand_total,
            ]);

            // पहले delete old items (optional)
            Invoicedata::where('invoice_id', $invoice->id)->delete();

            foreach ($request->diamond_id as $key => $diamondId) {
                Invoicedata::create([
                    'invoice_id' => $invoice->id,
                    'diamond_id' => $diamondId,
                    'weight' => $request->weight[$key],
                    'rate' => $request->rate[$key],
                    'gst' => $request->gst[$key] ?? 0,
                    'amount' => $request->weight[$key] * $request->rate[$key],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Invoice Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function updateClient(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'client_name' => $request->client_name,
            'client_mobile' => $request->client_mobile,
            'client_address' => $request->client_address,
            'client_state' => $request->client_state,
            'client_gst_no' => $request->client_gst_no,
            'client_gst_name' => $request->client_gst_name ?? null,
        ]);

        return back()->with('success', 'Client updated successfully');
    }

    public function deleteItem(Request $request)
    {
        $item = Invoicedata::find($request->id);

        if (!$item) {
            return response()->json(['error' => 'Item not found']);
        }

        $invoiceId = $item->invoice_id;

        $item->delete();

        $totals = $this->updateInvoiceTotals($invoiceId);

        return response()->json([
            'success' => true,
            'sub_total' => $totals['sub_total'],
            'tax' => $totals['tax'],
            'grand_total' => $totals['grand_total']
        ]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Delete all items first
        Invoicedata::where('invoice_id', $id)->delete();

        // Then delete invoice
        $invoice->delete();

        return redirect()->route('admin.invoice.index')
            ->with('success', 'Invoice deleted successfully');
    }

    // public function destroy($id)
    // {
    //     $invoice = Invoice::findOrFail($id);

    //     $invoice->items()->delete(); // auto delete items
    //     $invoice->delete();

    //     return back()->with('success', 'Deleted');
    // }


    private function updateInvoiceTotals($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $items = Invoicedata::where('invoice_id', $invoiceId)->get();

        $subTotal = 0;
        $totalTax = 0;

        $company = Company::first();

        $cgstRate = $company->cgst;
        $sgstRate = $company->sgst;
        $igstRate = $company->igst;

        foreach ($items as $it) {
            $amount = $it->amount;
            $subTotal += $amount;

            if ($invoice->client_state === 'gujarat') {
                $totalTax += ($amount * $cgstRate / 100) + ($amount * $sgstRate / 100);
            } else {
                $totalTax += ($amount * $igstRate / 100);
            }
        }

        $grandTotal = $subTotal + $totalTax;

        $invoice->update([
            'sub_total' => $subTotal,
            'tax' => $totalTax,
            'grand_total' => $grandTotal,
        ]);

        return [
            'sub_total' => $subTotal,
            'tax' => $totalTax,
            'grand_total' => $grandTotal
        ];
    }
}
