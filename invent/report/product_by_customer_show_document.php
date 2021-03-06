<?php 
	$page_name = "รายงานสินค้า แยกตามลูกค้า แสดงเลขที่เอกสาร";
	$id_profile = $_COOKIE['profile_id'];
	?>
<div class="container">
<!-- page place holder -->
<form name='report_form' id='report_form' action='' method='post'>
<div class="row" style="height:30px;">
	<div class="col-sm-8" style="margin-top:10px;"><h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $page_name; ?></h4></div>
    <div class="col-sm-4">
    	<p class="pull-right" style="margin-bottom:0px;">
        	<button type="button" class="btn btn-primary btn-sm" onClick="get_report()"><i class="fa fa-file-text"></i>&nbsp;รายงาน</button>
            <button type="button" class="btn btn-success btn-sm" onClick="do_export()"><i class="fa fa-file-excel-o"></i>&nbsp;ส่งออก</button>
        </p>
     </div>
</div>
<hr />
<!-- End page place holder -->
<div class="row">
	<div class="col-lg-2" >
    	<label style="display:block;">ลูกค้า</label>
        <div class="btn-group" style="width:100%;">
        	<button type="button" class="btn btn-sm btn-primary" id="btn_cus_all" onClick="cus_all()" style="width:50%;">ทั้งหมด</button>
            <button type="button" class="btn btn-sm" id="btn_cus_once" onClick="cus_once()" style="width:50%;">เฉพาะ</button>
        </div>
    </div>
    <div class="col-lg-3" >
    	<label style="visibility:hidden">cus</label>
        <input type="text" class="form-control input-sm" name="cus" id="cus" placeholder="ค้นหาลูกค้า" disabled />
    </div>
    <div class="col-lg-3">
    	<label style="display:block;">สินค้า</label>
        <div class="btn-group" style="width:100%;">
        	<button type="button" class="btn btn-sm btn-primary" id="btn_item_all" onClick="item_all()" style="width:33%;">ทั้งหมด</button>
            <button type="button" class="btn btn-sm" id="btn_product" onClick="item_product()" style="width:33%;">เฉพาะรุ่น</button>
            <button type="button" class="btn btn-sm" id="btn_item_once" onClick="item_once()" style="width:34%;">เฉพาะรายการ</button>
        </div>
    </div>
    <div class="col-lg-3" >
    	<label style="visibility:hidden">items</label>
        <input type="text" class="form-control input-sm" name="item" id="item" placeholder="ค้นหาสินค้า" disabled />
        <input type="text" class="form-control input-sm" name="product" id="product" placeholder="ค้นหาสินค้า" style="display:none;" disabled />
    </div>
   <div class="col-lg-12">&nbsp;</div>
   <div class="col-lg-4">
   		<label style="display:block;">รูปแบบเอกสาร</label>
        <div class="btn-group" style="width:100%;">
        	<button type="button" class="btn btn-sm btn-primary" id="btn_sale" onClick="sale()" style="width:25%;">ขาย</button>
            <button type="button" class="btn btn-sm" id="btn_consign" onClick="consign()" style="width:25%;">ฝากขาย</button>
            <button type="button" class="btn btn-sm" id="btn_sponsor" onClick="sponsor()" style="width:25%;">สปอนเซอร์</button>
            <button type="button" class="btn btn-sm" id="btn_all" onClick="role_all()" style="width:25%;">ทั้งหมด</button>
        </div>
    </div>
    <div class="col-lg-2" >
    	<label>วันที่</label>
        <input type="text" class="form-control input-sm" name="from_date" id="from_date" placeholder="เริ่มต้น" />
    </div>
    <div class="col-lg-2" >
    	<label style="visibility:hidden">วันที่</label>
        <input type="text" class="form-control input-sm" name="to_date" id="to_date" placeholder="สิ้นสุด" />
    </div>
    <input type="hidden" name="cus_range" id="cus_range" value="0" />
    <input type="hidden" name="item_range" id="item_range" value="0" />
    <input type="hidden" name="id_customer" id="id_customer" value="" />
    <input type="hidden" name="id_item" id="id_item" value="" />
    <input type="hidden" name="id_product" id="id_product" value="" />
    <input type="hidden" name="role" id="role" value="1" />
