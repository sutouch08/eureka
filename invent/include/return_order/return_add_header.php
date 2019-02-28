<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->getNewReference(); ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" id="date_add" value="<?php echo date('d-m-Y'); ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่บิล</label>
    <input type="text" class="form-control input-sm text-center" id="txt-bill" placeholder="ค้นหาบิล" autofocus />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Get</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getBillDetail()">ดึงข้อมูล</button>
  </div>
  <div class="col-sm-3 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm" id="txt-customer" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>พนักงานขาย</label>
    <input type="text" class="form-control input-sm" id="txt-emp" disabled />
  </div>

  <div class="col-sm-7 padding-5 first">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" placeholder="ไม่เกิน 100 ตัวอักษร" id="remark" />
  </div>
  <div class="col-sm-2 padding-5 ">
    <label>บาร์โค้ดโซน</label>
    <input type="text" class="form-control input-sm" id="zoneCode" placeholder="ระบุโซนที่จะรับเข้า" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm" id="zoneName" placeholder="ระบุโซนที่จะรับเข้า" />
  </div>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-change-zone" onclick="changeZone()">เปลี่ยนโซน</button>
  </div>
  <input type="hidden" id="id_return_order" />
  <input type="hidden" id="id_customer" />
  <input type="hidden" id="id_zone" />
</div>
<hr />
