<?php $cs = new return_order(); ?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="leave()">
        <i class="fa fa-arrow-left"></i> กลับ
      </button>
      <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="save()">
        <i class="fa fa-save"></i> บันทึก
      </button>
    </p>
  </div>
</div>
<hr/>

<?php include 'include/return_order/return_add_header.php'; ?>
<?php include 'include/return_order/return_add_item.php'; ?>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">No.</th>
          <th class="width-15">บาร์โค้ด</th>
          <th class="">สินค้า</th>
          <th class="width-10 text-right">ราคา</th>
          <th class="width-10 text-right">จำนวน</th>
          <th class="width-10 text-right">มูลค่า</th>
          <th class="width-10"></th>
        </tr>
      </thead>
      <tbody id="result">

      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="text-right">รวม</td>
          <td class="text-right" id="sumQty">0</td>
          <td class="text-right" id="sumAmount">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<script id="bill-template" type="text/x-handlebarsTemplate">

      {{#each this}}
        {{#if nodata}}

        {{else}}
          <tr class="font-size-12">
            <td class="middle text-center no">{{no}}</td>
            <td class="middle text-center">{{barcode}}</td>
            <td class="middle">{{product}}</td>
            <td class="middle text-right price" id="price-{{id}}">{{price}}</td>
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
