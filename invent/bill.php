<?php
	$page_menu = "invent_order_bill";
	$page_name = "รายการรอเปิดบิล";
	$id_tab = 19;
	$id_profile = $_COOKIE['profile_id'];
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
	accessDeny($view);
	require "function/support_helper.php";
	require "function/sponsor_helper.php";
	require 'function/order_helper.php';
	require 'function/product_helper.php';
	require 'function/qc_helper.php';
	?>
<div class="container">
<!-- page place holder -->
<?php if( ! isset( $_GET['check_order'] ) ) : ?>
<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-file-text"></i>&nbsp;<?php echo $page_name; ?></h4>
  </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
       <?php if( isset( $_GET['view_detail'] ) && isset( $_GET['id_order'] ) ) : ?>
		   <button type='button' class='btn btn-warning btn-sm' onClick="goBack()"><i class="fa fa-arrow-left"></i>&nbsp; กลับ</button>
	   <?php endif; ?>
       </p>
    </div>
</div>
<hr style='border-color:#CCC; margin-top: 5px; margin-bottom:15px;' />
<?php endif; ?>
<!-- End page place holder -->


<?php
	//--------------แสดงรายละเอียด
	if(isset($_GET['view_detail'])&&isset($_GET['id_order']))
	{
		include 'include/bill/bill_detail.php';
	}
	else
	{
		include 'include/bill/bill_list.php';
	}

?>
</div>

<div class="modal fade" id="modal_approve" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog " style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
				<h4 class="modal-title-site text-center" > รหัสลับผู้มีอำนาจอนุมัติส่วนลด</h4>
			</div>
			<input type="hidden" id="id_employee" name="id_employee" value="<?php echo $_COOKIE['user_id']; ?>"  />
			<div class="modal-body">
				<div class="form-group login-password">
					<input name="password" id="bill_password" class="form-control input"  size="20" placeholder="รหัสลับ" type="password" required="required" autofocus="autofocus">
				</div>
				<input class="btn  btn-block btn-lg btn-primary" value="ตกลง" type="button" onclick="valid_password()" >
			</div>
			<p style="text-align:center; color:red;" id="bill_message"></p>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <input type="hidden" id="id_customer"/><input type="hidden" id="id_order" />
		  </div>
			<div class="modal-body" id="info_body"></div>
			<div class="modal-footer">
       	<button type="button" class="btn btn-primary btn-sm" onClick="printSelectAddress()"><i class="fa fa-print"></i> พิมพ์</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:350px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" id="detailBody"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
      </div>
      <div class="modal-body" id="imageBody"></div>
      <div class="modal-footer"></div>
  	</div>
  </div>
</div>

<script>
	$('#modal_approve').on('shown.bs.modal', function () {  $('#bill_password').focus(); });
</script>

<script id="detailTemplate" type="text/x-handlebars-template">
	<div class="row">
		<div class="col-sm-12 text-center">ข้อมูลการชำระเงิน</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-sm-4 label-left">ยอดที่ต้องชำระ :</div><div class="col-sm-8">{{ orderAmount }}</div>
		<div class="col-sm-4 label-left">ยอดโอนชำระ : </div><div class="col-sm-8"><span style="font-weight:bold; color:#E9573F;">฿ {{ payAmount }}</span></div>
		<div class="col-sm-4 label-left">วันที่โอน : </div><div class="col-sm-8">{{ payDate }}</div>
		<div class="col-sm-4 label-left">ธนาคาร : </div><div class="col-sm-8">{{ bankName }}</div>
		<div class="col-sm-4 label-left">สาขา : </div><div class="col-sm-8">{{ branch }}</div>
		<div class="col-sm-4 label-left">เลขที่บัญชี : </div><div class="col-sm-8"><span style="font-weight:bold; color:#E9573F;">{{ accNo }}</span></div>
		<div class="col-sm-4 label-left">ชื่อบัญชี : </div><div class="col-sm-8">{{ accName }}</div>
		{{#if imageUrl}}
			<div class="col-sm-12 top-row top-col text-center"><a href="javascript:void(0)" onClick="viewImage('{{ imageUrl }}')">รูปสลิปแนบ <i class="fa fa-paperclip fa-rotate-90"></i></a> </div>
		{{else}}
			<div class="col-sm-12 top-row top-col text-center">---  ไม่พบไฟล์แนบ  ---</div>
		{{/if}}
	</div>
</script>
<script>
function viewImage(imageUrl)
{
	var image = '<img src="'+imageUrl+'" width="100%" />';
	$("#imageBody").html(image);
	$("#imageModal").modal('show');
}

function viewPaymentDetail(id_order)
{
	load_in();
	$.ajax({
		url:"controller/paymentController.php?viewPaymentDetail",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('ข้อผิดพลาด', 'ไม่พบข้อมูล', 'error');
			}else{
				var source 	= $("#detailTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#detailBody");
				render(source, data, output);
				$("#confirmModal").modal('show');
			}
		}
	});
}
function process_bill_discount() /// inspected discount value
{
    var discount = $("#bill_discount").val();
	if( isNaN(parseFloat(discount)) )
	{
		load_out();
		swal("รูปแบบตัวเลขส่วนลดไม่ถูกต้อง");
		return false;
	}else{
		$("#modal_approve").modal("show");
	}
}

/*function save_iv(){
	load_in();
	$("#iv_button").attr("disabled","disabled");
	var id_order = $("#id_order").val();
	var id_employee = $("#id_employee").val();
	window.location.href = "controller/billController.php?confirm_order&id_order="+id_order+"&id_employee="+id_employee;
}*/

