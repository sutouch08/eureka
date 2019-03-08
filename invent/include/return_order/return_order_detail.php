<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
      <button type="button" class="btn btn-sm btn-success" onclick="printDetail(<?php echo $_GET['id_return_order']; ?>)"><i class="fa fa-print"></i> พิมพ์</button>
    </p>
  </div>
</div>
<hr/>
<?php include 'include/return_order/return_view_detail.php'; ?>
