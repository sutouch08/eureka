<!-- ดูยอดสต็อกคงเหลือนำยอดที่สั่งมาคำนวนแล้ว --------------------------------->
<!--- Category Menu ---------------------------------->
<div class="row">
	<div class="col-sm-12">
		<ul class="nav navbar-nav" role="tablist" style="background-color:#EEE">
			<?php echo categoryTabMenu('view'); ?>
		</ul>
	</div><!---/ col-sm-12 ---->
</div><!---/ row -->
<hr style="border-color:#CCC; margin-top: 0px; margin-bottom:0px;" />
<div class="row">
	<div class="col-sm-12">
		<div class="tab-content" style="min-height:1px; padding:0px;">
		<?php echo getCategoryTab('view'); ?>
		</div>
	</div>
</div>
<!--- End Category Menu ------------------------------------>
<div class="modal fade" id="order_grid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal_title"></h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
            </div>
            <div class="modal-body" id="modal_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
