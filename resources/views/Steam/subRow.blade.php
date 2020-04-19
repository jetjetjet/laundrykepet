<?php
  $row = isset($row) ? $row : new \stdClass();
  $rowIndex = isset($rowIndex) ? $rowIndex : null;
  $canSave = Perm::can(['steam_simpan']);
  $categoryId =  $row->sdetail_scategory_id ?? '' ;
  $categoryName =  $row->sdetail_scategory_name ?? '' ;
  $total = $row->sdetail_price ?? '' ;
  $price = $row->price ?? '';
  $id = $row->id ?? '';
  $plate = $row->sdetail_plate ?? '' ;
?>

<tr class="subitem">
  <td style="width:250px">
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{$id}}">
    @if(empty($data->steam_executed_at))
    <select class="form-control input-sm" id="dtl[{{ $rowIndex }}][sdetail_scategory_id]" name="dtl[{{ $rowIndex }}][sdetail_scategory_id]">
      @if($categoryId)
      <option value="{{$categoryId}}"> {{ $categoryName }} </option>
      @endif
    </select>
    @else
      <input type="hidden" name="dtl[{{ $rowIndex }}][sdetail_scategory_id]" value="{{$categoryId}}">
      <input type="text" name="dtl[{{ $rowIndex }}][sdetail_scategory_name]" value="{{ $categoryName }}" class="form-control input-sm text-right" maxlength="18"
      autocomplete="off" readonly="readonly" />
    @endif
  </td>
  <td>
    <input type="text" name="dtl[{{ $rowIndex }}][sdetail_plate]" value="{{$plate}}" class="form-control input-sm text-right" />
  </td>
  <td>
    <input type="hidden" value="{{$price}}" name="dtl[{{ $rowIndex }}][price]" />
    <input type="number" name="dtl[{{ $rowIndex }}][sdetail_total]" value="{{$total}}" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" readonly />
  </td>
  @if ($canSave)
    <td>
      <div class="btn-group">
        @if(empty($data->steam_executed_at) && Perm::can(['steam_hapus']))
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