function save_iv(){
	var id_order = $("#id_order").val();
	var id_employee = $("#id_employee").val();
	load_in();
	$.ajax({
		url:"controller/billController.php?confirm_order&id_order="+id_order+"&id_employee="+id_employee,
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: 'สำเร็จ', text: 'บันทึกเอกสารเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal('ข้อผิดพลาด !', rs, 'error');
			}
		}
	});
}

function valid_password(){
	$("#loader").css("z-index","1100");
	load_in();
	var password = $("#bill_password").val();
	$.ajax({
		url:"controller/orderController.php?check_password&password="+password,
		type:"GET", cache:false,
		success: function(data){
			if(data == "0"){
				load_out();
				$("#bill_message").html("รหัสลับไม่ถูกต้องกรุณาตรวจสอบ");
				$("#bill_password").val("");
			}else{
				update_bill_discount(data);
			}
		}
	});
}

function checkBill()
{
	var id_order =  $("#id_order").val();
	$.ajax({
		url:'controller/billController.php?check_order_state&id_order='+id_order,
		type:'GET', cache:false,
		success: function(rs){
			if(rs != 10){
				$('#p_btn').css('display', 'none');
			}
		}
	});
}

function stopInt() { clearInterval(interv); }

function update_bill_discount(id_approve)
{
	var id_order = <?php if(isset($_GET['id_order'])){ echo $_GET['id_order']; }else{ echo "0"; } ?>;
	var discount = $("#bill_discount").val();
	$.ajax({
		url:"controller/orderController.php?insert_bill_discount", type:"POST", cache:false,
		data: { "id_order" : id_order, "id_approve" : id_approve, "discount" : discount },
		success: function(rs){
			var rs = $.trim(rs);
			load_out();
			if(rs == "success")
			{
				window.location.reload();
				load_out();
			}else{
				load_out();
				$("#modal_approve").modal("hide");
				swal("แก้ไขส่วนลดไม่สำเร็จ");
			}
		}

	});
}

function printAddress(id_order, id_customer)
{
	if( $("#online").length ){
		getOnlineAddress(id_order);
	}else{
		getAddressForm(id_order, id_customer);
	}
}

function getOnlineAddress(id_order)
{
	$.ajax({
		url:"controller/orderController.php?getOnlineAddress",
		type:"POST", cache:"false", data:{"id_order" : id_order },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'noaddress' || isNaN( parseInt(rs) ) ){
				noAddress();
			}else{
				printOnlineAddress(id_order, rs);
			}
		}
	});
}
function getAddressForm(id_order, id_customer)
{
	$.ajax({
		url:"controller/addressController.php?getAddressForm",
		type:"POST",cache: "false", data:{ "id_order" : id_order, "id_customer" : id_customer },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'no_address' ){
				noAddress();
			}else if( rs == 'no_sender' ){
				noSender();
			}else if( rs == 1 ){
				printPackingSheet(id_order, id_customer);
			}else{
				$("#id_customer").val(id_customer);
				$("#id_order").val(id_order);
				$("#info_body").html(rs);
				$("#infoModal").modal("show");
			}
		}
	});
}

function printPackingSheet(id_order, id_customer)
{
	var center = ($(document).width() - 800)/2;
	window.open("controller/addressController.php?printAddressSheet&id_order="+id_order+"&id_customer="+id_customer, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}

function printOnlineAddress(id_order, id_address)
{
	var center = ($(document).width() - 800)/2;
	window.open("controller/addressController.php?printOnlineAddressSheet&id_order="+id_order+"&id_address="+id_address, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}

function printSelectAddress()
{
	var id_order = $("#id_order").val();
	var id_cus = $("#id_customer").val();
	var id_ad =	$('input[name=id_address]:radio:checked').val();
	var id_sen	= $('input[name=id_sender]:radio:checked').val();
	if( isNaN(parseInt(id_ad)) ){ swal("กรุณาเลือกที่อยู่", "", "warning"); return false; }
	if( isNaN(parseInt(id_sen)) ){ swal("กรุณาเลือกขนส่ง", "", "warning"); return false; }
	$("#infoModal").modal('hide');
	var center = ($(document).width() - 800)/2;
	window.open("controller/addressController.php?printAddressSheet&id_order="+id_order+"&id_customer="+id_cus+"&id_address="+id_ad+"&id_sender="+id_sen, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}
function noAddress()
{
	swal("ข้อผิดพลาด", "ไม่พบที่อยู่ของลูกค้า กรุณาตรวจสอบว่าลูกค้ามีที่อยู่ในระบบแล้วหรือยัง", "warning");
}
function noSender()
{
	swal("ไม่พบผู้จัดส่ง", "ไม่พบรายชื่อผู้จัดส่ง กรุณาตรวจสอบว่าลูกค้ามีการกำหนดชื่อผู้จัดส่งในระบบแล้วหรือยัง", "warning");
}


function printOrder(id)
{
	var wid = $(document).width();
	var left = (wid - 900) /2;
	window.open("controller/billController.php?print_order&id_order="+id, "_blank", "width=900, height=1000, left="+left+", location=no, scrollbars=yes");
}

function printBarcode(id_order)
{
	var center = ($(document).width() - 800)/2;
	window.open("controller/billController.php?print_order_barcode&id_order="+id_order, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}

function printPackingList(id_order)
{
	window.open("index.php?content=order_closed&print_packing_list&id_order="+id_order, "_blank");
}

function goBack()
{
	window.location.href = "index.php?content=bill";
}

</script>
