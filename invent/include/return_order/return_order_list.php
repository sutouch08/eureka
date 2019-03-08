<?php
$sReference = getFilter('sReference', 'sReference', '');
$sOrderCode = getFilter('sOrderCode', 'sOrderCode', '');
$sCustomer  = getFilter('sCustomer', 'sCustomer', '');
$fromDate   = getFilter('fromDate', 'fromDate', '');
$toDate     = getFilter('toDate', 'toDate','');
?>


<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php if($add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr />
<form method="post" id="searchForm">
  <div class="row">
    <div class="col-sm-2 padding-5 first">
      <label>เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" name="sReference" id="sReference" value="<?php echo $sReference; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
      <label>เลขที่บิล</label>
      <input type="text" class="form-control input-sm text-center" name="sOrderCode" id="sOrderCode" value="<?php echo $sOrderCode; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
      <label>ลูกค้า</label>
      <input type="text" class="form-control input-sm text-center" name="sCustomer" id="sCustomer" value="<?php echo $sCustomer; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">วันที่</label>
      <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="เริ่มต้น"/>
      <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="สิ้นสุด"/>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">Search</label>
      <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">Reset</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
  </div>
</form>
<hr />

<?php
  createCookie('sReference', $sReference);
  createCookie('sOrderCode', $sOrderCode);
  createCookie('sCustomer', $sCustomer);
  createCookie('fromDate', $fromDate);
  createCookie('toDate', $toDate);
  $table = 'tbl_return_order';
  $where = "WHERE id != 0 ";

  if($sReference != '')
  {
    $where .= "AND reference LIKE '%".$sReference."%' ";
  }

  if($sOrderCode != '')
  {
    $where .= "AND order_code LIKE '%".$sOrderCode."%' ";
  }

  if($sCustomer != '')
  {
    $where .= "AND id_customer IN(".customer_in($sCustomer).") ";
  }

  if($fromDate != '' && $toDate != '')
  {
    $where .= "AND date_add >= '".fromDate($fromDate)."' ";
    $where .= "AND date_add <= '".toDate($toDate)."' ";
  }

  $where .= "ORDER BY reference DESC";

  $paginator = new paginator();
  $get_rows  = get_rows();
	$paginator->Per_Page($table, $where, $get_rows);
  $qs = dbQuery("SELECT * FROM ".$table." ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>
 <div class="row">
 	<div class="col-sm-8 padding-5 first">
 		<?php $paginator->display($get_rows, 'index.php?content=return_order'); ?>
 	</div>
  <div class="col-sm-4 margin-top-15">
      <p class="pull-right">
        <span class="">ว่างๆ</span><span> = ปกติ, </span>
        <span class="red">CN</span><span> = ยกเลิก </span>
    </p>
  </div>
 </div>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-10 text-center">วันที่</th>
          <th class="width-15">เลขที่เอกสาร</th>
          <th class="width-15">เลขที่บิล</th>
          <th class="width-20">ลูกค้า</th>
          <th class="width-10 text-right">จำนวน</th>
          <th class="width-10 text-right">มูลค่า</th>
          <th class="width-5 text-center">สถานะ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
<?php if(dbNumRows($qs) > 0) : ?>
<?php   $no = row_no(); ?>
<?php   $cs = new customer(); ?>
<?php   $ro = new return_order(); ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
        <tr id="row-<?php echo $rs->id; ?>">
          <td class="text-center no"><?php echo $no; ?></td>
          <td class="text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td><?php echo $rs->reference; ?></td>
          <td><?php echo $rs->order_code; ?></td>
          <td><?php echo $cs->getFullName($rs->id_customer); ?></td>
          <td class="text-right"><?php echo number($ro->getSumQty($rs->id)); ?></td>
          <td class="text-right"><?php echo number($ro->getSumAmount($rs->id),2); ?></td>
          <td class="text-center red"><?php echo $rs->isCancle == 1 ? 'CN' : ''; ?></td>
          <td class="text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
            <?php if($edit) : ?>
              <button type="button" class="btn btn-xs btn-warning" onclick="goEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
            <?php endif; ?>
            <?php if($delete) : ?>
              <button type="button" class="btn btn-xs btn-danger" onclick="goDelete(<?php echo $rs->id; ?>, '<?php echo $rs->reference; ?>')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
          </td>
        </tr>
<?php   $no++; ?>
<?php   endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="9" class="text-center">No Data.</td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
