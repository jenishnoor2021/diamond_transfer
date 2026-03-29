<?php

namespace App\Http\Controllers;

use App\Models\BrokerMemoItem;
use App\Models\Diamond;
use App\Models\Location;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSaleDiamondsController extends Controller
{
    public function sellDiamond(Request $request)
    {

        DB::beginTransaction();

        try {

            $saleType = $request->broker_id ? 'broker' : 'direct';

            // BROKER SALE
            if ($saleType == 'broker') {

                $item = BrokerMemoItem::findOrFail($request->item_id);

                $diamondId = $item->diamond_id;

                $item->update([
                    'status' => 'sold',
                    'sold_at' => now()
                ]);
            }
            // DIRECT SALE
            else {

                $diamond = Diamond::findOrFail($request->item_id);

                $diamondId = $diamond->id;

                $diamond->update([
                    'status' => 'sold'
                ]);
            }

            Sale::create([
                'diamond_id' => $diamondId,
                'broker_id' => $request->broker_id ?? null,
                'price' => $request->price,
                'payment_status' => $request->payment_status,
                'payment_type' => $request->payment_type,
                'sold_date' => now(),
                'sold_by_user_id' => auth()->id(),
                'sale_type' => $saleType
            ]);

            Diamond::where('id', $diamondId)
                ->update([
                    'status' => 'sold'
                ]);

            DB::commit();

            return response()->json([
                'success' => 'Diamond sold successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function ownerSalePage($type)
    {
        $surat = Location::where('name', 'Surat')->first()->id;
        $mumbai = Location::where('name', 'Mumbai')->first()->id;

        if ($type == 'surat') {
            $from = $surat; // surat id
        } else {
            $from = $mumbai;
        }

        $diamonds = Diamond::where('location_id', $from)->get();

        return view('admin.sale.owner-sale', compact(
            'diamonds',
            'type'
        ));
    }

    public function sellDiamondsList(Request $request)
    {
        if (session('user')['role'] == 'Admin') {
            $sales = Sale::with('diamond')->orderBy('id', 'DESC')->get();
        } else {
            $locationId = auth()->user()->location_id;

            $sales = Sale::with('diamond')
                ->where(function ($q) use ($locationId) {

                    $q->where('sold_by_user_id', auth()->id())

                        ->orWhere(function ($sub) use ($locationId) {
                            $sub->where('sale_type', 'broker')
                                ->whereHas('diamond', function ($d) use ($locationId) {
                                    $d->where('location_id', $locationId);
                                });
                        });
                })
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('admin.sale.sell_diamonds', compact('sales'));
    }

    public function update(Request $request)
    {
        $sale = Sale::findOrFail($request->sale_id);

        $sale->update([
            'price' => $request->price,
            'payment_status' => $request->payment_status,
            'payment_type' => $request->payment_type,
            'sale_type' => $request->sale_type,
            'sold_date' => $request->sold_date,
        ]);

        return back()->with('success', 'Sale Updated Successfully');
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $sale = Sale::findOrFail($id);

            $diamondId = $sale->diamond_id;

            // update diamond status back to stock
            Diamond::where('id', $diamondId)
                ->update([
                    'status' => 'in_stock',
                    'location_id' => Location::where('name', 'Surat')->first()->id
                ]);

            // if broker sale then update broker memo item
            if ($sale->sale_type == 'broker') {

                BrokerMemoItem::where('diamond_id', $diamondId)
                    ->update([
                        'status' => 'returned',
                    ]);
            }

            // delete sale
            $sale->delete();

            DB::commit();

            return back()->with('success', 'Sale deleted successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
