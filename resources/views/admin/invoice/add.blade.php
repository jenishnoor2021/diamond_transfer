@extends('layouts.admin')

@section('style')
<style>
  label.error {
    color: red;
    font-size: 13px;
  }
</style>
@endsection

@section('content')

<div class="card">
  <div class="card-body">

    <h4>Create Invoice Bill</h4>

    @include('includes.flash_message')

    <form method="POST" id="invoiceForm" action="{{ route('admin.invoice.store') }}">
      @csrf

      <input type="hidden" name="invoice_no" value="{{ $invoiceNo }}">
      <input type="hidden" name="serise_no" value="{{ $seriseNo }}">

      <div class="row">

        <div class="col-md-4">
          <label>Invoice No</label>
          <input type="text" class="form-control" value="{{ $invoiceNo }}" readonly>
        </div>

        <div class="col-md-4">
          <label>State <span class="text-danger">*</span></label>
          <select name="client_state" id="client_state" class="form-control">
            <option value="">---- Select State ----</option>
            <option value="gujarat">Gujarat</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- Client Name -->
        <div class="col-md-4">
          <label>Client Name <span class="text-danger">*</span></label>
          <input type="text" name="client_name" class="form-control">
        </div>

        <!-- Mobile -->
        <div class="col-md-4 mt-3">
          <label>Mobile <span class="text-danger">*</span></label>
          <input type="text" name="client_mobile" class="form-control">
        </div>

        <!-- Date -->
        <div class="col-md-2 mt-3">
          <label>Invoice Date <span class="text-danger">*</span></label>
          <input type="date" name="invoice_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>

        <!-- Address -->
        <div class="col-md-6 mt-3">
          <label>Address <span class="text-danger">*</span></label>
          <input type="text" name="client_address" class="form-control">
        </div>
      </div>

      <div class="col-md-4 mt-3">
        <div class="d-flex gap-2 mb-3">
          <button type="submit" class="btn btn-primary w-md">Save & Add Diamonds</button>
          <a class="btn btn-light w-md" href="{{ URL::to('/admin/invoices') }}">Back</a>
        </div>
      </div>

    </form>

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
        },
        client_state: {
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
        },
        client_state: {
          required: true
        }
      },

      submitHandler: function(form) {
        form.submit();
      }
    });

  });
</script>
@endsection