@extends('layouts.admin')

@section('style')
<style>
  label.error {
    color: red;
    font-size: 13px;
  }

  .amount {
    text-align: right;
  }
</style>
@endsection

@section('content')

<div class="card">
  <div class="card-body">

    <h4>Edit Invoice</h4>

    @include('includes.flash_message')

    <form method="POST" id="invoiceForm" action="{{ route('admin.invoice.updateClient', $invoice->id) }}">
      @csrf

      <input type="hidden" id="cgst_rate" value="{{ $company->cgst }}">
      <input type="hidden" id="sgst_rate" value="{{ $company->sgst }}">
      <input type="hidden" id="igst_rate" value="{{ $company->igst }}">

      <div class="row">

        <div class="col-md-4">
          <label>State <span class="text-danger">*</span></label>
          <select name="client_state" id="client_state" class="form-control">
            <option value="">---- Select State ----</option>
            <option value="gujarat" {{ $invoice->client_state == 'gujarat' ? 'selected' : '' }}>Gujarat</option>
            <option value="other" {{ $invoice->client_state == 'other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        <!-- Client Name -->
        <div class="col-md-4">
          <label>Client Name <span class="text-danger">*</span></label>
          <input type="text" name="client_name" class="form-control" value="{{ $invoice->client_name }}">
        </div>

        <!-- Mobile -->
        <div class="col-md-4">
          <label>Mobile <span class="text-danger">*</span></label>
          <input type="text" name="client_mobile" class="form-control" value="{{ $invoice->client_mobile }}">
        </div>

        <!-- Date -->
        <div class="col-md-4 mt-3">
          <label>Invoice Date <span class="text-danger">*</span></label>
          <input type="date" name="invoice_date" class="form-control"
            value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}">
        </div>

        <!-- Address -->
        <div class="col-md-8 mt-3">
          <label>Address <span class="text-danger">*</span></label>
          <input type="text" name="client_address" class="form-control" value="{{ $invoice->client_address }}">
        </div>

        <div class="col-md-4 mt-3">
          <div class="d-flex gap-2 mb-3">
            <button type="submit" class="btn btn-primary w-md">Update Client</button>
            <a class="btn btn-light w-md" href="{{ URL::to('/admin/invoices') }}">Back</a>
          </div>
        </div>

    </form>

  </div>

  <hr style="width:100%;border-top:1px solid #000;margin-top:30px;">

  <!-- Scan Diamond -->
  <div class="col-md-6">
    <label>Scan Diamond</label>
    <input type="text" id="scan_diamond" class="form-control" placeholder="Scan Diamond">
  </div>

  <!-- Table -->
  <div class="table-responsive mt-4">
    <table class="table table-bordered" id="diamond_table">
      <thead>
        <tr>
          <th>Diamond</th>
          <th>Weight</th>
          <th>Rate</th>
          <th class="sgst_col d-none">SGST</th>
          <th class="cgst_col d-none">CGST</th>
          <th class="igst_col d-none">IGST</th>
          <th>Amount</th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoice->items as $item)
        <tr>
          <td>
            {{ $item->diamond->stock_no }}
            <input type="hidden" name="diamond_id[]" value="{{ $item->diamond_id }}">
          </td>
          <td><input type="number" name="weight[]" value="{{ $item->weight }}" class="form-control"></td>
          <td><input type="number" name="rate[]" value="{{ $item->rate }}" class="form-control"></td>

          <td class="sgst_col d-none">0</td>
          <td class="cgst_col d-none">0</td>
          <td class="igst_col d-none">0</td>

          <td class="amount">{{ $item->amount }}</td>
          <td><button type="button" class="btn btn-danger remove" data-id="{{ $item->id }}">X</button></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Total -->
  <div class="col-md-4 mt-3">
    <label>Sub Total</label>
    <input type="text" id="sub_total" name="sub_total" class="form-control" readonly>
  </div>

  <div class="col-md-4 mt-3">
    <label>Tax</label>
    <input type="text" id="total_tax" name="tax" class="form-control" readonly>
  </div>

  <div class="col-md-4 mt-3">
    <label>Grand Total</label>
    <input type="text" id="grand_total" name="grand_total" class="form-control" readonly>
  </div>

  <div class="col-md-4 mt-3">
    <div class="d-flex gap-2 mb-3">
      <button type="button" class="btn btn-primary w-md" id="previewBtn">Preview</button>
      <a class="btn btn-light w-md" href="{{ URL::to('/admin/invoices') }}">Back</a>
    </div>
  </div>

</div>
</div>

@endsection

@section('script')

