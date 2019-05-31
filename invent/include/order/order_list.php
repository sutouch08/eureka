<?php

$s_ref = getFilter('s_ref', 's_ref', '');
$s_cus = getFilter('s_cus', 's_cus', '');
$s_emp = getFilter('s_emp', 's_emp', '');

$from  = getFilter('from_date', 'orderFrom', '');
$to = getFilter('to_date', 'orderTo', '');
$vt = getFilter('viewType', 'viewType', 1);

$selectState = getFilter('selectState', 'selectState', '');
$fhour 	= getFilter('fhour', 'fhour', '');
$thour 	= getFilter('thour', 'thour', '');

$state 	= array(
            'state_1'	=> getFilter('state_1', 'state_1', 0), //--- รอชำระเงิน
            'state_3'	=> getFilter('state_3', 'state_3', 0), //-- รอจัดสินค้า
            'state_4'	=> getFilter('state_4', 'state_4', 0), //-- กำลังจัดสินค้า
            //'state_11'	=> getFilter('state_11', 'state_11', 0), //-- กำลังตรวจสินค้า
            'state_10'	=> getFilter('state_10', 'state_10', 0), //-- รอเปิดบิล
            'state_9'	=> getFilter('state_9', 'state_5', 0) //-- เปิดบิลแล้ว
            );
$stateName = array(
            'state_1'	=> 'รอชำระเงิน', //-- รอชำระเงิน
            'state_3'	=> 'รอจัดสินค้า', //-- รอจัดสินค้า
            'state_4'	=> 'กำลังจัดสินค้า', //-- กำลังจัดสินค้า
            //'state_5'	=> 'รอ QC', //-- รอตรวจสินค้า
            //'state_11'	=> 'กำลัง QC', //-- กำลังตรวจสินค้า
            'state_10'	=> 'รอเปิดบิล', //-- รอเปิดบิล
            'state_9'	=> 'เปิดบิลแล้ว' //-- รอเปิดบิล
            );

$all		= $vt == 1 ? 'btn-info' : '';
$online	= $vt == 2 ? 'btn-info' : '';
$normal 	= $vt == 3 ? 'btn-info' : '';


createCookie('orderFrom', $from);
createCookie('orderTo', $to);
//createCookie('viewType', $vt);
createCookie('selectState', $selectState);
createCookie('fhour', $fhour);
createCookie('thour', $thour);
foreach($state as $key => $val)
{
  createCookie($key, $val);
}

$paginator = new paginator();
$get_rows = get_rows();
?>

<form  method="post" id="searchForm">
<div class="row">
<div class="col-sm-2 padding-5" style="padding-left:15px;">
  <label>เอกสาร</label>
      <input type="text" class="form-control input-sm" id="s_ref" name="s_ref" value="<?php echo $s_ref; ?>" placeholder="พิมพ์เลขที่เอกสาร" />
  </div>
  <div class="col-sm-2 padding-5">
  <label>ลูกค้า</label>
      <input type="text" class="form-control input-sm" id="s_cus" name="s_cus" value="<?php echo $s_cus; ?>" placeholder="พิมพ์ชื่อลูกค้า" />
  </div>
  <div class="col-sm-2 padding-5">
  <label>พนักงาน</label>
      <input type="text" class="form-control input-sm" id="s_emp" name="s_emp" value="<?php echo $s_emp; ?>" placeholder="พิมพ์ชื่อพนักงาน" />
  </div>
  <div class="col-sm-2 padding-5">
  <label style="display:block;">วันที่</label>
      <input type="text" class="form-control input-sm input-discount text-center" id="from_date" name="from_date" value="<?php echo $from; ?>" placeholder="เริ่มต้น" />
      <input type="text" class="form-control input-sm input-unit text-center" id="to_date" name="to_date" value="<?php echo $to; ?>" placeholder="สิ้นสุด" />
  </div>
  <!--
  <div class="col-sm-2 padding-5">
  <label style="display:block; visibility:hidden;">&nbsp;</label>
      <div class="btn-group" style="width:100%;">
        <button type="button" id="btn-show-all" class="btn btn-sm width-33 <?php echo $all; ?>" onClick="showAll()" >ทั้งหมด</button>
          <button type="button" id="btn-show-online" class="btn btn-sm width-33 <?php echo $online; ?>"  onClick="showOnline()" >ออนไลน์</button>
          <button type="button" id="btn-show-normal" class="btn btn-sm width-33 <?php echo $normal; ?>" onClick="showNormal()">เครดิต</button>
      </div>
  </div>
