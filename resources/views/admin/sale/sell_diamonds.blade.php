@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">All Sale Diamonds List</h4>

    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
          <thead>
            <tr>
              <th>Action</th>
              <th>Stock No</th>
              <th>Barcode</th>
              <th>Price</th>
              <th>Payment Status</th>
              <th>Payment Type</th>
              <th>Sale Type</th>
              <th>Sold Date</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($sales as $sale)
            <td>
              <button class="btn btn-primary btn-sm editSaleBtn"
                data-id="{{ $sale->id }}"
                data-price="{{ $sale->price }}"
                data-payment_status="{{ $sale->payment_status }}"
                data-payment_type="{{ $sale->payment_type }}"
                data-sale_type="{{ $sale->sale_type }}"
                data-sold_date="{{ $sale->sold_date }}">
                <i class="fa fa-edit"></i>
              </button>

              <a href="{{ route('admin.sales.delete',$sale->id) }}"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure to delete this sale?')">
                <i class="fa fa-trash"></i>
              </a>
            </td>

            <td>{{ $sale->diamond->stock_no }}</td>
            <td>{{ $sale->diamond->barcode_number ?? '-' }}</td>
            <td>{{ $sale->price }}</td>
            <td>
              <span class="badge bg-info">{{ $sale->payment_status }}</span>
            </td>
            <td>{{ $sale->payment_type }}</td>
            <td>{{ $sale->sale_type }}</td>
            <td>{{ $sale->sold_date }}</td>

            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="editSaleModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('admin.sales.update') }}">
        @csrf

        <input type="hidden" name="sale_id" id="sale_id">

        <div class="modal-header">
          <h5>Edit Sale</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label>Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
          </div>

          <div class="mb-2">
            <label>Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control" required>
              <option value="paid">Paid</option>
              <option value="partial">Partial</option>
              <option value="pending">Pending</option>
            </select>
          </div>

          <div class="mb-2">
            <label>Payment Type</label>
            <select name="payment_type" id="payment_type" class="form-control" required>
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="upi">UPI</option>
            </select>
          </div>

          <div class="mb-2">
            <label>Sale Type</label>
            <select name="sale_type" id="sale_type" class="form-control" required>
              <option value="direct">Direct</option>
              <option value="broker">Broker</option>
            </select>
          </div>

          <div class="mb-2">
            <label>Sold Date</label>
            <input type="date" name="sold_date" id="sold_date" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  $(document).on('click', '.editSaleBtn', function() {

    $('#sale_id').val($(this).data('id'));
    $('#price').val($(this).data('price'));
    $('#payment_status').val($(this).data('payment_status'));
    $('#payment_type').val($(this).data('payment_type'));
    $('#sale_type').val($(this).data('sale_type'));
    $('#sold_date').val($(this).data('sold_date'));

    $('#editSaleModal').modal('show');

  });

  $('form').submit(function(e) {

    let price = $('#price').val();
    let payment_status = $('#payment_status').val();
    let payment_type = $('#payment_type').val();
    let sale_type = $('#sale_type').val();
    let sold_date = $('#sold_date').val();

    if (price == '' || price <= 0) {
      alert('Please enter valid price');
      $('#price').focus();
      return false;
    }

    if (payment_status == '') {
      alert('Please select payment status');
      $('#payment_status').focus();
      return false;
    }

    if (payment_type == '') {
      alert('Please select payment type');
      $('#payment_type').focus();
      return false;
    }

    if (sale_type == '') {
      alert('Please select sale type');
      $('#sale_type').focus();
      return false;
    }

    if (sold_date == '') {
      alert('Please select sold date');
      $('#sold_date').focus();
      return false;
    }

  });
</script>
@endsection