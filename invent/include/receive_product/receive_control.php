
<div class="row">

  <div class="col-sm-3 padding-5 first">
    <label>รหัสสินค้า</label>
    <input type="text" class="form-control input-sm" id="pdCode" placeholder="ค้นหารหัสสินค้า" />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-get-pd" onclick="get_product()">แสดงรายการ</button>
  </div>
  <div class="col-sm-4">

  </div>
  <div class="col-sm-2">
    <?php if($rd->id_po != 0) : ?>
      <label class="display-block not-show">get po</label>
      <button type="button" class="btn btn-sm btn-info btn-block" onclick="getPO()">ดึงใบสั่งซื้อ</button>
    <?php endif; ?>
  </div>
  <div class="col-sm-2 padding-5 last">
    <label class="display-block not-show">clear all</label>
    <button type="button" class="btn btn-sm btn-danger btn-block" onclick="clearAll()">ลบรายการทั้งหมด</button>
  </div>
</div>



	<div class="modal fade" id="productGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" id="modal">
			<div class="modal-content">
	  		<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<center style="margin-bottom:10px;"><h4 class="modal-title" id="modal_title">title</h4></center>
          <input type="hidden" name="id_product" id="id_product" />
				</div>
				<div class="modal-body" id="modal_body">
          <div id="modal_body"></div>
        </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id="btn_close" data-dismiss="modal">ปิด</button>
					<button type="button" class="btn btn-primary" onclick="insert_item()">เพิ่มในรายการ</button>
				 </div>
			</div>
		</div>
	</div>



  <div class="modal fade" id="poGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <center style="margin-bottom:10px;"><h4 class="modal-title" id="po-title">title</h4></center>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered">
            <thead>
              <th class="width-10 text-center">No.</th>
              <th class="width-20 text-center">รหัส</th>
              <th class="text-center">สินค้า</th>
              <th class="width-10 text-center">ค้างรับ</th>
              <th class="width-10 text-center">จำนวน</th>
            </thead>
            <tbody id="po-body">

            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="btn_close" data-dismiss="modal">ปิด</button>
          <button type="button" class="btn btn-primary" onclick="insertPoItems()">เพิ่มในรายการ</button>
         </div>
      </div>
    </div>
  </div>
