<div class="row">
  <div class="col-sm-4 col-xs-12">
    <h4 class="title">
      <?php echo $order->reference; ?>
      <?php if($order->id_customer != 0) : ?>
      <?php   echo ' - '.$customer->full_name; ?>
      <?php endif; ?>
    </h4>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label>วันที่สั่ง : <?php echo thaiDate($order->date_add); ?></label>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label>สินค้า : <?php echo number_format($order->total_product); ?></label>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label>จำนวน : <?php echo number_format($order->total_qty); ?></label>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label>ยอดเงิน : <?php echo number_format($order->total_amount,2); ?></label>
  </div>
</div>

<hr/>
