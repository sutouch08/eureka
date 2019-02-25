<?php
$id  = $_GET['id_return_order'];
$ro = new return_order($id);
$cs = new customer();
$qs = $ro->getDetails($id);
?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
      <button type="button" class="btn btn-sm btn-success" onclick="printDetail(<?php echo $id; ?>)"><i class="fa fa-print"></i> พิมพ์</button>
    </p>
  </div>
</div>
<hr/>
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($ro->date_add); ?>" readonly />
  </div>
  <div class="col-sm-2 padding-5">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $ro->reference; ?>" readonly />
  </div>
  <div class="col-sm-2 padding-5">
    <label>เลขที่บิล</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $ro->order_code; ?>" readonly />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm" value="<?php echo $cs->getFullName($ro->id_customer); ?>" readonly />
  </div>
  <div class="col-sm-4 col-4-harf padding-5 last">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" value="<?php echo $ro->remark; ?>" readonly />
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
          <th class="width-25">สินค้า</th>
          <th class="width-10 text-right">จำนวน</th>
          <th class="width-10 text-right">ราคา</th>
          <th class="width-10 text-right">มูลค่า</th>
        </tr>
      </thead>
      <tbody>
<?php if(dbNumRows($qs) > 0 ) : ?>
<?php   $no = 1; ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
        <tr>
          <td class="text-center"><?php echo $no; ?></td>
          <td><?php echo $rs->product_code; ?></td>
          <td class="text-right"><?php echo number($rs->qty); ?></td>
          <td class="text-right"><?php echo number($rs->price, 2); ?></td>
          <td class="text-right"><?php echo number($rs->amount, 2); ?></td>
        </tr>
<?php     $no++; ?>
<?php   endwhile; ?>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
