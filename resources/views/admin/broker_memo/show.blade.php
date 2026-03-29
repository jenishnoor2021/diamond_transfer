@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center">

      <a href="{{ route('admin.broker.memo.index', ['location' => $memo->location->name]) }}"
        class="btn btn-secondary btn-sm waves-effect waves-light me-2">
        <i class="fa fa-arrow-left"></i>
      </a>

      <h4 class="mb-0 font-size-18">Broker Memo</h4>

    </div>
  </div>
</div>

<div class="card">
  <div class="card-body" id="printArea">

    <p>Memo No : {{$memo->memo_number}}</p>
    <p>Broker : {{$memo->broker->name}}</p>
    <p>Date : {{$memo->issue_date}}</p>

    <input type="hidden" id="memo_id" value="{{$memo->id}}">

    <div class="row mb-3 mt-2">

      <div class="col-md-4 d-flex">
        <label>Scan Barcode : </label>
        <input type="text"
          id="barcode_scan"
          class="form-control"
          placeholder="Scan barcode to add">
      </div>

    </div>

    <table class="table table-bordered">

      <thead>

        <tr>
          <th>Action</th>
          <th>Barcode</th>
          <th>Weight</th>
          <th>Color</th>
          <th>Clarity</th>
        </tr>

      </thead>

      <tbody>

        @foreach($memo->items as $item)

        <tr>
          <td>
            <button class="btn btn-outline-danger waves-effect waves-light removeDiamond"
              data-id="{{$item->id}}">
              <i class="fa fa-trash"></i>
            </button>
          </td>
          <td>{{$item->diamond->barcode_number}}</td>
          <td>{{$item->diamond->weight}}</td>
          <td>{{$item->diamond->color}}</td>
          <td>{{$item->diamond->clarity}}</td>

        </tr>

        @endforeach

      </tbody>

    </table>

  </div>
</div>

<!-- <button onclick="printMemo()" class="btn btn-success">
  Print Memo
</button> -->

<a href="{{ route('admin.broker.memo.print', $memo->id) }}"
  target="_blank"
  class="btn btn-success">
  Print Memo
</a>

@endsection

@section('script')
<script>
  function printMemo() {

    var printContents = document.getElementById('printArea').innerHTML;

    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

  }

  $(document).on('click', '.removeDiamond', function() {

    if (!confirm('Remove this diamond from memo?')) {
      return;
    }

    let item_id = $(this).data('id');

    $.post('/admin/broker-memo/remove-diamond', {
      item_id: item_id,
      _token: '{{csrf_token()}}'
    }, function(res) {

      alert(res.success);
      location.reload();

    });

  });

  $('#barcode_scan').keypress(function(e) {

    if (e.which == 13) {

      e.preventDefault();

      let barcode = $(this).val();
      let memo_id = $('#memo_id').val();

      $.post('/admin/broker-memo/add-diamond', {

        barcode: barcode,
        memo_id: memo_id,
        _token: '{{csrf_token()}}'

      }, function(res) {

        if (res.error) {
          alert(res.error);
          return;
        }

        alert(res.success);

        location.reload();

      });

      $(this).val('');

    }

  });
</script>
@endsection