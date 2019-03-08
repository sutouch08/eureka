<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog " style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
				<h4 class="modal-title-site text-center" >รับสินค้าเกินใบสั่งซื้อ</h4>
			</div>
			<input type="hidden" id="id_approver" value="0">
			<div class="modal-body">
				<div class="row">
          <div class="col-sm-12">
            <label>รหัสลับผู้มีอำนาจอนุมัติ</label>
            <input type="password" id="pwd-box" class="form-control input-sm text-center" />
          </div>
          <div class="col-sm-12">
            <p class="text-center red hide" id="message">รหัสไม่ถูกต้อง</p>
          </div>
        </div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
