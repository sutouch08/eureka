<?php
$id  = $_GET['id_return_order'];
$ro = new return_order($id);
$customer = new customer($ro->id_customer);
$sale = new sale($customer->id_sale);
$zone = new zone($ro->id_zone);
$product = new product();
$qs = $ro->getDetails($id);
?>


<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($ro->date_add); ?>" disabled />
  </div>
  <div class="col-sm-2 padding-5">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $ro->reference; ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่บิล</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $ro->order_code; ?>" disabled />
  </div>
  <div class="col-sm-3 col-3-harf padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm" value="<?php echo $customer->full_name; ?>" disabled />
  </div>
  <div class="col-sm-3 col-3-harf padding-5 last">
    <label>พนักงานขาย</label>
    <input type="text" class="form-control input-sm" value="<?php echo $sale->full_name; ?>" disabled />
  </div>

  <div class="col-sm-8 padding-5 first">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" value="<?php echo $ro->remark; ?>" disabled />
  </div>
  <div class="col-sm-4 padding-5 last">
    <label>โซนรับสินค้า</label>
    <input type="text" class="form-control input-sm" value="<?php echo $zone->zone_name; ?>" disabled />
  </div>
  <input type="hidden" id="id_return_order" value="<?php echo $ro->id; ?>" />
</div>
<hr class="margin-top-15"/>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-15">บาร์โค้ด</th>
          <th class="width-45">สินค้า</th>
          <th class="width-10 text-right">ราคา</th>
          <th class="width-10 text-right">จำนวน</th>
          <th class="width-10 text-right">มูลค่า</th>
          <th class="width-5 text-center">สถานะ</th>
        </tr>
      </thead>
      <tbody>
<?php if(dbNumRows($qs) > 0 ) : ?>
<?php   $no = 1; ?>
<?php   $totalQty = 0; ?>
<?php   $totalAmount = 0; ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
<?php     $pd = $product->getDetail($rs->id_product_attribute); ?>
        <tr>
          <td class="text-center"><?php echo $no; ?></td>
          <td><?php echo $pd->barcode; ?></td>
          <td><?php echo $pd->reference .' : '.$pd->product_name; ?></td>
          <td class="text-right"><?php echo number($rs->price, 2); ?></td>
          <td class="text-right"><?php echo number($rs->qty); ?></td>
          <td class="text-right"><?php echo number($rs->amount, 2); ?></td>
          <td class="text-center"><?php echo isActived($rs->valid); ?></td>
        </tr>
<?php     $no++; ?>
<?php     $totalQty += $rs->qty; ?>
<?php     $totalAmount += $rs->amount; ?>
<?php   endwhile; ?>
        <tr>
          <td colspan="4" class="text-right">รวม</td>
          <td class="text-right"><?php echo number($totalQty); ?></td>
          <td class="text-right"><?php echo number($totalAmount, 2); ?></td>
          <td></td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
