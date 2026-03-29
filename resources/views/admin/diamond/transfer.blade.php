@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex justify-content-between">
      <h4 class="mb-0">Transfer</h4>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <form method="POST" id="transferForm" action="{{ route('admin.diamond.transfer.store') }}">
          @csrf

          <div class="row mb-3 align-items-end">

            <div class="col-md-4">
              <label>From Location</label>
              <select name="from_location" class="form-control" disabled>

                @foreach($locations as $location)

                <option value="{{$location->id}}"
                  @if($location->id == $from) selected @endif>

                  {{$location->name}}

                </option>

                @endforeach

              </select>

              <!-- hidden field to submit value -->
              <input type="hidden" name="from_location" value="{{$from}}">
            </div>

            <div class="col-md-4">
              <label>To Location</label>
              <select name="to_location" class="form-control" disabled>

                @foreach($locations as $location)

                <option value="{{$location->id}}"
                  @if($location->id == $to) selected @endif>

                  {{$location->name}}

                </option>

                @endforeach

              </select>

              <input type="hidden" name="to_location" value="{{$to}}">
            </div>

            <div class="col-md-4">
              <button class="btn btn-primary w-100">
                Transfer Selected Diamonds
              </button>
            </div>
          </div>

          <div class="row mb-3">

            <div class="col-md-4">
              <label>Search Barcode / Name</label>
              <input type="text" id="searchDiamond" class="form-control" placeholder="Enter barcode or name">
            </div>

          </div>

          <table class="table table-bordered">

            <thead>
              <tr>
                <th>Select</th>
                <th>Barcode</th>
                <th>Name</th>
                <th>Weight</th>
                <th>Color</th>
                <th>Clarity</th>
                <th>Location</th>
                <th>Status</th>
                <th>Remove</th>
              </tr>
            </thead>

            <tbody id="diamondTable"></tbody>

          </table>

        </form>

      </div>
    </div>

  </div>
</div>

@endsection

@section('script')

<script>
  $('#searchDiamond').on('keypress', function(e) {
    if (e.which == 13) { // Enter key
      e.preventDefault();
      searchDiamond();
    }
  });

  $(document).ready(function() {
    $('#searchDiamond').focus();
  });

  function searchDiamond() {

    let keyword = $('#searchDiamond').val();

    if (keyword.length < 2) {
      return;
    }

    $.get('/admin/diamond-transfer-search', {
      keyword: keyword,
      from_location: '{{ $from }}'
    }, function(res) {

      if (res.error) {
        $('#searchDiamond').val('');
        alert(res.error);
        return;
      }

      let diamond = res.diamond;

      // prevent duplicate
      if ($('#row_' + diamond.id).length) {
        alert('Diamond already added');
        $('#searchDiamond').val('');
        return;
      }

      let statusBadge = '';

      if (diamond.status === 'with_broker') {
        statusBadge = '<span class="badge bg-warning">Broker</span>';
      } else if (diamond.status === 'sold') {
        statusBadge = '<span class="badge bg-danger">Sold</span>';
      } else {
        statusBadge = '<span class="badge bg-success">In Stock</span>';
      }

      let html = `
    <tr id="row_${diamond.id}">
        <td>
          <input type="checkbox" name="diamond_ids[]" value="${diamond.id}" checked>
        </td>
        <td>${diamond.barcode_number}</td>
        <td>${diamond.stock_no}</td>
        <td>${diamond.weight}</td>
        <td>${diamond.color ?? ''}</td>
        <td>${diamond.clarity ?? ''}</td>
        <td>${diamond.location?.name ?? '-'}</td>
        <td>${statusBadge}</td>
        <td>
          <button type="button" class="btn btn-danger btn-sm removeDiamond" data-id="${diamond.id}">
            Remove
          </button>
        </td>
    </tr>
    `;

      $('#diamondTable').append(html);

      $('#searchDiamond').val('').focus();

    });

  }

  $(document).on('click', '.removeDiamond', function() {

    let id = $(this).data('id');

    $('#row_' + id).remove();

  });
</script>

@endsection