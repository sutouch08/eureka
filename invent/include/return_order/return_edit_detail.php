<?php
$qs = $ro->getDetails($id);
$product = new product();
?>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-15">บาร์โค้ด</th>
          <th class="width-40">สินค้า</th>
          <th class="width-10 text-right">ราคา</th>
          <th class="width-10 text-right">จำนวน</th>
          <th class="width-15 text-right">มูลค่า</th>
          <th class="width-5 text-center"></th>
        </tr>
      </thead>
      <tbody id="result">
<?php if(dbNumRows($qs) > 0 ) : ?>
<?php   $no = 1; ?>
<?php   $totalQty = 0; ?>
<?php   $totalAmount = 0; ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
<?php     $pd = $product->getDetail($rs->id_product_attribute); ?>
        <tr id="row-<?php echo $rs->id_product_attribute; ?>">
          <td class="text-center no"><?php echo $no; ?></td>
          <td class=""><?php echo $pd->barcode; ?></td>
          <td><?php echo $pd->reference.' : '.$pd->product_name; ?></td>
          <td class="text-right price" id="price-<?php echo $rs->id_product_attribute; ?>">
            <?php echo number($rs->price, 2); ?>
          </td>
          <td class="text-right">
            <input type="number" class="form-control input-sm input-mini text-right qty" id="qty-<?php echo $rs->id_product_attribute; ?>" value="<?php echo number($rs->qty); ?>" />
          </td>
          <td class="text-right"><?php echo number($rs->amount, 2); ?></td>
          <td class="text-right">
            <?php if($edit && $rs->valid == 0) : ?>
              <button type="button" class="btn btn-xs btn-danger" onclick="removeDetail(<?php echo $rs->id_product_attribute; ?>)">
                <i class="fa fa-trash"></i>
              </button>
            <?php endif; ?>
          </td>
        </tr>
<?php     $no++; ?>
<?php     $totalQty += $rs->qty; ?>
<?php     $totalAmount += $rs->amount; ?>
<?php   endwhile; ?>
<?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="text-right">รวม</td>
          <td class="text-right" id="sumQty"><?php echo number($totalQty); ?></td>
          <td class="text-right" id="sumAmount"><?php echo number($totalAmount, 2); ?></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
