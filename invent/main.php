<?php
	$pop_on = "back";
	$now = date('Y-m-d H:i:s');
	$qa  = "SELECT * FROM tbl_popup ";
	$qa .= "WHERE pop_on = 'back' ";
	$qa .= "AND start <= '".$now."' ";
	$qa .= "AND end >= '".$now."' ";
	$qa .= "AND active = 1";
	$sql = dbQuery($qa);

	if(dbNumRows($sql) == 1)
	{
		$res    = dbFetchObject($sql);
		$width  = $res->width;
		$height = $res->height;
		$delay  = $res->delay;

		$popup_content  = '<div class="row">';
		$popup_content .= '<div class="col-sm-12">';
		$popup_content .= $res->content;
		$popup_content .= '</div></div>';


		include '../library/popup.php';

		if( ! getCookie('pop_back'))
		{
			createCookie('pop_back', $res->delay, time()+$delay);
			echo '<script> $(document).ready(function(e){ $("#modal_popup").modal("show"); }); </script>';
		}

	}

?>

<div class="container">
	<div class="row margin-top-15">
		<div class="col-sm-4 col-sm-offset-3 padding-5">
			<input type="text" class="form-control input-sm text-center" id="search-text" placeholder="พิมพ์รหัสสินค้า 4 ตัวอักษรขึ้นไป" />
		</div>
		<div class="col-sm-1 col-1-harf padding-5">
			<button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ตรวจสอบสต็อก</button>
		</div>
		<div class="col-sm-1 col-1-harf padding-5">
			<button type="button" class="btn btn-sm btn-info btn-block" onclick="findOrder()">ตรวจสอบออเดอร์</button>
		</div>
		<div class="col-sm-1 col-1-harf padding-5">
			<button type="button" class="btn btn-sm btn-info btn-block" onclick="getViewStock()">ดูสต็อกคงเหลือ</button>
		</div>
	</div>
	<hr class="margin-top-15 margin-bottom-15"/>

	<div class="row">
		<div class="col-sm-12" id="result">

		</div>
	</div>

</div><!--/ container -->

<script id="order-template" type="text/x-handlebarsTemplate">
<table class="table table-bordered">
	<thead>
		<tr class="font-size-12">
			<th class="width-15">รหัสสินค้า</th>
			<th class="width-15 text-center">เลขที่ออเดอร์</th>
			<th class="width-10 text-center">จำนวน</th>
			<th class="width-10 text-center">สถานะ</th>
			<th class="width-30 text-center">ลูกค้า</th>
			<th class="width-20 text-center">พนักงาน</th>
		</tr>
	</thead>
	<tbody>
		{{#each this}}
			{{#if nodata}}
				<tr>
					<td colspan="6" class="text-center">ไม่พบข้อมูล</td>
				</tr>
			{{else}}
				<tr class="font-size-12">
					<td class="middle">{{ pdCode }}</td>
					<td class="middle text-center">
						<a href="javascript:void(0)" onclick="view_order({{id_order}})">
							{{ reference }}
						</a>
					</td>
					<td class="middle text-center">{{ qty }}</td>
					<td class="middle text-center">{{ state }}</td>
					<td class="middle">{{ cusName }}</td>
					<td class="middle">{{ empName }}</td>
				</tr>
			{{/if}}
		{{/each}}
	</tbody>
</table>
</script>


<script id="stock-template" type="text/x-handlebarsTemplate">
<table class="table table-bordered">
	<thead>
		<tr class="font-size-12">
			<th class="width-10 text-center">รูปภาพ</th>
			<th class="width-15 text-center">รหัสสินค้า</th>
			<th class="text-center">ชื่อสินค้า</th>
			<th class="width-10 text-center">จำนวน</th>
			<th class="width-10 text-center">สถานที่</th>
		</tr>
	</thead>
	<tbody>
{{#each this}}
	{{#if nodata}}
		<tr>
			<td colspan="4" class="text-center">ไม่พบรายการ</td>
		</tr>
	{{else}}
		<tr>
			<td class="middle text-center">{{{ img }}}</td>
			<td class="middle">{{ pdCode }}</td>
			<td class="middle">{{ pdName }}</td>
			<td class="text-center middle">{{ qty }}</td>
			<td class="text-center middle">
				<button type="button"
							class="btn btn-info"
							data-container="body"
							data-toggle="popover"
							data-html="true"
							data-placement="left"
							data-trigger="focus"
							data-content="{{ stockInZone }}">
							รายละเอียด
				</button>
			</td>
		</tr>
	{{/if}}
{{/each}}
	</tbody>
</table>
</script>



<script>

//---- ค้นหาว่าสินค้าติดอยู่ที่ออเดอร์ไหนบ้าง
function findOrder(){
	var searchText = $.trim($('#search-text').val());
	if(searchText.length > 3){
		load_in();
		$.ajax({
			url:'controller/searchController.php?findOrder',
			type:'GET',
			cache:'false',
			data:{
				'searchText' : searchText
			},
			success:function(rs){
				load_out();
				var source = $('#order-template').html();
				var data = $.parseJSON(rs);
				var output = $('#result');
				render(source, data, output);
			}
		});
	}
}



function getSearch(){
	var searchText = $.trim($('#search-text').val());
	if(searchText.length > 3 ){
		load_in();
		$.ajax({
			url:'controller/searchController.php?getProductStock',
			type:'GET',
			cache:'false',
			data:{
				'searchText' : searchText
			},
			success:function(rs){
				load_out();
				var source = $('#stock-template').html();
				var data = $.parseJSON(rs);
				var output = $('#result');
				render(source, data, output);
				popover_init();
			}
		});
	}
}


function view_order(id){
	var wid = $(document).width();
	var left = (wid - 900) / 2;
	window.open("index.php?content=order&edit&view_detail&id_order="+id+"&nomenu", "_blank", "width=900, height=1000, left="+left+", location=no, scrollbars=yes");
}

function popover_init(){
	$('[data-toggle="popover"]').popover();
}

function getViewStock(){
	window.location.href = 'index.php?content=order&view_stock=Y';
}
</script>
