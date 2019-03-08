<?php
$id  = $_GET['id_return_order'];
$ro = new return_order($id);
$customer = new customer($ro->id_customer);
$sale = new sale($customer->id_sale);
$zone = new zone($ro->id_zone);

 ?>

<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่บิล</label>
    <input type="text" class="form-control input-sm text-center" id="txt-bill" placeholder="ค้นหาบิล" value="<?php echo $cs->order_code; ?>" disabled/>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Get</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-get-detail" onclick="getBillDetail()" disabled>ดึงข้อมูล</button>
  </div>
  <div class="col-sm-3 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm" id="txt-customer" value="<?php echo $customer->full_name; ?>" disabled />
  </div>
  <div class="col-sm-2 col-2-harf padding-5">
    <label>พนักงานขาย</label>
    <input type="text" class="form-control input-sm" id="txt-emp" value="<?php echo $sale->full_name; ?>" disabled />
  </div>
  <?php if($edit) : ?>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">btn</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateHeader()">Update</button>
  </div>
  <?php endif; ?>

  <div class="col-sm-7 padding-5 first">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" placeholder="ไม่เกิน 100 ตัวอักษร" id="remark" value="<?php echo $cs->remark; ?>" disabled />
  </div>
  <div class="col-sm-2 padding-5 ">
    <label>บาร์โค้ดโซน</label>
    <input type="text" class="form-control input-sm" id="zoneCode" placeholder="ระบุโซนที่จะรับเข้า" value="<?php echo $zone->barcode_zone; ?>" disabled />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm" id="zoneName" placeholder="ระบุโซนที่จะรับเข้า" value="<?php echo $zone->zone_name; ?>" disabled />
  </div>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-change-zone" onclick="changeZone()">เปลี่ยนโซน</button>
    <button type="button" class="btn btn-sm btn-primary btn-block hide" id="btn-set-zone" onclick="setZone()">บันทึกโซน</button>
  </div>
  <input type="hidden" id="id_return_order" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="id_customer" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="id_zone" value="<?php echo $cs->id_zone; ?>"/>
</div>
<hr />
