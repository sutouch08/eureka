<?php
$id = $_GET['id_return_order'];
$cs = new return_order($id);
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="leave()">
        <i class="fa fa-arrow-left"></i> กลับ
      </button>
<?php if($cs->isSave == 1 && $delete) : ?>
      <button type="button" class="btn btn-sm btn-danger" onclick="unSaveReturn()">ยกเลิกการบันทึก</button>
<?php else : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
<?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php
if($cs->isSave == 1)
{
  include 'include/return_order/return_view_detail.php';
}
else
{
  include 'include/return_order/return_edit_header.php';
  include 'include/return_order/return_add_item.php';
  include 'include/return_order/return_edit_detail.php';
}
?>

<script id="bill-template" type="text/x-handlebarsTemplate">

      {{#each this}}
        {{#if nodata}}

        {{else}}
          <tr class="font-size-12 row-{{id}}">
            <td class="middle text-center no">{{no}}</td>
            <td class="middle">{{barcode}}</td>
            <td class="middle">{{product}}</td>
            <td class="middle text-right" id="price-{{id}}">{{price}}</td>
            <td class="middle text-right">
              <input type="number" class="form-control input-sm text-right qty" id="qty-{{id}}" value="{{qty}}" />
            </td>
            <td class="middle text-right" id="cnAmount-{{id}}">{{amount}}</td>
            <td class="middle text-right">
              <button type="button" class="btn btn-xs btn-danger" onclick="removeRow({{id}})">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        {{/if}}
      {{/each}}

</script>

<script src="script/return_order/return_edit.js"></script>
