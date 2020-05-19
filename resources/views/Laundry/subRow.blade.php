<?php
  $row = isset($row) ? $row : new \stdClass();
  $rowIndex = isset($rowIndex) ? $rowIndex : null;
  $canSave = Perm::can(['laundry_simpan']);
  $categoryId =  $row->ldetail_lcategory_id ?? '' ;
  $categoryName =  $row->ldetail_lcategory_name ?? '' ;
  $qty =  $row->ldetail_qty ?? '' ;
  $total = $row->ldetail_total ?? '' ;
  $price = $row->price ?? '';
  $id = $row->id ?? '';
  $endDate = $row->ldetail_end_date ?? '' ;
  $tipe = $row->ldetail_type ?? '';
  $condition = $row->ldetail_condition ?? '';
?>

<tr class="subitem">
  <td style="width:250px">
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{$id}}">
    @if(empty($data->laundry_executed_at))
    <select class="form-control input-sm" id="dtl[{{ $rowIndex }}][ldetail_lcategory_id]" name="dtl[{{ $rowIndex }}][ldetail_lcategory_id]">
      @if($categoryId)
      <option value="{{$categoryId}}"> {{ $categoryName }} </option>
      @endif
    </select>
    @else
      <input type="hidden" name="dtl[{{ $rowIndex }}][ldetail_lcategory_id]" value="{{$categoryId}}">
      <input type="text" name="dtl[{{ $rowIndex }}][ldetail_lcategory_name]" value="{{ $categoryName }}" class="form-control input-sm text-right" maxlength="18"
      autocomplete="off" readonly="readonly" />
    @endif
  </td>
  <td>
    <input type="text" name="dtl[{{ $rowIndex }}][ldetail_end_date]" value="{{$endDate}}" class="form-control input-sm text-right"
    readonly />
  </td>
  <td>
    <input type="number" name="dtl[{{ $rowIndex }}][ldetail_qty]" value="{{$qty}}" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" {{ !empty($data->laundry_executed_at) ? 'readonly' : '' }} />
  </td>
  <td>
    <input type="text" name="dtl[{{ $rowIndex }}][ldetail_type]" value="{{$tipe}}" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" readonly />
  </td>
  <td>
  <input type="text" name="dtl[{{ $rowIndex }}][ldetail_condition]" value="{{$condition}}" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" {{ !empty($data->laundry_executed_at) ? 'readonly' : '' }} />
  </td>
  <td>
    <input type="hidden" value="{{$price}}" name="dtl[{{ $rowIndex }}][price]" />
    <input type="number" name="dtl[{{ $rowIndex }}][ldetail_total]" value="{{$total}}" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" readonly />
  </td>
  @if ($canSave)
    <td>
      <div class="btn-group">
        @if(empty($data->laundry_executed_at) && Perm::can(['laundry_hapus']))
        <!-- <button type="button" class="btn btn-xs btn-default" moveup-row>
            <span class="fa fa-long-arrow-up fa-fw"></span>
        </button>
        <button type="button" class="btn btn-xs btn-default" movedown-row>
            <span class="fa fa-long-arrow-down fa-fw"></span>
        </button> -->
          <button type="button" class="btn btn-xs btn-danger" remove-row>
              <span class="fa fa-trash fa-fw"></span>
          </button>
        @endif
      <div>
    </td>
  @endif
</tr>