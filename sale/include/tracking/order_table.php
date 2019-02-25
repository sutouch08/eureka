<?php
$qr = "SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order;
$qs = dbQuery($qr);
?>

<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="table-responsive" style="min-height:200px;">
      <table class="table border-1">
        <thead>
          <tr>
            <th class="width-10 text-center">Image</th>
            <th class="">สินค้า</th>
            <th class="width-10 text-right">ราคา</th>
            <th class="width-10 text-right">ส่วนลด</th>
            <th class="width-10 text-right">จำนวน</th>
            <th class="width-15 text-right">มูลค่า</th>
          </tr>
        </thead>
        <tbody>


  <?php if(dbNumRows($qs) > 0 ) : ?>
  <?php   $pd = new product(); ?>
  <?php   $discount = 0; ?>
  <?php   $amount = 0; ?>
  <?php   $total_amount = 0; ?>
  <?php   while($rs = dbFetchObject($qs)) : ?>
          <tr style="font-size:12px;">
            <td class="middle text-center">
              <img src="<?php echo $pd->get_product_attribute_image($rs->id_product_attribute, 1); ?>" />
            </td>
            <td class="middle">
              <?php echo $rs->product_reference; ?>
            </td>
            <td class="middle text-right">
              <?php echo number($rs->product_price, 2); ?>
            </td>
            <td class="middle text-right">
              <?php echo discountLabel($rs->reduction_percent, $rs->reduction_amount); ?>
            </td>
            <td class="middle text-center">
              <?php echo number($rs->product_qty); ?>
            </td>
            <td class="middle text-right">
              <?php echo number($rs->total_amount,2); ?>
            </td>
          </tr>
  <?php   $discount += $rs->discount_amount; ?>
  <?php   $amount   += $rs->total_amount; ?>
  <?php   $total_amount += ($rs->product_qty * $rs->product_price); ?>
  <?php   endwhile; ?>
          <tr>
            <td rowspan="3" colspan="4">&nbsp;</td>
            <td style="border-left:1px solid #ccc"><b>สินค้า</b></td>
            <td colspan="2" align="right"><b><?php echo number($total_amount,2); ?></b></td>
          </tr>
          <tr>
            <td style="border-left:1px solid #ccc"><b>ส่วนลด</b></td>
            <td align="right"><b><?php echo number($discount,2); ?></b></td>
          </tr>
          <tr>
            <td style="border-left:1px solid #ccc"><b>สุทธิ </b></td>
            <td colspan="2" align="right"><b><?php echo number($amount,2); ?></b></td>
          </tr>
  <?php else : ?>
          <tr>
            <td colspan="6" align="center"><h4>ไม่มีรายการสินค้า</h4></td>
          </tr>
  <?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="row">
	<div class="col-sm-12">
    	<p><h4>ข้อความ :  <?php echo $order->comment; ?></h4></p>
    </div>
</div>
