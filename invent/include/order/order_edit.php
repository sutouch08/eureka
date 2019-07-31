<?php
	$id_employee = $_COOKIE['user_id'];
	$id_order = $_GET['id_order'];
	$order = new order($id_order);
	if($order->id_customer != "0") :
		$customer = new customer($order->id_customer);
		$customer->customer_stat();
	endif;
	$sale = new sale($order->id_sale);
	$state = dbQuery("SELECT * FROM tbl_order_state_change WHERE id_order = ".$id_order." ORDER BY date_add DESC, id_order_state_change DESC");
	$role = $order->role;
	$fee = getDeliveryFee($id_order);
	$onlineCustomer = getCustomerOnlineReference($id_order);
	$online = $order->payment == 'ออนไลน์' ? TRUE : FALSE;
?>
<div class="row">
	<div class="col-sm-12">
    	<h5 class="title">
		<?php 	echo $order->reference." - ";  	if($order->id_customer != "0") : echo customer_name($order->id_customer); endif; ?>
        <?php 	if( $online && $onlineCustomer != '') : echo ' ( '.$onlineCustomer.' ) '; endif; ?>
        <p class='pull-right'>พนักงานขาย : &nbsp; <?php echo $sale->full_name; ?></p>
        </h5>
    </div>
</div>
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:5px;' />
<div class="row">
	<div class="col-sm-12">
		<dl><dt>วันที่สั่ง : <dd><?php echo thaiDate($order->date_add); ?></dd> | </dt></dl>
		<dl><dt>สินค้า : <dd><?php echo number_format($order->total_product); ?></dd> | </dt></dl>
		<dl><dt>จำนวน : <dd><?php echo number_format($order->total_qty); ?></dd> | </dt></dl>
		<dl><dt>ยอดเงิน : <dd><?php echo number_format($order->total_amount,2); ?></dd> </dt></dl>

        <p class='pull-right' style="margin-bottom:0px;">
        <?php if( $online ) : ?>
			<?php if( ! $fee ) : ?>
                <input type="text" id="deliveryFee" class="form-control input-sm input-mini" style="display:none;" placeholder="ค่าจัดส่ง" />
                <button type="button" id="btn-add-fee" class="btn btn-sm btn-info" onClick="addDeliveryFee()" ><i class="fa fa-plus"></i> เพิ่มค่าจัดส่ง</button>
                <button type="button" id="btn-update-fee" class="btn btn-sm btn-success" style="display:none;" onClick="updateDeliveryFee(<?php echo $id_order; ?>)" ><i class="fa fa-save"></i> บันทึกค่าส่ง</button>
            <?php else : ?>
                <input type="text" id="deliveryFee" class="form-control input-sm input-mini" style="display:inline; " placeholder="ค่าจัดส่ง" value="<?php echo $fee; ?>" disabled />
                <?php if( $edit OR $add ) : ?>
                <button type="button" id="btn-edit-fee" class="btn btn-sm btn-warning" onClick="editDeliveryFee()" ><i class="fa fa-pencil"></i> แก้ไขค่าจัดส่ง</button>
                <button type="button" id="btn-update-fee" class="btn btn-sm btn-success" style="display:none;" onClick="updateDeliveryFee(<?php echo $id_order; ?>)" ><i class="fa fa-save"></i> บันทึกค่าส่ง</button>
                <?php endif; ?>
            <?php endif; ?>
                <button type="button" class="btn btn-sm btn-primary" onClick="getSummary()"><i class="fa fa-list"></i> ข้อมูลสรุป</button>
        <?php endif; ?>
        <?php if($order->current_state == 5 || $order->current_state == 9 || $order->current_state == 10 || $order->current_state == 11) : ?>
        	<button type="button" class="btn btn-info btn-sm" onclick="check_order(<?php echo $id_order; ?>)"><i class="fa fa-search"></i>&nbsp; ตรวจสอบรายการ</button>
        <?php endif; ?>
				<?php if($order->order_status == 1) : ?>
			<button type="button" class="btn btn-success btn-sm" onclick="print_order(<?php echo $id_order; ?>)"><i class="fa fa-print"></i>&nbsp; พิมพ์</button>
			<?php endif; ?>
        </p>
	</div>
</div><!-- /row -->
<hr style='border-color:#CCC; margin-top: 5px; margin-bottom:15px;' />
<div class='row'>
	<form id='state_change' action='controller/orderController.php?edit&state_change' method='post'>
	<div class='col-sm-6'>
		<table class='table' style='width:100%; padding:10px; border: 1px solid #ccc;'>
        	<tr>
				<td style='width:25%; text-align:right; vertical-align:middle;'>สถานะ :&nbsp; </td>
                <td style='width:40%; padding-right:10px;'>
        			<input type='hidden' name='id_order' id="id_order" value='<?php echo $order->id_order; ?>' />
                    <input type='hidden' name='id_employee' value='<?php echo $id_employee; ?>' />
                    <?php if( $edit ) : ?>
                        <select name='order_state' id='order_state' class='form-control input-sm'>
                            <?php echo orderStateList($order->id_order); ?>
                        </select>
					<?php endif; ?>
                </td>
                <td style='padding-right:10px;'>
                <?php if($edit) : ?>
               	 	<button class='btn btn-default' type='button' onclick='state_change()' $can_edit>เพิ่ม</button>
                <?php endif; ?>
                </td>
            </tr>