-->
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ค้นหา</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้นหา</button>
  </div>

  <div class="col-sm-1 padding-5 last">
  <label style="display:block; visibility:hidden;">&nbsp;</label>
      <button type="button" class="btn btn-warning btn-sm btn-block" onClick="clearFilter()">Reset</button>
  </div>

  <div style="width:100%; float:left; height:1px; margin-top:5px; margin-bottom:5px;"></div>


  <?php $first = 1; ?>
  <?php foreach($state as $key => $val ) : ?>
  <?php	$st = $val == 1 ? 'btn-info' : ''; ?>
  <div class="col-sm-1 padding-5 <?php echo ($first == 1 ? 'first' : ''); ?>">
    <label style="display:block; visibility:hidden;">&nbsp;</label>
    <button type="button" id="btn-<?php echo $key; ?>" class="btn btn-sm btn-block <?php echo $st; ?>" onclick="toggleState('<?php echo $key; ?>')">
      <?php echo $stateName[$key]; ?>
    </button>
    <input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $val; ?>" />
  <?php $first++; ?>
  </div>
  <?php endforeach; ?>
<!--
  <div class="col-sm-2 padding-5">
    <label style="display:block; visibility:hidden;">&nbsp;</label>
    <select class="form-control input-sm" name="selectState" id="selectState">
        <?php //echo selectStateTime($selectState); ?>
      </select>
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เริ่มต้น</label>
      <select class="form-control input-sm" name="fhour" id="fhour">
        <?php //echo selectTime($fhour); ?>
      </select>
  </div>
  <div class="col-sm-1 col-1-harf padding-5 last">
    <label>สิ้นสุด</label>
      <select class="form-control input-sm" name="thour" id="thour">
        <?php //echo selectTime($thour); ?>
      </select>
  </div>
  <div class="col-sm-1 padding-5 last">
  <label style="display:block; visibility:hidden;">&nbsp;</label>
      <button type="button" class="btn btn-primary btn-sm btn-block" onClick="getSearch()">ใช้ตัวกรอง</button>
  </div>
-->

</div>

<input type="hidden" name="viewType" id="viewType" value="<?php echo $vt; ?>" />
</form>
<hr style="border-color:#CCC; margin-top: 15px; margin-bottom:0px;" />
<?php
//--------------------  เงื่อนไข ------------------//

$where = "WHERE role = 1 ";

if( $s_ref != '' OR $s_cus != '' OR $s_emp != '' OR $from != '' )
{
  if( $s_ref != '' )
  {
    createCookie('s_ref', $s_ref);
    $where .= "AND reference LIKE '%".$s_ref."%' ";
  }
  if( $s_cus != '' )
  {
    createCookie('s_cus', $s_cus);
    if( $vt == 2 )
    {
      $in = onlineOrderByCustomer($s_cus);
      if( $in !== FALSE )
      {

        $where .= "AND id_order IN(".$in.") ";
      }
      else
      {
        $in = customer_in($s_cus);
        if( $in !== FALSE )
        {
          $where .= "AND id_customer IN(".$in.") ";
        }
        else
        {
          $where .= "AND id_customer = '' ";
        }
      }
    }
    else
    {
      $in = customer_in($s_cus);
      if( $in !== FALSE )
      {
        $where .= "AND id_customer IN(".$in.") ";
      }
      else
      {
        $where .= "AND id_customer = '' ";
      }
    }
  }
  if( $s_emp != '' )
  {
    createCookie('s_emp', $s_emp);
    $in = employee_in($s_emp);
    if( $in !== FALSE )
    {
      $where .= "AND id_employee IN(".$in.") ";
    }
    else
    {
      $where .= "AND id_employee = '' ";
    }
  }
  if( $from != '' )
  {
      if( $selectState != '' )
      {
        $from = dbDate($from).' '.$fhour.':00';
        $to	= $to == '' ? dbDate($from). ' '.$thour.':00' : dbDate($to). ' '.$thour.':00';
        $in 	= getOrderStateInTime($selectState, $from, $to);
        if( $in != FALSE )
        {
          $where .= "AND id_order IN(".$in.") ";
        }
        else
        {
          $where .= "AND id_order IN(0) ";
        }
      }
      else
      {
        $to	= $to == '' ? toDate($from) : toDate($to);
        $from = fromDate($from);
        $where .= "AND date_add>= '".$from."' AND date_add <='".$to."' ";
      }
  }
}
//$where .= "AND valid != 2 ";
$where .= $vt == 2 ? "AND payment = 'ออนไลน์' " : ($vt == 3 ? "AND payment IN('เครดิต', 'เงินสด') " : '');
$state_in = getStateIn($state);
$where .= $state_in == '' ? "" : "AND current_state IN(".$state_in.") ";
$where .= "ORDER BY id_order DESC";
?>
<div class="row" id="result">
<div class="col-sm-12" id="search-table">
<?php
$paginator->Per_Page("tbl_order",$where,$get_rows);
$paginator->display($get_rows,"index.php?content=order");
?>
  <table class="table" style="border:solid 1px #ccc;">
          <thead>
              <th style="width:5%; text-align:center;">ID</th>
              <th style="width:10%;">เลขที่อ้างอิง</th>
              <th style="width:20%;">ลูกค้า</th>
              <th style="width:10%;">พนักงาน</th>
              <th style="width:10%; text-align:center;">ยอดเงิน</th>
              <th style="width:10%; text-align:center;">การชำระเงิน</th>
              <th style="width:10%; text-align:center;">สถานะ</th>
              <th style="width:8%; text-align:center;">วันที่เพิ่ม</th>
              <th style="width:8%; text-align:center;">วันที่ปรับปรุง</th>
          </thead>
