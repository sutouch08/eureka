<div class="row top-row">
  <div class="col-sm-12 col-xs-12 top-col">
    <h4 class="title"><?php echo $page_name; ?></h4>
  </div>
</div>
<hr/>

<?php
  $sCode = getFilter('sCode', 'sCode', '');
  $sName = getFilter('sName', 'sName', '');
  $fromDate = getFilter('fromDate', 'fromDate', '');
  $toDate = getFilter('toDate', 'toDate', '');
 ?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 col-sx-12">
    <label class="hidden-xs">เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" placeholder="เลขที่เอกสาร" id="sCode" name="sCode" value="<?php echo $sCode; ?>" />
  </div>
  <div class="col-sm-3 col-xs-12">
    <label class="hidden-xs">ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center" placeholder="ลูกค้า" id="sName" name="sName" value="<?php echo $sName; ?>" />
  </div>
  <div class="col-sm-3 col-xs-12">
    <label class="display-block hidden-xs">วันที่</label>
    <input type="text" class="form-control input-sm text-center input-discount" id="fromDate" name="fromDate" placeholder="เริ่มต้น" value="<?php echo $fromDate; ?>" readonly />
    <input type="text" class="form-control input-sm text-center input-unit" id="toDate" name="toDate" placeholder="สิ้นสุด" value="<?php echo $toDate; ?>" readonly/>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label class="dispay-block not-show hidden-xs">Reset</label>
    <button type="button" class="btn btn-md btn-warning btn-block" onclick="clearFilter()">Clear</button>
  </div>
  <div class="col-sm-2 col-xs-6">
    <label class="display-block not-show hidden-xs">Search</label>
    <button type="button" class="btn btn-md btn-info btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
  </div>
</div>
</form>
<hr/>
<?php
createCookie('sCode', $sCode);
createCookie('sName', $sName);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);

$where = "WHERE id_employee = '".getCookie('user_id')."' ";

if($sCode != '')
{
  $where .= "AND reference LIKE '%".$sCode."%' ";
}

if($sName != '')
{
  $where .= "AND id_customer IN(".customer_in($sName).") ";
}

if($fromDate != '' && $toDate != '')
{
  $where .= "AND date_add >= '".dbDate($fromDate)."' ";
  $where .= "AND date_add <= '".dbDate($toDate)."' ";
}

$where .= "ORDER BY reference DESC";

$paginator	= new paginator();
$get_rows	  = get_rows();
$page		    = get_page();

$paginator->Per_Page('tbl_order', $where, $get_rows);
$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12 col-xs-12">
		<?php $paginator->display($get_rows, 'index.php?content=tracking'); ?>
	</div>
	<div class="col-xs-12 visible-xs">&nbsp;</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive" style="min-height:400px;">
    	<table class="table table-bordered">
        	<thead>
            	<tr class="font-size-10">
                <th class="width-5 text-center">ลำดับ</th>
                <th class="width-15 text-center">เลขที่เอกสาร</th>
                <th class="text-center">ลูกค้า</th>
                <th class="width-10 text-center">ยอดเงิน</th>
                <th class="width-10 text-center">สถานะ</th>
                <th class="width-10 text-center">วันที่</th>
              </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no 		= row_no();			?>
<?php	$cs 		= new customer(); ?>
<?php	$order 	= new order(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>

			<tr  style="font-size:12px; color:white; background-color:<?php echo state_color($rs->current_state); ?>"  //--- order_help.php ?>
        <td class="middle text-cennter pointer text-center" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo $no; ?>
        </td>

        <td class="middle pointer text-center" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo $rs->reference; ?>
        </td>

        <td class="middle pointer" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo customer_name($rs->id_customer); ?>
        </td>

				<td class="middle pointer text-center" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo number_format($order->getTotalAmount($rs->id_order), 2); ?>
        </td>

				<td class="middle pointer text-center" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo stateName($rs->current_state); ?>
        </td>

				<td class="middle pointer text-center" onclick="viewDetail(<?php echo $rs->id_order; ?>)">
          <?php echo thaiDate($rs->date_add); ?>
        </td>

      </tr>
<?php		$no++; ?>
<?php		endwhile; ?>
<?php else : ?>
			 <tr>
          <td colspan="6" class="text-center"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php endif; ?>
            </tbody>
        </table>
			</div>
    </div>
</div>