<script>
  $(document).ready(function() {

    $("#invoiceForm").validate({

      rules: {
        client_name: {
          required: true
        },
        client_mobile: {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 10
        },
        invoice_date: {
          required: true
        },
        client_address: {
          required: true
        }
      },

      messages: {
        client_name: {
          required: "Enter client name"
        },
        client_mobile: {
          required: "Enter mobile number",
          digits: "Only numbers allowed",
          minlength: "Must be 10 digits",
          maxlength: "Must be 10 digits"
        },
        invoice_date: {
          required: "Select date"
        },
        client_address: {
          required: "Enter address"
        }
      },

      submitHandler: function(form) {

        if ($('#diamond_table tbody tr').length == 0) {
          alert("Please add at least one diamond");
          return false;
        }

        form.submit();
      }
    });

  });
</script>

<script>
  $('#scan_diamond').on('keypress', function(e) {

    if (e.which == 13) {
      e.preventDefault(); // 🚀 IMPORTANT

      let barcode = $(this).val().trim();

      if (barcode != '') {
        getDiamond(barcode);
      }

      $(this).val('');
    }
  });

  function getDiamond(barcode) {

    $.ajax({
      url: '/admin/invoice/add-diamond',
      type: 'POST',
      data: {
        barcode: barcode,
        invoice_id: <?= $invoice->id ?>,
        _token: '{{ csrf_token() }}'
      },
      success: function(res) {

        if (res.error) {
          alert(res.error);
          return;
        }

        // row append
        $('#diamond_table tbody').append(res.row_html);

        // $('#sub_total').val(res.sub_total.toFixed(2));
        // $('#total_tax').val(res.tax.toFixed(2));
        // $('#grand_total').val(res.grand_total.toFixed(2));

        manageTaxColumns();
        calculateTotal();
      }
    });
  }

  $('#client_state').on('change', function() {
    manageTaxColumns();
    calculateTotal();
  });

  // Remove Row
  $(document).on('click', '.remove', function() {

    if (!confirm('Are you sure to delete this item?')) return;

    let id = $(this).data('id');
    let row = $(this).closest('tr');

    $.post('/admin/invoice/delete-item', {
      id: id,
      _token: '{{ csrf_token() }}'
    }, function(res) {

      if (res.success) {
        row.remove();

        // $('#sub_total').val(res.sub_total.toFixed(2));
        // $('#total_tax').val(res.tax.toFixed(2));
        // $('#grand_total').val(res.grand_total.toFixed(2));

        calculateTotal();
      } else {
        alert(res.error || 'Something went wrong');
      }

    });
  });

  // Auto Calculation
  $(document).on('input', 'input[name="weight[]"], input[name="rate[]"]', function() {

    let row = $(this).closest('tr');

    let qty = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
    let rate = parseFloat(row.find('input[name="rate[]"]').val()) || 0;

    let amount = qty * rate;

    row.find('.amount').val(amount.toFixed(2));

    calculateTotal();
  });


  // Total
  function calculateTotal() {

    let total = 0;
    let totalTax = 0;

    let cgstRate = parseFloat($('#cgst_rate').val()) || 0;
    let sgstRate = parseFloat($('#sgst_rate').val()) || 0;
    let igstRate = parseFloat($('#igst_rate').val()) || 0;

    let clientState = $('#client_state').val().toLowerCase();

    $('.amount').each(function() {

      let amount = $(this).is('input') ? parseFloat($(this).val()) || 0 : parseFloat($(this).text()) || 0;
      total += amount;

      let row = $(this).closest('tr');

      if (clientState === 'gujarat') {

        row.find('.sgst_col').text(sgstRate + '%');
        row.find('.cgst_col').text(cgstRate + '%');
        row.find('.igst_col').text('0');

        let sgst = amount * (sgstRate / 100);
        let cgst = amount * (cgstRate / 100);

        totalTax += sgst + cgst;

      } else {

        row.find('.igst_col').text(igstRate + '%');
        row.find('.sgst_col').text('0');
        row.find('.cgst_col').text('0');

        let igst = amount * (igstRate / 100);

        totalTax += igst;
      }
    });

    $('#sub_total').val(total.toFixed(2));
    $('#total_tax').val(totalTax.toFixed(2));

    let grandTotal = total + totalTax;

    $('#grand_total').val(grandTotal.toFixed(2));
  }

  $(document).ready(function() {
    calculateTotal();
    manageTaxColumns();
  });

  function manageTaxColumns() {

    let clientState = $('#client_state').val();

    if (clientState === 'gujarat') {
      $('.sgst_col, .cgst_col').removeClass('d-none');
      $('.igst_col').addClass('d-none');
    } else {
      $('.igst_col').removeClass('d-none');
      $('.sgst_col, .cgst_col').addClass('d-none');
    }
  }

  $('#previewBtn').click(function() {
    let invoiceId = <?= $invoice->id; ?>;
    window.open('/admin/invoice/preview/' + invoiceId, '_blank');
  });
</script>

@endsection