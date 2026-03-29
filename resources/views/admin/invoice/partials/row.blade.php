<tr>
  <td>
    {{ $item->diamond->stock_no }}
    <input type="hidden" name="diamond_id[]" value="{{ $item->diamond_id }}">
  </td>

  <td><input type="number" value="{{ $item->weight }}" class="form-control"></td>
  <td><input type="number" value="{{ $item->rate }}" class="form-control"></td>

  <td class="sgst_col d-none">0</td>
  <td class="cgst_col d-none">0</td>
  <td class="igst_col d-none">0</td>

  <td><input type="text" class="form-control amount" value="{{ $item->amount }}" readonly></td>

  <td>
    <button class="btn btn-danger remove" data-id="{{ $item->id }}">X</button>
  </td>
</tr>