<div class="row">

  <div class="col-md-6 mb-2">
    <strong>Name:</strong> {{ $diamond->stock_no }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Barcode:</strong> {{ $diamond->barcode_number ?? '-' }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Weight:</strong> {{ $diamond->weight }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Color:</strong> {{ $diamond->color ?? '-' }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Clarity:</strong> {{ $diamond->clarity ?? '-' }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Location:</strong> {{ $diamond->location->name ?? '-' }}
  </div>

  <div class="col-md-6 mb-2">
    <strong>Status:</strong>

    @if($diamond->status == 'in_stock')
    <span class="badge bg-warning">In Stock</span>
    @elseif($diamond->status == 'with_broker')
    <span class="badge bg-info">Broker</span>
    @elseif($diamond->status == 'sold')
    <span class="badge bg-success">Sold</span>
    @endif

  </div>

</div>