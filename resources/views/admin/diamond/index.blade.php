@extends('layouts.admin')
@section('style')
@endsection
@section('content')
<!-- start page title -->

<div class="row">
  <div class="col-12">

    <div class="page-title-box d-sm-flex justify-content-between">

      <div>
        <h4 class="mb-0">Diamonds</h4>
        <span class="text-danger" style="font-size:12px;">(Showing Only Today Imported or added Diamonds List)</span>
      </div>

      <div>
        <a href="{{ route('admin.diamond.create') }}" class="btn btn-sm btn-success">
          <i class="mdi mdi-plus"></i> Add Diamond
        </a>

        <a href="{{ route('diamond.import') }}" class="btn btn-sm btn-primary">
          <i class="mdi mdi-arrow-left-bold-circle-outline me-1"></i> Import
        </a>
      </div>
    </div>

  </div>
</div>

<div class="row">
  <div class="col-12">

    @include('includes.flash_message')

    @if(count($diamonds) > 0)
    <div class="card">
      <div class="card-body">

        <table id="datatable" class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>Action</th>
              <th>Name</th>
              <th>Weight</th>
              <th>Barcode</th>
            </tr>
          </thead>
          <tbody>
            @foreach($diamonds as $diamond)
            <tr>
              <td>
                <a href="{{ route('admin.diamond.edit', $diamond->id) }}" target="_blank" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                <a href="{{ route('admin.diamond.destroy', $diamond->id) }}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a>
              </td>
              <td>{{ $diamond->stock_no }}</td>
              <td>{{ number_format($diamond->weight, 2) }}</td>
              <td>{{ $diamond->barcode_number ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

  </div>
</div>
<!-- end row -->
@endsection