<?php
	$page_menu = "invent_product_move";
	$page_name = "ตรวจสอบ BUFFER ZONE";
	$id_tab = 9;
	$id_profile = $_COOKIE['profile_id'];
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];

	$s_ref = getFilter('s_ref', 's_ref', '');
	$s_product = getFilter('s_product', 's_product', '');
	?>

<div class="container">
<!-- page place holder -->
<div class="row" style="height:35px;">
	<div class="col-lg-8 col-sm-12 col-xs-12" style="margin-top:10px;">
    	<h4 class="title"><i class="fa fa-bar-chart"></i>  <?php echo $page_name; ?></h4>
    </div>
    <div class="col-lg-4 col-sm-12 col-xs-12">
       <p class="pull-right" style="margin-bottom:0px;">
       <?php if(isset($_GET['completed'])) : ?>
       <button type="button" class="btn btn-primary btn-sm" onclick="all_items()"><i class="fa fa-list"></i> รายการทั้งหมด</button>
       <?php else : ?>
       	<button type="button" class="btn btn-info btn-sm" onclick="find_completed()"><i class="fa fa-list"></i> รายการที่เปิดบิลแล้ว</button>
       <?php endif; ?>
		</p>
     </div>
</div>
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:10px;' />
<!-- End page place holder -->
<?php if( isset($_GET['completed']) ) : ?>
<div class="row">
<div class="col-lg-12">
	<table class="table table-striped">
    	<thead style="font-size:12px;">
            <th style="width: 30%;">เลขที่เอกสาร</th>
            <th style="width: 10%; text-align:right;">จำนวน</th>
            <th style="width: 20%; text-align:center;">สถานะ</th>
            <th style="text-align:right;">การกระทำ</th>
        </thead>
<?php $qs = dbQuery("SELECT tbl_buffer.id_order, SUM(qty) AS qty, tbl_order.reference, current_state, tbl_order.date_upd FROM tbl_buffer JOIN tbl_order ON tbl_buffer.id_order = tbl_order.id_order WHERE tbl_order.current_state IN(9,8) GROUP BY tbl_buffer.id_order"); ?>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	while( $rs = dbFetchArray($qs) ) : ?>
			<tr id="row_<?php echo $rs['id_order']; ?>">
                <td><?php echo $rs['reference']; ?></td>
                <td align="right"><?php echo number_format($rs['qty']); ?></td>
                <td align="center"><?php echo current_order_state($rs['id_order']); ?></td>
                <td align="right"><button type="button" class="btn btn-danger btn-sm" onclick="clear_buffer(<?php echo $rs['id_order']; ?>)"><i class="fa fa-exclamation-triangle"></i> เคลีร์ยรายการที่ค้าง</button></td>
            </tr>
<?php 	endwhile; ?>
<?php else : ?>
			<tr><td colspan="5" align="center"><h4>-----  ไม่มีรายการค้าง  -----</h4></td></tr>
<?php endif; ?>
</table>
</div>
</div>
<?php else : ?>
	<form  method="post" id="searchForm">
	<div class="row">
		<div class="col-sm-2 padding-5" style="padding-left:15px;">
		  <label>เอกสาร</label>
		      <input type="text" class="form-control input-sm search" id="s_ref" name="s_ref" value="<?php echo $s_ref; ?>" placeholder="พิมพ์เลขที่เอกสาร" />
		  </div>
		  <div class="col-sm-2 padding-5">
		  <label>สินค้า</label>
		      <input type="text" class="form-control input-sm search" id="s_product" name="s_product" value="<?php echo $s_product; ?>" placeholder="รหัสสินค้า" />
		  </div>
			<div class="col-sm-1 padding-5">
		    <label class="display-block not-show">ค้นหา</label>
		    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้นหา</button>
		  </div>

		  <div class="col-sm-1 padding-5 last">
		  <label style="display:block; visibility:hidden;">&nbsp;</label>
		      <button type="button" class="btn btn-warning btn-sm btn-block" onClick="clearFilter()">Reset</button>
		  </div>
	</div>
	</from>
	<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:10px;' />
<div class="row">
<div class='col-sm-12'>
<table class="table table-striped border-1">
<thead>
	<th style="width:5%; text-align:center" >ลำดับ</th>
	<th style="width:30%;">สินค้า</th>
	<th style="width:10%; text-align:right">จำนวน</th>
	<th style="width:15%; text-align:center">จากออเดอร์</th>
	<th style="width:15%;">สถานะ</th>
	<th>จากโซน</th>
</thead>
<?php
	$where  = "LEFT JOIN tbl_order ON tbl_buffer.id_order = tbl_order.id_order ";
	$where .= "LEFT JOIN tbl_product_attribute ON tbl_buffer.id_product_attribute = tbl_product_attribute.id_product_attribute ";
	$where .= "WHERE id_buffer > 0 ";

	if(!empty($s_ref))
	{
		createCookie('s_ref', $s_ref);
    $where .= "AND tbl_order.reference LIKE '%".$s_ref."%' ";
	}

	if(!empty($s_product))
	{
		createCookie('s_product', $s_product);
		$where .= "AND tbl_product_attribute.reference LIKE '{$s_product}%' ";
	}

	$where .= "ORDER BY tbl_order.reference ASC, tbl_product_attribute.reference ASC";

	$paginator = new paginator();
	$get_rows = get_rows();

	$paginator->Per_Page("tbl_buffer",$where,$get_rows);
	$paginator->display($get_rows,"index.php?content=buffer_zone");

	$qr  = "SELECT tbl_buffer.*, tbl_order.reference AS orderCode, tbl_product_attribute.reference AS pdCode ";
	$qr .= "FROM tbl_buffer ".$where." LIMIT ".$paginator->Page_Start." , ".$paginator->Per_Page;

?>
<?php $sql = dbQuery($qr); ?>
<?php $row = dbNumRows($sql); ?>
<?php if($row > 0 ) : ?>

	<?php   $no = row_no(); ?>
	<?php while($rs = dbFetchArray($sql)) : ?>
    <tr>
    	<td align="center"><?php echo $no; ?></td>
        <td><?php echo $rs['pdCode']; ?></td>
        <td align="right"><?php echo $rs['qty']; ?></td>
        <td align="center"><?php echo $rs['orderCode']; ?></td>
        <td><?php echo current_order_state($rs['id_order']); ?></td>
        <td><?php echo get_zone($rs['id_zone']); ?></td>
    </tr>
    <?php 	$no++; ?>
    <?php endwhile; ?>

<?php else : ?>
	<tr><td colspan="6" align="center"><h4>----------  ไม่มีสินค้า  ----------</h4></td></tr>
<?php endif; ?>
</table>
</div>

<?php endif; ?>
</div>
<script>
function find_completed()
{
	window.location.href = "index.php?content=buffer_zone&completed";
}
function all_items()
{
	window.location.href = "index.php?content=buffer_zone";
}

function clear_buffer(id)
{
	load_in();
	$.ajax({
		url: "controller/billController.php?clear_buffer&id_order="+id,
		type:"GET", cache:"false",
		success: function(rs)
		{
			load_out();
			window.location.reload();
		}
	});
}

function getSearch() {
	$('#searchForm').submit();
}

function clearFilter() {
    $.ajax({
        url: "controller/bufferController.php?clearFilter",
        success: function(rs) {
            goBack();
        }
    });
}


function goBack() {
	window.location.href = "index.php?content=buffer_zone";
}

$('.search').keyup(function(e){
	if(e.keyCode === 13) {
		getSearch();
	}
})
</script>
