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


          <div class="mb-2">
            <strong>Total Diamonds :</strong> {{ $diamonds->count() }}
          </div>

          <div class="mb-2">
            <strong>Selected Diamonds :</strong>
            <span id="selectedCount">0</span>
          </div>

          <table class="table table-bordered">

            <thead>
              <tr>
                <th>
                  <input type="checkbox" id="checkAll">
                </th>
                <th>Barcode</th>
                <th>id</th>
                <th>Name</th>
                <th>Weight</th>
                <th>Color</th>
                <th>Clarity</th>
                <th>Location</th>
                <th>Sale</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>

              @foreach($diamonds as $diamond)

              <tr>

                <td>
                  <input type="checkbox"
                    name="diamond_ids[]"
                    value="{{$diamond->id}}"
                    class="diamondCheckbox"
                    @if($diamond->status != 'in_stock') disabled @endif
                  >
                </td>

                <td>{{$diamond->barcode_number}}</td>
                <td>{{$diamond->id}}</td>
                <td>{{$diamond->stock_no}}</td>
                <td>{{$diamond->weight}}</td>
                <td>{{$diamond->color}}</td>
                <td>{{$diamond->clarity}}</td>
                <td>{{$diamond->location->name ?? '-'}}</td>
                <td>
                  @if($diamond->status == 'in_stock')
                  <div class="btn btn-success sellBtn" data-id="{{$diamond->id}}">Sell</div>
                  @endif
                </td>
                <td>
                  @if($diamond->status == 'with_broker')
                  <span class="badge bg-warning">Broker</span>
                  @elseif($diamond->status == 'sold')
                  <span class="badge bg-danger">Sold</span>
                  @else
                  <span class="badge bg-success">In Stock</span>
                  @endif
                </td>

              </tr>

              @endforeach

            </tbody>

          </table>

        </form>

      </div>
    </div>

  </div>
</div>


<div class="modal fade" id="sellModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Sell Diamond - <span id="sell_diamond"></span> - (<span id="sell_weight"></span>)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="sellForm">

        <div class="modal-body">

          <input type="hidden" name="item_id" id="sell_item_id">
          <input type="hidden" name="broker_id" id="sell_broker_id">

          <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Payment Status</label>
            <select name="payment_status" class="form-control" required>
              <option value="">Select</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Payment Type</label>
            <select name="payment_type" class="form-control" required>
              <option value="">Select</option>
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="upi">UPI</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Sell Diamond</button>
        </div>

      </form>

    </div>
  </div>
</div>


@endsection

@section('script')

<script>
  // select all checkbox
  $('#checkAll').click(function() {
    $('.diamondCheckbox').prop('checked', $(this).prop('checked'));
    updateSelectedCount();
  });

  // individual checkbox
  $('.diamondCheckbox').on('change', function() {
    updateSelectedCount();
  });

  function updateSelectedCount() {
    let count = $('.diamondCheckbox:checked').length;
    $('#selectedCount').text(count);
  }

  // validation before submit
  $('#transferForm').submit(function(e) {

    let checked = $('.diamondCheckbox:checked').length;

    if (checked === 0) {
      alert('Please select at least one diamond to transfer');
      e.preventDefault();
    }

  });



  $(document).on('click', '.sellBtn', function() {

    // reset form
    $('#sellForm')[0].reset();

    // clear validation errors if using jquery validate
    $('#sellForm').validate().resetForm();

    // clear diamond info text
    $('#sell_barcode').text('');
    $('#sell_weight').text('');

    let item_id = $(this).data('id');
    let broker_id = null;

    let row = $(this).closest('tr');

    let d_name = row.find('td:eq(2)').text();
    let weight = row.find('td:eq(3)').text();

    $('#sell_item_id').val(item_id);
    $('#sell_broker_id').val(broker_id);

    $('#sell_diamond').text(d_name);
    $('#sell_weight').text(weight);

    $('#sellModal').modal('show');

  });

  $("#sellForm").validate({

    rules: {
      price: {
        required: true
      },
      payment_status: {
        required: true
      },
      payment_type: {
        required: true
      }
    },

    submitHandler: function(form) {

      let item_id = $('#sell_item_id').val();
      let row = $('.sellBtn[data-id="' + item_id + '"]').closest('tr');

      $.post('/admin/sell-diamond', {

        item_id: item_id,
        broker_id: $('#sell_broker_id').val() || null,
        price: $('input[name=price]').val(),
        payment_status: $('select[name=payment_status]').val(),
        payment_type: $('select[name=payment_type]').val(),
        _token: '{{csrf_token()}}'

      }, function(res) {

        if (res.success) {
          $('#sellModal').modal('hide');

          alert(res.success);
          location.reload();

        } else {
          showAlert(res.error, 'danger');
        }

      });

    }

  });
</script>

@endsection