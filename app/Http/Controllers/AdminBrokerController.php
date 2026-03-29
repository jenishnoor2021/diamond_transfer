<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\Location;
use Illuminate\Http\Request;

class AdminBrokerController extends Controller
{
    public function index()
    {
        if (session('user')['role'] == 'Admin') {

            $brokers = Broker::with('location')
                ->latest()
                ->get();
        } else {

            $locationId = auth()->user()->location_id;

            $brokers = Broker::with('location')
                ->where('location_id', $locationId)
                ->latest()
                ->get();
        }

        return view('admin.broker.index', compact('brokers'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('admin.broker.add', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location_id' => 'required'
        ]);

        Broker::create($request->all());

        return redirect()
            ->route('admin.broker.index')
            ->with('success', 'Broker added successfully');
    }

    public function edit($id)
    {
        $broker = Broker::findOrFail($id);
        $locations = Location::all();

        return view('admin.broker.edit', compact(
            'broker',
            'locations'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $broker = Broker::findOrFail($id);
        $broker->update($request->all());

        return redirect()
            ->route('admin.broker.index')
            ->with('success', 'Broker updated successfully');
    }

    public function destroy($id)
    {
        Broker::findOrFail($id)->delete();
        return back()->with('success', 'Broker deleted');
    }
}
