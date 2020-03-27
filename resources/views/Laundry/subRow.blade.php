<?php
  $row = isset($row) ? $row : new \stdClass();
  $rowIndex = isset($rowIndex) ? $rowIndex : null;
  $canSave = Perm::can(['laundry_simpan']);
?>

<tr>
  <td>
    <select class="form-control input-sm" name="dtl[{{ $rowIndex }}][ldetail_lcategory_name]">
  </td>
  <td>
    <input type="number" name="dtl[{{ $rowIndex }}][ldetail_qty]" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" />
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][price]" />
    <input type="number" name="dtl[][ldetail_price]" class="form-control input-sm text-right" maxlength="18"
    autocomplete="off" readonly />
  </td>
  @if ($canSave)
    <td>
      <div class="btn-group">
        <button type="button" class="btn btn-xs btn-default" moveup-row>
            <span class="fa fa-long-arrow-up fa-fw"></span>
        </button>
        <button type="button" class="btn btn-xs btn-default" movedown-row>
            <span class="fa fa-long-arrow-down fa-fw"></span>
        </button>
        <button type="button" class="btn btn-xs btn-danger" remove-row>
            <span class="fa fa-trash fa-fw"></span>
        </button>
      <div>
    </td>
  @endif
</tr>