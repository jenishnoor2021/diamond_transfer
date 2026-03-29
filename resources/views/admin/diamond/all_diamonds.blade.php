@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">All Diamonds List</h4>

    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <form method="GET">

          <div class="row mb-3">

            <div class="col-md-3">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="">All</option>
                <option value="in_stock" {{ request('status')=='in_stock'?'selected':'' }}>In Stock</option>
                <option value="with_broker" {{ request('status')=='with_broker'?'selected':'' }}>Broker</option>
                <option value="sold" {{ request('status')=='sold'?'selected':'' }}>Sold</option>
              </select>
            </div>

            <div class="col-md-3">
              <label>Location</label>
              <select name="location_id" class="form-control">
                <option value="">All</option>
                @foreach($locations as $location)
                <option value="{{ $location->id }}"
                  {{ request('location_id')==$location->id?'selected':'' }}>
                  {{ $location->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3">
              <label>Broker</label>
              <select name="broker_id" class="form-control">
                <option value="">All</option>
                @foreach($brokers as $broker)
                <option value="{{ $broker->id }}"
                  {{ request('broker_id')==$broker->id?'selected':'' }}>
                  {{ $broker->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
              <button class="btn btn-primary me-2">Filter</button>
              <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </div>

          </div>

        </form>

        <form method="POST" action="{{ route('admin.diamond.bulk.print') }}" target="_blank">
          @csrf

          <button class="btn btn-success mb-3">Print Selected</button>

          <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
            <thead>
              <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>Action</th>
                <th>Name</th>
                <th>Barcode</th>
                <th>Location</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($diamonds as $diamond)
              <tr>
                <td>
                  <input type="checkbox" name="diamond_ids[]" value="{{ $diamond->id }}" class="diamondCheck">
                </td>
                <td>
                  <a href="javascript:void(0)"
                    class="btn btn-outline-primary viewDiamond"
                    data-id="{{ $diamond->id }}">
                    <i class="fa fa-eye"></i>
                  </a>

                  <a href="/admin/print-image/{{ $diamond->id }}" target="_blank" class="btn btn-outline-secondary">Print</a>
                </td>
                <td>{{ $diamond->stock_no }}</td>
                <td>{{ $diamond->barcode_number ?? '-' }}</td>
                <td>{{ $diamond->location->name ?? '-' }}</td>
                <td>
                  @if($diamond->status == 'in_stock')
                  <span class="badge bg-warning">In Stock</span>
                  @elseif($diamond->status == 'with_broker')
                  <span class="badge bg-info">Broker</span>
                  @elseif($diamond->status == 'sold')
                  <span class="badge bg-success">Sold</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </form>

      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="diamondModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Diamond Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="diamondDetailBody">
        Loading...
      </div>

    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  $(document).on('click', '.viewDiamond', function() {

    let id = $(this).data('id');

    $('#diamondModal').modal('show');
    $('#diamondDetailBody').html('Loading...');

    $.ajax({
      url: "/admin/diamond/detail/" + id,
      type: "GET",
      success: function(res) {
        $('#diamondDetailBody').html(res.html);
      },
      error: function() {
        $('#diamondDetailBody').html('<div class="text-danger">Failed to load data</div>');
      }
    });

  });

  $('#checkAll').click(function() {
    $('.diamondCheck').prop('checked', $(this).prop('checked'));
  });
</script>
@endsection