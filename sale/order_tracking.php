<?php
	$page_menu = "invent_order";
	$page_name = "ติดตามออเดอร์";
	$id_profile = $_COOKIE['profile_id'];
	if(!sale_access($_COOKIE['user_id']))
	{
		echo accessDeny();
		exit;
	}

	include '../invent/function/order_helper.php';
	?>

<div class="container">
<!-- page place holder -->
<?php
if(isset($_GET['view_detail']))
{
		include 'include/tracking/tracking_detail.php';
}
else
{
	include 'include/tracking/tracking_list.php';
}
?>

</div>
<script>
$('#fromDate').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd){
		$('#toDate').datepicker('option', 'minDate', sd);
	}
});

$('#toDate').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd){
		$('#fromDate').datepicker('option', 'maxDate', sd);
	}
});


function getSearch(){
	var sCode = $('#sCode').val();
	var sName = $('#sName').val();
	var from  = $('#fromDate').val();
	var to    = $('#toDate').val();

	if(sCode.length > 0 || sName.length > 0 || from.length > 0 || to.length > 0){
		if(from.length > 0 && !isDate(from) || to.length > 0 && !isDate(to))
		{
			swal('วันที่ไม่ถูกต้อง');
			return false;
		}

		$('#searchForm').submit();
	}
}



function goBack(){
	window.location.href = 'index.php?content=tracking';
}


function clearFilter(){
	$.get('controller/orderController.php?clearFilter&tracking', function(){
		goBack();
	});
}


$(document).ready(function(e) {
    if($("#error").length){
		alert($("#error").text());
	}
});


function viewDetail(id){
	window.location.href = 'index.php?content=tracking&view_detail&id_order='+id;
}

$("#get_info").click(function(e) {
	var cus_name = $("#customer_name").val();
	var cus_id = $("#id_customer").val();
	var arr = cus_name.split(":");
	 if(cus_name == ""){
		alert("ยังไม่ได้เลือกลูกค้า");
	}else if(cus_id ==""){
		alert("ระบบไม่พบ Customer ID ไม่สามารถเพิ่มออเดอร์ได้กรุณาเลือกลูกค้าใหม่หรือติดต่อผู้ดูแลระบบ");
	}else{
		$.ajax({
			url:"controller/customerController.php?get_info&id_customer="+cus_id,
			type:"GET", cache:false, success: function(data){
				$(".modal-title").text("ข้อมูล : "+arr[1]);
				$(".modal-body").html(data);
				$("#info").click();
			}
		});
	}
});


function get_row(){
	$("#rows").submit();
}
</script>
