<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: DejaVu Sans;
      font-size: 11px;
    }

    .border {
      border: 1px solid #000;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 5px;
    }

    .no-border td {
      border: none;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <table class="no-border">
    <tr>
      <td class="text-center">
        <h2 style="margin:0;">{{ $company->name }}</h2>
        <p style="margin:0;">{{ $company->address }}</p>
        <h4 style="margin:5px 0;">TAX INVOICE</h4>
      </td>
    </tr>
  </table>

  <!-- CLIENT + INVOICE INFO -->
  <table>
    <tr>
      <td width="60%">
        <b>To,</b><br>
        Name : {{ $invoice->client_name }}<br>
        Address : {{ $invoice->client_address }}<br>
        Mobile : {{ $invoice->client_mobile }}<br>
        GST No : {{ $invoice->client_gst_no }}<br>
      </td>

      <td width="40%">
        <b>Invoice No:</b> {{ $invoice->invoice_no ?? '-' }}<br>
        <b>Date:</b> {{ $invoice->invoice_date }}<br>
        <b>Payment Term:</b> 7 Days
      </td>
    </tr>
  </table>

  <!-- ITEM TABLE -->
  <table>
    <thead>
      <tr class="bold text-center">
        <th>#</th>
        <th>Product Name</th>
        <th>Weight</th>
        <th>Rate</th>
        @if($invoice->client_state == 'gujarat')
        <th>CGST</th>
        <th>SGST</th>
        @else
        <th>IGST</th>
        @endif
        <th>Amount</th>
      </tr>
    </thead>

    <tbody>
      @foreach($invoice->items as $key => $item)
      <tr>
        <td class="text-center">{{ $key+1 }}</td>
        <td>{{ $item->diamond->stock_no ?? '-' }}</td>
        <td class="text-right">{{ $item->weight }}</td>
        <td class="text-right">{{ number_format($item->rate,2) }}</td>
        @if($invoice->client_state == 'gujarat')
        <th class="text-right">{{$company->cgst ?? '0.00'}}</th>
        <th class="text-right">{{$company->sgst ?? '0.00'}}</th>
        @else
        <th class="text-right">{{$company->igst ?? '0.00'}}</th>
        @endif
        <td class="text-right">{{ number_format($item->amount,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <br>

  <!-- BOTTOM SECTION -->
  <table>
    <tr>
      <!-- LEFT SIDE -->
      <td width="60%" style="vertical-align:top;">

        <b>GSTIN No:</b> {{ $company->gst ?? '-' }} <br><br>

        <b>Bank Name:</b> ICICI Bank <br>
        <b>Bank A/c No:</b> 74150500954 <br>
        <b>RTGS/IFSC Code:</b> ICIC0007415 <br><br>

        <b>Total GST:</b> {{ $invoice->tax }} <br>
        <b>Bill Amount:</b> {{ $invoice->grand_total }} <br><br>

        <b>Note:</b>

      </td>

      <!-- RIGHT SIDE TOTAL BOX -->
      <td width="40%">
        <table>
          <tr>
            <td>Sub Total</td>
            <td class="text-right">{{ number_format($invoice->sub_total,2) }}</td>
          </tr>
          <tr>
            <td>Taxable Amount</td>
            <td class="text-right">{{ number_format($invoice->sub_total,2) }}</td>
          </tr>
          <tr>
            <td>Integrated Tax</td>
            <td class="text-right">{{ number_format($invoice->tax,2) }}</td>
          </tr>
          <tr>
            <td>Round Off</td>
            <td class="text-right">0.00</td>
          </tr>
          <tr class="bold">
            <td>Grand Total</td>
            <td class="text-right">{{ number_format($invoice->grand_total,2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>

  <!-- TERMS -->
  <table>
    <tr>
      <td>
        <b>Terms & Condition:</b><br>
        1. Goods once sold will not be taken back.<br>
        2. Interest @18% p.a. will be charged if payment is not made within due date.<br>
        3. Risk and responsibility ceases as soon as goods leave our premises.<br>
        4. Subject to Surat jurisdiction only.
      </td>
    </tr>
  </table>

  <br><br>

  <table class="no-border">
    <tr>
      <td class="text-right">
        For, {{ $company->name }}<br><br><br>
        Authorised Signatory
      </td>
    </tr>
  </table>

</body>

</html>