
<div class="container">
  <?php if($edit) : ?>
  <?php
  $rd = new receive_product($_GET['id_receive_product']);
  $sup = new supplier($rd->id_supplier);
  $zone = new zone($rd->id_zone);
  $po = new po();

  $qs = $rd->getDetails($rd->id_receive_product);

  ?>
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        <?php if($rd->status == 0) : ?>
          <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="saveEdit()">
            <i class="fa fa-save"></i> บันทึก</button>
        <?php else : ?>
          <button type="button" class="btn btn-sm btn-danger" id="btn-unsave" onclick="unSave()">ยกเลิกการบันทึก</button>
        <?php endif; ?>
      </p>
    </div>
  </div>
  <hr/>

  <div class="row">
    <div class="col-sm-2 padding-5 first">
      <label class="display-block">เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $rd->reference; ?>" disabled />
    </div>
    <div class="col-sm-1 padding-5">
      <label>วันที่</label>
      <input type="text" class="form-control input-sm text-center input-box" id="date_add" value="<?php echo thaiDate($rd->date_add); ?>" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label>รหัสผู้จำหน่าย</label>
      <input type="text" class="form-control input-sm text-center input-box" id="supCode" value="<?php echo $sup->code; ?>" disabled />
    </div>
    <div class="col-sm-4 padding-5">
      <label>ชื่อผู้จำหน่าย</label>
      <input type="text" class="form-control input-sm input-box" id="supName" value="<?php echo $sup->name; ?>" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">ใบสั่งซื้อ</label>
      <input type="text" class="form-control input-sm text-center input-box" id="po" value="<?php echo $rd->po_reference; ?>" disabled />
    </div>
    <div class="col-sm-2 padding-5 last">
      <label class="display-block">ใบส่งสินค้า</label>
      <input type="text" class="form-control input-sm text-center input-box" id="invoice" value="<?php echo $rd->invoice; ?>" disabled />
    </div>

    <div class="col-sm-2 padding-5 first">
      <label>รหัสโซน</label>
      <input type="text" class="form-control input-sm input-box" id="zone-code" placeholder="ระบุโซนที่จะรับเข้า" value="<?php echo $zone->barcode_zone; ?>"  disabled />
    </div>
    <div class="col-sm-3 padding-5">
      <label>ชื่อโซน</label>
      <input type="text" class="form-control input-sm input-box" id="zone-box" placeholder="ระบุโซนที่จะรับเข้า" value="<?php echo $zone->name; ?>" disabled/>
    </div>

    <div class="col-sm-6 padding-5">
      <label class="display-block">หมายเหตุ</label>
      <input type="text" class="form-control input-sm input-box" id="remark" value="<?php echo $rd->remark; ?>" disabled />
    </div>

    <?php if($rd->status == 0) : ?>
    <div class="col-sm-1 padding-5 last">
      <label class="display-block not-show">แก้ไข</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
      <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-save-edit" onclick="updateReceiveProduct()"><i class="fa fa-save"></i> Update</button>
    </div>
    <?php endif; ?>

    <input type="hidden" id="id_receive_product" value="<?php echo $rd->id_receive_product; ?>" />
    <input type="hidden" id="id_po" value="<?php echo $rd->id_po; ?>" />
    <input type="hidden" id="id_supplier" value="<?php echo $rd->id_supplier; ?>" />
    <input type="hidden" id="id_zone" value="<?php echo $zone->id_zone; ?>" />
  </div><!--/row-->

  <hr/>

  <?php
  if($rd->status == 0)
  {
    include('include/receive_product/receive_control.php');
  }
  ?>

  <hr class="margin-top-15" />
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped table-bordered">
        <thead>
          <th class="width-5 text-center">No.</th>
          <th class="width-15 text-center">รหัส</th>
          <th class="text-center">สินค้า</th>
          <th class="width-10 text-center">จำนวน</th>
          <th class="width-5 text-center">Actions</th>
        </thead>
        <tbody id="result">
          <?php  if(dbNumRows($qs) > 0) : ?>
            <?php $no = 1; ?>
            <?php $totalQty = 0; ?>
            <?php while($rs = dbFetchObject($qs)) : ?>
              <?php $id_pa = $rs->id_product_attribute; ?>
              <?php $id_pd = $rs->id_product; ?>
              <?php $status = isset($rs->status) ? $rs->status : 0; ?>
            <tr id="row-<?php echo $id_pa; ?>" class="item-row">
              <td class="text-center middle no"><?php echo $no; ?></td>
              <td class="middle"><?php echo $rs->reference; ?></td>
              <td class="middle"><?php echo $rs->product_name; ?></td>
              <td class="middle text-center qty"><?php echo number($rs->qty); ?></td>
              <td class="middle text-center">
                <?php if($rs->status == 0) : ?>
                  <button type="button" class="btn btn-sm btn-danger" id="btn-remove-<?php echo $id_pa; ?>" onclick="removeItem('<?php echo $id_pa; ?>')">
                    <i class="fa fa-trash"></i>
                  </button>
                <?php endif; ?>
                <?php if($rs->status == 1) : ?>
                  <?php echo isActived(1); ?>
                <?php endif; ?>
              </td>
            </tr>
            <?php $totalQty += $rs->qty; ?>
            <?php $no++; ?>
            <?php endwhile; ?>
            <tr>
              <td colspan="3" class="middle text-right"><strong>รวม</strong></td>
              <td class="middle text-center"><strong id="total"><?php echo number($totalQty); ?></strong></td>
              <td></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php else : ?>
    <?php include('include/page_error.php'); ?>
  <?php endif; ?>
</div><!--/ container -->

<?php include 'include/receive_product/receive_template.php'; ?>


<script src="script/receive_product/receive_add.js?token=<?php echo date('YmdH'); ?>"></script>
<script src="script/receive_product/receive_edit.js?token=<?php echo date('YmdH'); ?>"></script>
<script src="script/receive_product/receive_control.js?token=<?php echo date('YmdH'); ?>"></script>
<script src="script/receive_product/receive_zone.js?token=<?php echo date('YmdH'); ?>"></script>
<script src="script/receive_product/receive_supplier.js?token=<?php echo date('YmdH'); ?>"></script>
<script src="script/receive_product/receive_po.js?token=<?php echo date('YmdH'); ?>"></script>
