<?php
	$id_tab = 44;
	$id_profile = getCookie('profile_id');
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add  = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
	accessDeny($view);
	?>

<div class="container">
	<?php
	if(isset($_GET['add']))
	{
		include 'include/return_order/return_order_add.php';
	}

	else if(isset($_GET['edit']))
	{
		include 'include/return_order/return_order_edit.php';
	}


	else if(isset($_GET['viewDetail']))
	{
		include 'include/return_order/return_order_detail.php';
	}


	else
	{
		include 'include/return_order/return_order_list.php';
	}

	?>

</div><!--/ container -->
<script src="script/return_order/return_order.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/return_order/return_add.js?token=<?php echo date('Ymd'); ?>"></script>
