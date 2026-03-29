@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex justify-content-between">
      <h4 class="mb-0">Issue {{$location}} Broker</h4>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">

    <form method="POST" action="{{route('admin.broker.memo.store')}}">
      @csrf

      <input type="hidden" name="location_id" value="{{$location_id}}">

      <div class="row mb-3">

        <div class="col-md-4">
          <label>Broker</label>
          <select name="broker_id" class="form-control">
            @foreach($brokers as $broker)
            <option value="{{$broker->id}}">
              {{$broker->name}}
            </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label>Scan Barcode</label>
          <input type="text" id="barcode_scan"
            class="form-control"
            placeholder="Scan barcode">
        </div>

      </div>

      <table class="table table-bordered" id="diamondTable">

        <thead>

          <tr>
            <th>Barcode</th>
            <th>Weight</th>
            <th>Color</th>
            <th>Clarity</th>
            <th>Remove</th>
          </tr>

        </thead>

        <tbody></tbody>

      </table>

      <div class="row">

        <div class="col-md-6">

          <h5>Total Diamonds :
            <span id="total_diamond">0</span>
          </h5>

          <h5>Total Carat :
            <span id="total_carat">0</span>
          </h5>

        </div>

      </div>

      <button class="btn btn-primary">
        Generate Memo
      </button>

    </form>

  </div>
</div>

@endsection

@section('script')
<script>
  $('#barcode_scan').keypress(function(e) {

    if (e.which == 13) {

      e.preventDefault(); // IMPORTANT

      let barcode = $(this).val();
      let broker_id = $('select[name="broker_id"]').val();

      $.ajax({

        url: '/admin/get-diamond-by-barcode',
        type: 'POST',

        data: {
          barcode: barcode,
          broker_id: broker_id,
          _token: '{{csrf_token()}}'
        },

        success: function(res) {

          console.log(res);

          if (res.error) {
            alert(res.error);
            return;
          }

          // check duplicate diamond
          if ($('#diamondTable input[name="diamond_ids[]"][value="' + res.id + '"]').length > 0) {
            alert('Diamond already scanned');
            return;
          }

          $('#diamondTable tbody').append(
            '<tr>' +
            '<td>' + res.barcode_number + '</td>' +
            '<td>' + res.weight + '</td>' +
            '<td>' + res.color + '</td>' +
            '<td>' + res.clarity + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-danger removeRow">X</button>' +
            '<input type="hidden" name="diamond_ids[]" value="' + res.id + '">' +
            '</td>' +
            '</tr>'
          );

          calculateTotal();

        }

      });

      $(this).val('');

    }

  });

  function calculateTotal() {

    let totalDiamond = 0;
    let totalCarat = 0;

    $('#diamondTable tbody tr').each(function() {

      totalDiamond++;

      let weight = $(this).find('td:eq(1)').text();
      totalCarat += parseFloat(weight);

    });

    $('#total_diamond').text(totalDiamond);
    $('#total_carat').text(totalCarat.toFixed(2));

  }

  $(document).on('click', '.removeRow', function() {

    $(this).closest('tr').remove();
    calculateTotal();

  });
</script>
@endsection