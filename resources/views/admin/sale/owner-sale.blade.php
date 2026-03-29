@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex justify-content-between">
      <h4 class="mb-0">{{$type}} Owner Sale</h4>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <div class="mb-2">
          <strong>Total Diamonds :</strong> {{ $diamonds->count() }}
        </div>

        <table id="datatable" class="table table-bordered">

          <thead>
            <tr>
              <th>Barcode</th>
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
              <td>{{$diamond->barcode_number}}</td>
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

    let d_name = row.find('td:eq(1)').text();
    let weight = row.find('td:eq(2)').text();

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