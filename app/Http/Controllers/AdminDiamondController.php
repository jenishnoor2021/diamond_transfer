<?php

namespace App\Http\Controllers;

use App\Imports\DiamondImport;
use App\Models\Broker;
use App\Models\Clarity;
use App\Models\Color;
use App\Models\Cut;
use App\Models\Diamond;
use App\Models\DiamondTransfer;
use App\Models\Location;
use App\Models\Polish;
use App\Models\Shape;
use App\Models\Symmetry;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class AdminDiamondController extends Controller
{
    public function index()
    {
        $diamonds = Diamond::where('status', 'in_stock')->latest()->get();
        return view('admin.diamond.index', compact('diamonds'));
    }

    public function importPage()
    {
        return view('admin.diamond.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx'
        ]);

        Excel::import(new DiamondImport, $request->file('file'));

        return back()->with('success', 'Diamonds Imported Successfully');
    }

    public function create()
    {
        $shapes = Shape::get();
        $colors = Color::get();
        $clarity = Clarity::get();
        $polish = Polish::get();
        $symmetry = Symmetry::get();
        $cut = Cut::get();

        return view('admin.diamond.add', compact('shapes', 'colors', 'clarity', 'polish', 'symmetry', 'cut'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'barcode_number' => 'required|unique:diamonds',
            'stock_no' => 'required',
            'weight' => 'nullable|numeric',
            'price_per_carat' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        do {
            $number = mt_rand(1000000000, 9999999999);
        } while (Diamond::where('barcode_number', $number)->exists());

        $locationId = Location::where('name', 'Surat')->value('id');

        Diamond::create([
            'barcode_number' => $number,
            'stock_no' => $request->stock_no,
            'certificate_no' => $request->certificate_no,
            'availability' => $request->availability,
            'shape' => $request->shape,
            'weight' => $request->weight,
            'color' => $request->color,
            'clarity' => $request->clarity,
            'cut_grade' => $request->cut_grade,
            'polish' => $request->polish,
            'symmetry' => $request->symmetry,
            'fluorescence_intensity' => $request->fluorescence_intensity,
            'measurements' => $request->measurements,
            'depth_percent' => $request->depth_percent,
            'table_percent' => $request->table_percent,
            'lab' => $request->lab,
            'price_per_carat' => $request->price_per_carat,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'image_url' => $request->image_url,
            'video_url' => $request->video_url,
            'growth_type' => $request->growth_type,
            'location_id' => $locationId,
            'status' => 'in_stock',
        ]);

        return redirect()->route('admin.diamonds.index')
            ->with('success', 'Diamond Added Successfully');
    }

    public function edit($id)
    {
        $diamond = Diamond::findOrFail($id);

        $shapes = Shape::get();
        $colors = Color::get();
        $clarity = Clarity::get();
        $polish = Polish::get();
        $symmetry = Symmetry::get();
        $cut = Cut::get();

        return view('admin.diamond.edit', compact(
            'diamond',
            'shapes',
            'colors',
            'clarity',
            'polish',
            'symmetry',
            'cut'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock_no' => 'required',
            'weight' => 'required',
            'price_per_carat' => 'required'
        ]);

        $diamond = Diamond::findOrFail($id);

        $diamond->update([
            'stock_no' => $request->stock_no,
            'certificate_no' => $request->certificate_no,
            'weight' => $request->weight,
            'shape' => $request->shape,
            'color' => $request->color,
            'clarity' => $request->clarity,
            'cut_grade' => $request->cut_grade,
            'polish' => $request->polish,
            'symmetry' => $request->symmetry,
            'price_per_carat' => $request->price_per_carat,
            'lab' => $request->lab,
            'availability' => $request->availability,
            'fancy_color' => $request->fancy_color,
            'fancy_color_intensity' => $request->fancy_color_intensity,
            'fancy_color_overtone' => $request->fancy_color_overtone,
            'fluorescence_intensity' => $request->fluorescence_intensity,
            'measurements' => $request->measurements,
            'depth_percent' => $request->depth_percent,
            'table_percent' => $request->table_percent,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'image_url' => $request->image_url,
            'video_url' => $request->video_url,
            'growth_type' => $request->growth_type,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.diamonds.index')
            ->with('success', 'Diamond updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        Diamond::findOrFail($id)->delete();

        return redirect()->route('admin.diamonds.index')->with('success', 'Diamond deleted successfully');
    }

    public function allDiamonds(Request $request)
    {
        $query = Diamond::query();

        // USER ROLE RESTRICTION
        if (session('user')['role'] != 'Admin') {

            $locationId = auth()->user()->location_id;

            // get transferred diamond ids
            $diamondIds = DiamondTransfer::where('to_location', $locationId)
                ->pluck('diamond_id');

            $query->whereIn('id', $diamondIds);
        }

        // status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // location filter
        if ($request->location_id) {
            $query->where('location_id', $request->location_id);
        }

        // broker filter
        if ($request->broker_id) {
            $query->whereHas('brokerMemoItems.memo', function ($q) use ($request) {
                $q->where('broker_id', $request->broker_id);
            });
        }

        $diamonds = $query->orderBy('id', 'DESC')->get();

        $locations = Location::all();
        $brokers = Broker::all();

        return view('admin.diamond.all_diamonds', compact(
            'diamonds',
            'locations',
            'brokers'
        ));
    }

    public function diamondDetail($id)
    {
        $diamond = Diamond::with('location')->find($id);

        if (!$diamond) {
            return response()->json([
                'html' => '<div class="text-danger">Diamond not found</div>'
            ]);
        }

        $html = view('admin.diamond.partials.diamond_detail', compact('diamond'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function printImage($id)
    {
        $dimond = Diamond::where('id', $id)->first();

        return view('admin.diamond.print', compact('dimond'));
    }

    public function bulkPrint(Request $request)
    {
        $diamonds = Diamond::whereIn('id', $request->diamond_ids)->get();

        return view('admin.diamond.bulk_print', compact('diamonds'));
    }
}