</div>
</form>
<hr />
<div class="row"><div class="col-lg-12" id="rs"></div></div>

<script id="report_template" type="text/x-handlebars-template">
<table class="table table-striped">
	<thead style="font-size:12px;">
    	<th style="width:5%; text-align:center;">ลำดับ</th>
        <th style="width:10%;">วันที่</th>
		<th style="width:20%;">ลูกค้า</th>
		<th style="width:35%;">สินค้า</th>
        <th style="width:5%; text-align:center;">จำนวน</th>
        <th style="width:10%; text-align:right;">มูลค่า</th>
        <th style="width:15%; text-align:center;">เลขที่เอกสาร</th>  
    </thead>
    {{#each this}}
    	{{#if nocontent}}
        	<tr>
            	<td align="center" colspan="7"><h4>-----  ไม่พบข้อมูล  -----</h4></td>
            </tr>
        {{else}}
        	{{#if last}}
            	<tr>
                	<td colspan="4" align="right"><strong>รวม</strong></td>
                    <td align="center">{{ total_qty }}</td>
                    <td align="right">{{ total_amount }}</td>
					<td align="right"></td>
                </tr>
            {{else}}
            	<tr style="font-size:12px;">
                	<td align="center">{{ no }}</td>
                    <td>{{ date }}</td>
					<td>{{ customer }}</td>
                    <td>{{ item }}</td>
                    <td align="center">{{ qty }}</td>
                    <td align="right">{{ amount }}</td>
                    <td align="center">{{ reference }}</td> 
                </tr>
            {{/if}}
        {{/if}}    
    {{/each}}
</table>
</script>
</div><!--- container -->
<script>
function get_report()
{
	var cus_range		= $("#cus_range").val();
	var item_range		= $("#item_range").val();
	var cus				= $("#cus").val();
	var item				= $("#item").val();
	var product			= $("#product").val();
	var id_cus			= $("#id_customer").val();
	var id_item			= 	$("#id_item").val();
	var id_product		= $("#id_product").val();
	var role				= $("#role").val();
	var from_date		= $("#from_date").val();
	var to_date			= $("#to_date").val();
	
	if( cus_range == 1 && (id_cus =='' || cus == '')){ swal("กรุณาระบุชื่อลูกค้า"); return false; }
	if( item_range == 1 && (id_item =='' || item == "")){ swal("กรุณาระบุรหัสสินค้า"); return false; }
	if( item_range == 2 && (id_product == '' || product == '')){ swal("กรุณาระบุรหัสสินค้า"); return false; }
	if( !isDate(from_date) || !isDate(to_date) ){ swal("วันที่ไม่ถูกต้อง"); return false; }
	
	load_in();
	$.ajax({
		url:"report/reportController/verifyReportController.php?pdbcd&report",
		type:"POST", cache:"false", data:{ "cus_range" : cus_range, "item_range" : item_range, "id_customer" : id_cus, "id_product_attribute" : id_item, "id_product" : id_product, "from_date" : from_date, "to_date" : to_date, "role" : role },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			var source 	= $("#report_template").html();
			var data		= $.parseJSON(rs);
			var output	= $("#rs");
			render(source, data, output);
		}
	});
}

function do_export()
{
	var cus_range		= $("#cus_range").val();
	var item_range		= $("#item_range").val();
	var cus				= $("#cus").val();
	var item				= $("#item").val();
	var product			= $("#product").val();
	var id_cus			= $("#id_customer").val();
	var id_item			= 	$("#id_item").val();
	var id_product		= $("#id_product").val();
	var role				= $("#role").val();
	var from_date		= $("#from_date").val();
	var to_date			= $("#to_date").val();
	var token 			= new Date().getTime();
	
	if( cus_range == 1 && (id_cus =='' || cus == '')){ swal("กรุณาระบุชื่อลูกค้า"); return false; }
	if( item_range == 1 && (id_item =='' || item == "")){ swal("กรุณาระบุรหัสสินค้า"); return false; }
	if( item_range == 2 && (id_product == '' || product == '')){ swal("กรุณาระบุรหัสสินค้า"); return false; }
	if( !isDate(from_date) || !isDate(to_date) ){ swal("วันที่ไม่ถูกต้อง"); return false; }
	
	var target = "report/reportController/verifyReportController.php?pdbcd&export&token="+token;
	$("#report_form").attr("action", target);
	get_download(token);
	$("#report_form").submit();
}

function role_all()
{
	$("#role").val(0);
	$("#btn_sale").removeClass("btn-primary");
	$("#btn_consign").removeClass("btn-primary");
	$("#btn_sponsor").removeClass("btn-primary");
	$("#btn_all").addClass("btn-primary");
}

function sale()
{
	$("#role").val(1);
	$("#btn_all").removeClass("btn-primary");
	$("#btn_consign").removeClass("btn-primary");
	$("#btn_sponsor").removeClass("btn-primary");
	$("#btn_sale").addClass("btn-primary");
}

function consign()
{
	$("#role").val(5);
	$("#btn_all").removeClass("btn-primary");
	$("#btn_sale").removeClass("btn-primary");
	$("#btn_sponsor").removeClass("btn-primary");
	$("#btn_consign").addClass("btn-primary");
}

function sponsor()
{
	$("#role").val(4);
	$("#btn_all").removeClass("btn-primary");
	$("#btn_sale").removeClass("btn-primary");
	$("#btn_consign").removeClass("btn-primary");
	$("#btn_sponsor").addClass("btn-primary");
}
function cus_all()
{
	$("#cus_range").val(0);
	$("#cus").val("");
	$("#id_employee").val('');
	$("#cus").attr("disabled", "disabled");
	$("#btn_cus_once").removeClass("btn-primary");
	$("#btn_cus_all").addClass("btn-primary");	
}
function cus_once()
{
	$("#cus_range").val(1);
	$("#cus").removeAttr("disabled");
	$("#btn_cus_all").removeClass("btn-primary");
	$("#btn_cus_once").addClass("btn-primary");	
	$("#cus").focus();
}
function item_all()
{
	$("#item_range").val(0);
	$("#item").val('');
	$("#id_item").val('');
	$("#product").val('');
	$("#id_product").val('');
	$("#item").attr("disabled", "disabled");
	$("#product").attr("disabled", "disabled");
	$("#btn_item_once").removeClass("btn-primary");
	$("#btn_product").removeClass("btn-primary");
	$("#btn_item_all").addClass("btn-primary");	
}

function item_product()
{
	$("#item_range").val(2);
	$("#id_tem").val('');
	$("#item").val('');
	$("#item").attr("disabled", "disabled");
	$("#item").css("display", "none");
	$("#product").removeAttr("disabled");
	$("#product").css("display","");
	$("#btn_item_all").removeClass("btn-primary");
	$("#btn_item_once").removeClass("btn-primary");
	$("#btn_product").addClass("btn-primary");
	$("#product").focus();
}

function item_once()
{
	$("#item_range").val(1);
	$("#id_product").val('');
	$("#product").val('');
	$("#product").attr("disabled", "disabled");
	$("#product").css("display", "none");
	$("#item").removeAttr("disabled");
	$("#item").css("display", "");
	$("#btn_item_all").removeClass("btn-primary");
	$("#btn_product").removeClass("btn-primary");
	$("#btn_item_once").addClass("btn-primary");
	$("#item").focus();	
}

$("#cus").autocomplete({
	source: "controller/autoComplete.php?get_customer",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var ar = rs.split(" | ");
		var id = ar[2];
		$(this).val(ar[1]);
		$("#id_customer").val(id);
	}
});

$("#item").autocomplete({
	source: "controller/autoComplete.php?get_product_attribute",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var ar = rs.split(" | ");
		var id = ar[1];
		$(this).val(ar[0]);
		$("#id_item").val(id);
	}
});

$("#product").autocomplete({
	source: "controller/autoComplete.php?get_product_id",
	sutoFocus: true,
	close: function(){
		var rs = $(this).val();
		var ar = rs.split(" : ");
		var id = ar[2];
		var code = ar[0];
		$("#id_product").val(id);
		$(this).val(code);
	}
});

$("#from_date").datepicker({
	dateFormat: "dd-mm-yy",
	onClose: function(selectedDate){
		$("#to_date").datepicker("option", "minDate", selectedDate);
	}
});
$("#to_date").datepicker({
	dateFormat: "dd-mm-yy",
	onClose: function(selectedDate){
		$("#from_date").datepicker("option", "maxDate", selectedDate);
	}
});
</script>