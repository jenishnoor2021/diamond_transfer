@extends('layouts.admin')

@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Return / Sale {{$location}}</h4>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="card">
  <div class="card-body">
    @include('includes.flash_message')

    <div class="row">

      <input type="hidden" id="location_id" value="{{$location_id}}">

      <div class="col-md-4">
        <label>Select Broker</label>
        <select id="broker_id" class="form-control">
          <option value="">Select Broker</option>

          @foreach($brokers as $broker)
          <option value="{{$broker->id}}">
            {{$broker->name}}
          </option>
          @endforeach

        </select>
      </div>

      <div class="col-md-4">
        <label>Select Memo</label>
        <select id="memo_id" class="form-control"></select>
        <div id="memoMessage" class="text-danger mt-2"></div>
      </div>

      <div class="col-md-4 d-none">
        <button class="btn btn-primary mt-4" id="loadDiamonds">
          Load Diamonds
        </button>
      </div>

    </div>

  </div>
</div>

<div class="card mt-3">
  <div class="card-body">

    <table class="table table-bordered" id="diamondTable">

      <thead>
        <tr>
          <th>Barcode</th>
          <th>Name</th>
          <th>Weight</th>
          <th>Color</th>
          <th>Clarity</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody></tbody>

    </table>

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
  $('#broker_id').change(function() {

    let broker_id = $(this).val();
    let location_id = $('#location_id').val();

    $('#memo_id').html('<option>Loading...</option>');

    $.post('/admin/broker-memos', {
      broker_id: broker_id,
      location_id: location_id,
      _token: '{{csrf_token()}}'
    }, function(res) {

      $('#memo_id').html('');

      if (res.length == 0) {

        $('#memo_id').html(
          '<option value="">No memo found for this broker</option>'
        );

      } else {

        $('#memo_id').append(
          '<option value="">Select Memo</option>'
        );

        res.forEach(function(memo) {

          $('#memo_id').append(
            '<option value="' + memo.id + '">' + memo.memo_number + '</option>'
          );

        });

      }

    });

  });

  $('#loadDiamonds').click(function() {

    let memo_id = $('#memo_id').val();

    if (memo_id == '') {
      alert('Please select memo');
      return;
    }

    $.post('/admin/get-memo-diamonds', {
      memo_id: memo_id,
      _token: '{{csrf_token()}}'
    }, function(res) {

      $('#diamondTable tbody').html('');

      if (res.length == 0) {

        $('#diamondTable tbody').append(
          '<tr><td colspan="6" class="text-center">No diamonds found</td></tr>'
        );

        return;
      }

      res.forEach(function(item) {

        $('#diamondTable tbody').append(

          '<tr>' +
          '<td>' + item.diamond.barcode_number + '</td>' +
          '<td>' + item.diamond.stock_no + '</td>' +
          '<td>' + item.diamond.weight + '</td>' +
          '<td>' + item.diamond.color + '</td>' +
          '<td>' + item.diamond.clarity + '</td>' +

          '<td>' +
          '<button class="btn btn-danger returnBtn" data-id="' + item.id + '">Return</button> ' +
          '<button class="btn btn-success sellBtn" data-id="' + item.id + '">Sell</button>' +
          '</td>' +

          '</tr>'

        );

      });

    });

  });

  $('#memo_id').change(function() {
    $('#loadDiamonds').click();
  });

  $(document).on('click', '.returnBtn', function() {

    if (!confirm("Are you sure you want to return this diamond?")) {
      return;
    }

    let item_id = $(this).data('id');
    let row = $(this).closest('tr');

    $.post('/admin/return-diamond', {
      item_id: item_id,
      _token: '{{csrf_token()}}'
    }, function(res) {

      row.remove();
      showAlert(res.success, 'success');

      // if table becomes empty
      if ($('#diamondTable tbody tr').length == 0) {
        $('#diamondTable tbody').append(
          '<tr><td colspan="6" class="text-center">No diamonds found</td></tr>'
        );
      }

    });

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
    let broker_id = $('#broker_id').val();

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
        broker_id: $('#sell_broker_id').val(),
        price: $('input[name=price]').val(),
        payment_status: $('select[name=payment_status]').val(),
        payment_type: $('select[name=payment_type]').val(),
        _token: '{{csrf_token()}}'

      }, function(res) {

        if (res.success) {
          $('#sellModal').modal('hide');

          row.remove();

          showAlert(res.success, 'success');

          if ($('#diamondTable tbody tr').length == 0) {

            $('#diamondTable tbody').append(
              '<tr><td colspan="6" class="text-center">No diamonds found</td></tr>'
            );

          }
        } else {
          showAlert(res.error, 'danger');
        }

      });

    }

  });
</script>

@endsection