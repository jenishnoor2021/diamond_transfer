<!DOCTYPE html>
<html>

<head>

  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

  <style>
    .barcode-box {
      width: 180px;
      float: left;
      margin: 10px;
      text-align: center;
    }

    p {
      font-size: 10px;
    }

    @media print {
      .no-print {
        display: none;
      }
    }
  </style>

</head>

<body>

  @foreach($diamonds as $diamond)

  <div class="barcode-box">

    <p>
      <b>
        {{ \Carbon\Carbon::parse($diamond->created_at)->format('d/m') }}
        |
        {{ $diamond->stock_no }}
        |
        {{ $diamond->weight }}
      </b>
    </p>

    <svg id="barcode{{$diamond->id}}"></svg>

    <script>
      JsBarcode("#barcode{{$diamond->id}}", "{{$diamond->barcode_number}}", {
        format: "CODE128",
        displayValue: true,
        height: 50,
        width: 2
      });
    </script>

  </div>

  @endforeach

  <div style="clear:both"></div>

  <button class="no-print" onclick="window.print()">Print</button>

</body>

</html>