<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" id="date_add" value="<?php echo date('d-m-Y'); ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่บิล</label>
    <input type="text" class="form-control input-sm text-center" id="txt-bill" placeholder="ค้นหาบิล" autofocus />
  </div>
  <div class="col-sm-3 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm" id="txt-customer" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>พนักงานขาย</label>
    <input type="text" class="form-control input-sm" id="txt-emp" disabled />
  </div>

  <div class="col-sm-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" placeholder="ไม่เกิน 100 ตัวอักษร" id="remark" />
  </div>

<hr />