<?php	if(dbNumRows($state) > 0 ) :		?>
<?php		while($rd = dbFetchArray($state) ) :	?>
                <tr  style='background-color:<?php echo state_color($rd['id_order_state']); ?>'>
                    <td style='padding-top:10px; padding-bottom:10px; text-align:center; color:#FFF;'><?php echo $order->stateName($rd['id_order_state']); ?></td>
                    <td style='padding-top:10px; padding-bottom:10px; text-align:center; color:#FFF;'><?php echo employee_name($rd['id_employee']); ?></td>
                    <td style='padding-top:10px; padding-bottom:10px; text-align:center; color:#FFF;'><?php echo thaiDateTime($rd['date_add']); ?></td>
                </tr>
<?php		endwhile;		?>
<?php else :	?>
            <tr>
                <td style='padding-top:10px; padding-bottom:10px; text-align:center;'><?php echo $order->currentState(); ?></td>
                <td style='padding-top:10px; padding-bottom:10px; text-align:right;'></td>
                <td style='padding-top:10px; padding-bottom:10px; text-align:center;'><?php echo date('d-m-Y H:i:s', strtotime($order->date_upd)); ?></td>
            </tr>
<?php endif; ?>
 		</table>
 	</div>
    </form>

</div><!-- /row-->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:15px;' />
<form id='editOrderForm'>
<div class='row'>
    <div class='col-sm-12'>
			<?php include 'include/order/order_table.php'; ?>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
    	<p><h4>ข้อความ :  <?php if($order->comment ==""){ echo"ไม่มีข้อความ";}else{ echo $order->comment; } ?></h4></p>
    </div>
</div>
<h4>&nbsp;</h4>
</form>
<!--  สรุปยอดส่ง Line ------>
<div class='modal fade' id='orderSummaryTab' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:300px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
            </div>
            <div class='modal-body' id="summaryText">
            		<?php echo $orderTxt; ?>
            </div>
            <div class='modal-footer'>
                <button class="btn btn-sm btn-info btn-block" data-dismiss='modal' data-clipboard-action="copy" data-clipboard-target="#summaryText">Copy</button>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='ModalLogin' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog ' style='width: 350px;'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' > รหัสลับผู้มีอำนาจการแก้ไขส่วนลด </h4>
			</div>
			<input type='hidden' id='id_employee' name='id_employee' />
			<div class='modal-body'>
				<div class='form-group login-password'>
					<input name='password' id='password' class='form-control input'  size='20' placeholder='รหัสลับ' type='password' required='required' autofocus="autofocus" />
				</div>
				<input id='login' class='btn  btn-block btn-lg btn-primary' value='ตกลง' type='button' onclick='checkPassword()' />
			</div>
			<p style='text-align:center; color:red;' id='message'></p>
			<div class='modal-footer'>
			</div>
		</div>
	</div>
</div>
<script>
$('#ModalLogin').on('shown.bs.modal', function () {  $('#password').focus(); });
$("#password").keyup(function(e) { if(e.keyCode == 13 ){ checkPassword(); }});
</script>

<div class='modal fade' id='modal_approve' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog ' style='width: 350px;'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' > รหัสลับผู้มีอำนาจอนุมัติส่วนลด</h4>
			</div>
			<input type='hidden' id='id_approve' name='id_approve'>
			<div class='modal-body'>
				<div class='form-group login-password'>
					<input name='password' id='bill_password' class='form-control input'  size='20' placeholder='รหัสลับ' type='password' required='required' autofocus="autofocus">
				</div>
				<input class='btn  btn-block btn-lg btn-primary' value='ตกลง' type='button' onclick='valid_password()' />
			</div>
			<p style='text-align:center; color:red;' id='bill_message'></p>
			<div class='modal-footer'>
			</div>
		</div>
	</div>
</div>
<script>
$('#modal_approve').on('shown.bs.modal', function () {  $('#bill_password').focus(); });
</script>

<div class='modal fade' id='modal_approve_edit' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog ' style='width: 350px;'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' > รหัสลับผู้มีอำนาจอนุมัติส่วนลด</h4>
			</div>
			<div class='modal-body'>
				<div class='form-group login-password'>
					<input name='password' id='edit_bill_password' class='form-control input'  size='20' placeholder='รหัสลับ' type='password' required='required' autofocus="autofocus">
				</div>
				<input class='btn  btn-block btn-lg btn-primary' value='ตกลง' type='button' onclick='valid_approve()' >
				<!--userForm-->
			</div>
			<p style='text-align:center; color:red;' id='edit_bill_message'></p>
			<div class='modal-footer'>
			</div>
		</div>
	</div>
</div>
<script>
$('#modal_approve_edit').on('shown.bs.modal', function () {  $('#edit_bill_password').focus(); });
</script>

<div class='modal fade' id='editPriceModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog ' style='width: 350px;'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' >รหัสลับผู้มีอำนาจอนุมัติราคา</h4>
			</div>
			<div class='modal-body'>
            	<div class="row">
                	<div class="col-sm-12"><input type="password" id="confirmPricePassword" class="form-control input-sm text-center" /></div>

                    <div class="col-sm-12 top-col"><button type="button" class="btn btn-primary btn-block" onClick="validConfirmPrice()">ยืนยัน</button></div>
                    <div class="col-sm-12 top-col text-center" style="padding-bottom:20px;"><span id="confirmPrice-error" class="label-left red" style="margin-left:15px; margin-top:15px;">ตัวเลขไม่ถูกต้อง</span></div>
                </div>
			</div>
		</div>
	</div>
</div>
