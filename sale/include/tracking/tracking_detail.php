<div class="row top-row">
  <div class="col-sm-6 col-xs-6 top-col">
    <h4 class="title"><?php echo $page_name; ?></h4>
  </div>
  <div class="col-sm-6 col-xs-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-md btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>
</div>
<hr/>

<?php
$id_employee = $_COOKIE['user_id'];
$id_order = $_GET['id_order'];
$order = new order($id_order);
$customer = new customer($order->id_customer);
$sale = new sale($order->id_employee);
$state = $order->orderState();
$role = $order->role;
?>

<?php include 'include/tracking/order_head.php'; ?>
<?php include 'include/tracking/order_status.php'; ?>
<?php include 'include/tracking/order_table.php'; ?>
