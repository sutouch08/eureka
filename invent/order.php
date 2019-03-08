<script src="<?php echo WEB_ROOT; ?>library/js/clipboard.min.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/js/jquery.md5.js"></script>
<?php
	$page_name	= "ออเดอร์";
	$id_tab 			= 14;
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	include 'function/order_helper.php';
	include "function/address_helper.php";

	//-------------  ตรวจสอบออเดอร์ที่หมดอายุทุกๆ 24 ชั่วโมง  -----------//
	if( ! getCookie('expirationCheck') )
	{
		orderExpiration();
	}
	//-------------/  ตรวจสอบออเดอร์ที่หมดอายุทุกๆ 24 ชั่วโมง  /-----------//
?>
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
		<h4 class="title"><i class="fa fa-shopping-bag"></i>&nbsp;<?php echo $page_name; ?></h4>
	</div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
	<?php if( isset($_GET['add'] ) || isset( $_GET['edit'] ) || isset( $_GET['view_stock'] ) ) : ?>
    	<button type="button" class="btn btn-sm btn-warning" onClick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    <?php endif; ?>

    <?php if( isset( $_GET['add'] ) && isset( $_GET['id_order'] ) && $add ) : ?>
    <?php 	if( isSaved($_GET['id_order']) === FALSE ) : ?>
        	<button type="button" class="btn btn-success btn-sm" onClick="save(<?php echo $_GET['id_order']; ?>)"><i class="fa fa-save"></i> บันทึก</button>
    <?php 	endif; ?>
    <?php endif; ?>

    <?php if( isset( $_GET['edit'] ) && isset( $_GET['id_order'] ) && isset( $_GET['view_detail'] ) ) : ?>
    	<?php $order = new order($_GET['id_order']); ?>
    	<?php if( $order->valid == 0 && ($order->current_state ==1 || $order->current_state == 3 ) ) : ?>
        	<?php if( $edit OR $add ) : ?>
        	<button type="button" class="btn btn-warning btn-sm" onClick="getEdit(<?php echo $_GET['id_order']; ?>)"><i class="fa fa-pencil"></i> แก้ไข</button>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if( !isset($_GET['add'] ) && !isset( $_GET['edit'] ) && !isset( $_GET['view_stock'] ) ) : ?>
    	<?php if( $add ) : ?>
       <!-- <button type="button" class="btn btn-primary btn-sm" onClick="addNewOnline()"><i class="fa fa-plus"></i> เพิ่มใหม่ ( ออนไลน์ )</button> -->
        <button type="button" class="btn btn-success btn-sm" onClick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่ ( ปกติ )</button>
		<?php endif; ?>
        <button type="button" class="btn btn-info btn-sm" onClick="viewStock()"><i class="fa fa-search"></i> ดูสต็อกคงเหลือ</button>
    <?php endif; ?>
       </p>
    </div>
</div>
<hr style='border-color:#CCC; margin-top: 5px; margin-bottom:15px;' />

<?php
//*********************************************** เพิ่มออเดอร์ใหม่ ********************************************************//
if(isset($_GET['add']))
{
	include 'include/order/order_add.php';
}
elseif(isset($_GET['edit']) && isset($_GET['id_order']))
{

	include 'include/order/order_edit.php';
}
elseif(isset($_GET['view_stock']))
{
	include 'include/order/order_view_stock.php';
}
else
{
	include 'include/order/order_list.php';
}
?>
</div><!--/ Container -->


<script id="orderProductTemplate" type="text/x-handlebars-template" >
	{{#each this }}
		{{#if @last}}
			<tr>
				 <td colspan="7" align="right"><h4>จำนวนรวม</h4></td>
				 <td  align="right"><h4>{{ total_qty }}</h4></td>
				 <td align="center"><h4>ชิ้น</h4></td>
			</tr>
		{{else}}
    	<tr style="font-size:12px;">
        	<td align="center" style="vertical-align:middle;">{{ no }}</td>
            <td align="center" style="vertical-align:middle;">{{{ img }}}</td>
            <td align="center" style="vertical-align:middle;">{{ barcode }}</td>
            <td style="vertical-align:middle;">{{ product }}</td>
            <td align="center" style="vertical-align:middle;">{{ price }}</td>
            <td align="center" style="vertical-align:middle;">{{ qty }}</td>
            <td align="center" style="vertical-align:middle;">{{ discount }}</td>
            <td align="center" style="vertical-align:middle;">{{ amount }}</td>
            <td align="right" style="vertical-align:middle;">
            	<button type="button" class="btn btn-danger btn-xs" onClick="deleteRow({{ id }}, '{{ product }}')"><i class="fa fa-trash"></i></button>
            </td>
      	</tr>
		{{/if}}
	{{/each}}
</script>
<script>
	function expandCategory(el)
	{
		var className = 'open';
		if (el.classList)
		{
    		el.classList.add(className)
		}else if (!hasClass(el, className)){
			el.className += " " + className
		}
	}

	function collapseCategory(el)
	{
		var className = 'open';
		if (el.classList)
		{
			el.classList.remove(className)
		}else if (hasClass(el, className)) {
			var reg = new RegExp('(\\s|^)' + className + '(\\s|$)')
			el.className=el.className.replace(reg, ' ')
  		}
	}
</script>

<script src="script/order.js"></script>
