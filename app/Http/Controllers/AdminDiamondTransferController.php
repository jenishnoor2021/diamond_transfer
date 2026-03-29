<?php

namespace App\Http\Controllers;

use App\Models\BrokerMemoItem;
use App\Models\Diamond;
use App\Models\DiamondTransfer;
use App\Models\Location;
use Illuminate\Http\Request;

class AdminDiamondTransferController extends Controller
{
    public function index($type)
    {
        $surat = Location::where('name', 'Surat')->first()->id;
        $mumbai = Location::where('name', 'Mumbai')->first()->id;

        if ($type == 'surat_to_mumbai') {
            $from = $surat; // surat id
            $to = $mumbai; // mumbai id
        } else {
            $from = $mumbai;
            $to = $surat;
        }

        $diamonds = Diamond::where('location_id', $from)->get();

        $locations = Location::all();

        return view('admin.diamond.transfer', compact(
            'diamonds',
            'locations',
            'from',
            'to'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'diamond_ids' => 'required',
            'from_location' => 'required',
            'to_location' => 'required'
        ]);

        foreach ($request->diamond_ids as $diamond_id) {

            $diamond = Diamond::find($diamond_id);

            // Prevent transfer if with broker
            if ($diamond->status == 'with_broker') {
                return back()->with(
                    'error',
                    'Diamond ' . $diamond->barcode_number . ' is currently with broker'
                );
            }

            // Prevent transfer if sold
            if ($diamond->status == 'sold') {
                return back()->with(
                    'error',
                    'Diamond ' . $diamond->barcode_number . ' is already sold'
                );
            }

            $issued = BrokerMemoItem::where('diamond_id', $diamond_id)
                ->where('status', 'issued')
                ->exists();

            if ($issued) {
                return back()->with('error', 'Diamond is currently issued to broker');
            }

            DiamondTransfer::create([
                'diamond_id' => $diamond_id,
                'from_location' => $request->from_location,
                'to_location' => $request->to_location,
                'transfer_date' => now()
            ]);

            $diamond->update([
                'location_id' => $request->to_location
            ]);
        }

        return back()->with('success', 'Diamonds transferred successfully');
    }


    public function destroy($id)
    {
        DiamondTransfer::find($id)->delete();

        return back()->with('success', 'Transfer deleted');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $from = $request->from_location;

        $diamond = Diamond::where(function ($q) use ($keyword) {
            $q->where('barcode_number', $keyword)
                ->orWhere('stock_no', $keyword);
        })->first();

        if (!$diamond) {
            return response()->json([
                'error' => 'Diamond not found'
            ]);
        }

        // location check
        if ($diamond->location_id != $from) {
            return response()->json([
                'error' => 'Diamond belongs to another location'
            ]);
        }

        // sold check
        if ($diamond->status == 'sold') {
            return response()->json([
                'error' => 'Diamond already sold'
            ]);
        }

        // broker check
        if ($diamond->status == 'with_broker') {
            return response()->json([
                'error' => 'Diamond is currently with broker'
            ]);
        }

        // issued memo check
        $issued = BrokerMemoItem::where('diamond_id', $diamond->id)
            ->where('status', 'issued')
            ->exists();

        if ($issued) {
            return response()->json([
                'error' => 'Diamond already issued in broker memo'
            ]);
        }

        return response()->json([
            'diamond' => $diamond->load('location')
        ]);
    }
}
