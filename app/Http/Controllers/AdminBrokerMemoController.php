<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\BrokerMemo;
use App\Models\BrokerMemoItem;
use App\Models\Company;
use App\Models\Diamond;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBrokerMemoController extends Controller
{
    public function index($location)
    {
        $surat = Location::where('name', 'Surat')->first()->id;
        $mumbai = Location::where('name', 'Mumbai')->first()->id;

        $location_id = (strtolower($location) == 'surat') ? $surat : $mumbai;

        $memos = BrokerMemo::where('location_id', $location_id)
            ->latest()
            ->get();

        return view('admin.broker_memo.index', compact('memos', 'location'));
    }

    public function create($location)
    {
        $surat = Location::where('name', 'Surat')->first()->id;
        $mumbai = Location::where('name', 'Mumbai')->first()->id;

        $location_id = ($location == 'surat') ? $surat : $mumbai;

        $brokers = Broker::where('location_id', $location_id)->get();

        return view('admin.broker_memo.create', compact('brokers', 'location_id', 'location'));
    }

    public function getDiamond(Request $request)
    {
        $diamond = Diamond::where('barcode_number', $request->barcode)
            ->first();

        if (!$diamond) {
            return response()->json(['error' => 'Diamond not found']);
        }

        // Check if diamond already issued in another memo
        $issued = BrokerMemoItem::where('diamond_id', $diamond->id)
            ->where('status', 'issued')
            ->exists();

        if ($issued) {
            return response()->json([
                'error' => 'Diamond already issued in another memo'
            ]);
        }

        $broker = Broker::find($request->broker_id);

        if ($diamond->location_id != $broker->location_id) {

            return response()->json([
                'error' => 'This diamond belongs to another location'
            ]);
        }

        return response()->json($diamond);
    }

    public function store(Request $request)
    {

        $request->validate([
            'broker_id' => 'required',
            'diamond_ids' => 'required|array'
        ]);

        $broker = Broker::findOrFail($request->broker_id);

        DB::beginTransaction();

        try {

            $memo = BrokerMemo::create([
                'broker_id' => $broker->id,
                'location_id' => $request->location_id,
                'memo_number' => 'MEMO-' . time(),
                'issue_date' => now()
            ]);

            foreach ($request->diamond_ids as $diamond_id) {

                $diamond = Diamond::find($diamond_id);

                if (!$diamond) {
                    continue;
                }

                $issued = BrokerMemoItem::where('diamond_id', $diamond->id)
                    ->where('status', 'issued')
                    ->exists();

                if ($issued) {
                    return response()->json([
                        'error' => 'Diamond already issued in another memo'
                    ]);
                }

                // LOCATION VALIDATION
                if ($diamond->location_id != $broker->location_id) {

                    DB::rollBack();

                    return back()->with(
                        'error',
                        'Diamond ' . $diamond->barcode_number . ' belongs to another location'
                    );
                }

                BrokerMemoItem::create([
                    'broker_memo_id' => $memo->id,
                    'diamond_id' => $diamond->id,
                    'status' => 'issued'
                ]);

                // OPTIONAL STATUS UPDATE
                $diamond->update([
                    'status' => 'with_broker'
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.broker.memo.show', $memo->id)
                ->with('success', 'Broker memo created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $memo = BrokerMemo::with('items.diamond', 'broker')
            ->findOrFail($id);

        return view('admin.broker_memo.show', compact('memo'));
    }

    public function printMemo($id)
    {
        $memo = BrokerMemo::with('items.diamond', 'broker')->findOrFail($id);
        $company = Company::first();
        return view('admin.broker_memo.print', compact('memo', 'company'));
    }

    public function returnPage($location)
    {

        $surat = Location::where('name', 'Surat')->first()->id;
        $mumbai = Location::where('name', 'Mumbai')->first()->id;

        if ($location == 'surat') {
            $location_id = $surat;
        } else {
            $location_id = $mumbai;
        }

        $brokers = Broker::where('location_id', $location_id)->get();

        return view('admin.broker_memo.return', compact(
            'brokers',
            'location_id',
            'location'
        ));
    }

    public function getBrokerMemos(Request $request)
    {
        $memos = BrokerMemo::where('broker_id', $request->broker_id)
            ->where('location_id', $request->location_id)
            ->latest()
            ->get();

        return response()->json($memos);
    }

    public function getMemoDiamonds(Request $request)
    {
        $diamonds = BrokerMemoItem::with('diamond')
            ->where('broker_memo_id', $request->memo_id)
            ->where('status', 'issued')
            ->get();

        return response()->json($diamonds);
    }

    public function returnDiamond(Request $request)
    {

        $item = BrokerMemoItem::find($request->item_id);

        $item->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);

        Diamond::where('id', $item->diamond_id)
            ->update([
                'status' => 'in_stock'
            ]);

        return response()->json([
            'success' => 'Diamond returned successfully'
        ]);
    }

    public function edit($id)
    {
        $memo = BrokerMemo::with('items.diamond', 'broker')->findOrFail($id);

        $brokers = Broker::where('location_id', $memo->location_id)->get();

        return view('admin.broker_memo.edit', compact(
            'memo',
            'brokers'
        ));
    }

    public function update(Request $request, $id)
    {
        $memo = BrokerMemo::findOrFail($id);

        DB::beginTransaction();

        try {

            if ($request->diamond_ids) {

                foreach ($request->diamond_ids as $diamond_id) {

                    $exists = BrokerMemoItem::where('broker_memo_id', $memo->id)
                        ->where('diamond_id', $diamond_id)
                        ->exists();

                    if (!$exists) {

                        BrokerMemoItem::create([
                            'broker_memo_id' => $memo->id,
                            'diamond_id' => $diamond_id,
                            'status' => 'issued'
                        ]);

                        Diamond::where('id', $diamond_id)
                            ->update(['status' => 'with_broker']);
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Memo updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function removeDiamond(Request $request)
    {

        $item = BrokerMemoItem::find($request->item_id);

        if (!$item) {
            return response()->json(['error' => 'Item not found']);
        }

        Diamond::where('id', $item->diamond_id)
            ->update([
                'status' => 'in_stock'
            ]);

        $item->delete();

        return response()->json([
            'success' => 'Diamond removed from memo'
        ]);
    }

    public function destroy($id)
    {
        $memo = BrokerMemo::with('items')->findOrFail($id);

        DB::beginTransaction();

        try {

            foreach ($memo->items as $item) {

                Diamond::where('id', $item->diamond_id)
                    ->update([
                        'status' => 'in_stock'
                    ]);
            }

            BrokerMemoItem::where('broker_memo_id', $memo->id)->delete();

            $memo->delete();

            DB::commit();

            return back()->with('success', 'Memo deleted successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function addDiamond(Request $request)
    {

        $memo = BrokerMemo::findOrFail($request->memo_id);

        $diamond = Diamond::where('barcode_number', $request->barcode)->first();

        if (!$diamond) {
            return response()->json([
                'error' => 'Diamond not found'
            ]);
        }

        // already issued check
        $issued = BrokerMemoItem::where('diamond_id', $diamond->id)
            ->where('status', 'issued')
            ->exists();

        if ($issued) {
            return response()->json([
                'error' => 'Diamond already issued'
            ]);
        }

        // location validation
        if ($diamond->location_id != $memo->location_id) {
            return response()->json([
                'error' => 'Diamond belongs to another location'
            ]);
        }

        BrokerMemoItem::create([
            'broker_memo_id' => $memo->id,
            'diamond_id' => $diamond->id,
            'status' => 'issued'
        ]);

        $diamond->update([
            'status' => 'with_broker'
        ]);

        return response()->json([
            'success' => 'Diamond added successfully'
        ]);
    }
}