<?php	$qs = dbQuery("SELECT * FROM tbl_order ".$where." LIMIT ".$paginator->Page_Start." , ".$paginator->Per_Page);		?>
<?php	if( dbNumRows($qs) > 0) :		?>
<?php 		while( $rs = dbFetchArray($qs) ) :			?>
<?php			$id = $rs['id_order'];		?>
<?php			$order = new order($id);		?>
<?php			$online = getCustomerOnlineReference($id); ?>
<?php			$customer_name = customer_name($order->id_customer); ?>
<?php			$customer  = $order->payment != 'ออนไลน์' ? $customer_name : ( $online != '' ? $customer_name.' ( '.$online.' )' : $customer_name );	?>
<?php 		$orderAmount = orderAmount($id) - bill_discount($id) + getDeliveryFee($id) + getServiceFee($id); ?>
<?php			if( $order->valid != 2 ) : ?>
    <tr style="color:#000; background-color:<?php echo state_color($order->current_state); ?>; font-size:14px;">
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo $id; ?></td>
      <td style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo $order->reference; ?></td>
      <td style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo $customer; ?></td>
      <td style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo employee_name($order->id_employee); ?></td>
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo number_format($orderAmount); ?></td>
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo $order->payment; ?></td>
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo $order->current_state_name; ?></td>
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo thaiDate($order->date_add); ?></td>
      <td align="center" style="cursor:pointer;" onclick="viewOrder(<?php echo $id; ?>)"><?php echo thaiDate($order->date_upd); ?></td>
    </tr>
<?php		else : ?>
    <tr style="color:#FFF; background-color:#434A54; font-size:10px;">
      <td align="center"><?php echo $id; ?></td>
      <td><?php echo $order->reference; ?></td>
      <td><?php echo $customer; ?></td>
      <td><?php echo employee_name($order->id_employee); ?></td>
      <td align="center"><?php echo number_format($orderAmount); ?></td>
      <td align="center"><?php echo $order->payment; ?></td>
      <td align="center"><?php echo $order->current_state_name; ?></td>
      <td align="center"><?php echo thaiDate($order->date_add); ?></td>
      <td align="center"><?php echo thaiDate($order->date_upd); ?></td>
    </tr>
<?php			endif; ?>
<?php	endwhile; ?>
<?php else : ?>
    <tr><td colspan="9" align="center"><h4>ไม่พบรายการตามเงื่อนไขที่กำหนด</h4></td></tr>
<?php endif; ?>
  </table>

<script>
  var x = setTimeout(function () { location.reload(); }, 60 * 5000);
</script>
</div><!--/ col-sm-12 -->
</div><!--/ row -->
