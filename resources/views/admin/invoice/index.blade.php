@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">

    <div class="page-title-box d-sm-flex justify-content-between">

      <h4 class="mb-0">Invoices</h4>

      <a href="{{ route('admin.add.invoice') }}" class="btn btn-primary">
        Add Invoice
      </a>

    </div>

  </div>
</div>


<div class="card">
  <div class="card-body">

    @include('includes.flash_message')

    <table class="table table-bordered">

      <thead>
        <tr>
          <th>#</th>
          <th>Action</th>
          <th>Invoice No</th>
          <th>Date</th>
          <th>Amount</th>
        </tr>
      </thead>

      <tbody>

        @foreach($invoices as $invoice)

        <tr>
          <td>{{$loop->iteration}}</td>
          <td class="d-flex gap-1">
            <!-- Edit Button -->
            <a href="{{ route('admin.invoice.edit', $invoice->id) }}"
              class="btn btn-outline-primary waves-effect waves-light">
              <i class="fa fa-edit"></i>
            </a>

            <!-- Delete Form -->
            <form action="{{ route('admin.invoice.delete', $invoice->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this invoice?')">
              @csrf
              @method('DELETE')

              <button type="submit"
                class="btn btn-outline-danger waves-effect waves-light">
                <i class="fa fa-trash"></i>
              </button>
            </form>

            <a href="{{ route('admin.invoice.preview', $invoice->id) }}" class="btn btn-outline-success waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>
          </td>

          <td>{{$invoice->invoice_no}}</td>
          <td>{{$invoice->invoice_date}}</td>
          <td>{{$invoice->grand_total}}</td>
        </tr>

        @endforeach

      </tbody>

    </table>

  </div>
</div>

@endsection