<?php if( $viewStockOnly === FALSE ) : ?>
<ul class="nav navbar-nav">
  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes"></i>  ระบบคลังสินค้า</a>
    <ul class="dropdown-menu">
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i>  รับสินค้าเข้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=receive_product"><i class="fa fa-download"></i>  รับสินค้า จากการซื้อ</a></li>
<!--          <li><a href="index.php?content=receive_tranform"><i class="fa fa-download"></i>  รับสินค้า จากการแปรสภาพ</a></li> -->
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i>  รับคืนสินค้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=order_return"><i class="fa fa-download"></i>  รับคืนสินค้า จากการขาย(ปัจจุบัน)</a></li>
          <li><a href="index.php?content=order_return2"><i class="fa fa-download"></i>  รับคืนสินค้า จากการขาย(อดีต)</a></li>
<!--          <li><a href="index.php?content=support_return"><i class="fa fa-download"></i>  รับคืนสินค้า จากอภินันท์</a></li> -->
<!--          <li><a href="index.php?content=sponsor_return"><i class="fa fa-download"></i>  รับคืนสินค้า จากสปอนเซอร์</a></li>-->
        </ul>
      </li>
      <li class="divider"></li>
<!--      <li><a href="index.php?content=requisition">		<i class="fa fa-upload"></i>  เบิกสินค้าเพื่อแปรรูป</a></li> -->
<!--      <li><a href="index.php?content=order_transform"><i class="fa fa-upload"></i>  เบิกสินค้าเพื่อแปรสภาพ</a></li> -->
<!--      <li><a href="index.php?content=order_support"><i class="fa fa-upload"></i>  เบิกอภินันทนาการ</a></li> -->
<!--      <li><a href="index.php?content=lend"><i class="fa fa-upload"></i>  ยืมสินค้า</a></li> -->
<!--      <li class="divider"></li> -->
      <li><a href="index.php?content=tranfer"><i class="fa fa-recycle"></i>  โอนสินค้าระหว่างคลัง</a></li>
      <li><a href="index.php?content=ProductMove"><i class="fa fa-recycle"></i>  ย้ายพื้นที่จัดเก็บ</a></li>
      <li><a href="index.php?content=ProductCheck"><i class="fa fa-check-square-o"></i>  ตรวจนับสินค้า</a></li>
      <li><a href="index.php?content=ProductAdjust"><span class="glyphicon glyphicon-tasks"></span>  ปรับปรุงยอด</a></li>
      <li><a href="index.php?content=drop_zero"><span class="glyphicon glyphicon-tasks"></span>  เคลียร์ยอดสต็อกที่เป็นศูนย์</a></li>
      <li><a href="index.php?content=buffer_zone"><span class="glyphicon glyphicon-tasks"></span>  ตรวจสอบ BUFFER ZONE</a></li>
      <li><a href="index.php?content=cancle_zone"><span class="glyphicon glyphicon-tasks"></span>  ตรวจสอบ CANCLE ZONE</a></li>
      <li><a href="index.php?content=stock"><span class="glyphicon glyphicon-tasks"></span>  ตรวจสอบ Stock</a></li>
      <li><a href="index.php?content=stock_movement"><span class="glyphicon glyphicon-tasks"></span>  ตรวจสอบ Movement</a></li>
    </ul>
  </li>

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i>  ระบบขาย</a>
    <ul class="dropdown-menu">
      <li><a href="index.php?content=order"><i class="fa fa-shopping-bag"></i>  ออเดอร์</a></li>
      <li><a href="index.php?content=order_sponsor"><i class="fa fa-ticket"></i>  สปอนเซอร์ สโมสร</a></li>
<!--      <li><a href="index.php?content=consignment"><i class="fa fa-cloud-upload"></i>  ฝากขาย</a></li> -->
<!--      <li><a href="index.php?content=order_online"><i class="fa fa-desktop"></i>  ขายออนไลน์</a></li> -->
      <li class="divider"></li>
      <li><a href="index.php?content=prepare"><i class="fa fa-shopping-basket"></i>  จัดสินค้า</a></li>
<!--      <li><a href="index.php?content=qc"><i class="fa fa-check-square-o"></i>  ตรวจสินค้า</a></li> -->
    <?php if($fast_qc) : ?>
<!--     	<li><a href="index.php?content=qc2"><i class="fa fa-check-square-o"></i>  ตรวจสินค้า จากข้างนอก</a></li> -->
     <?php endif; ?>
      <li><a href="index.php?content=bill"><i class="fa fa-file-text-o"></i>  ออเดอร์รอเปิดบิล</a></li>
      <li><a href="index.php?content=order_closed"><i class="fa fa-file-text-o"></i>  ออเดอร์เปิดบิลแล้ว</a></li>
     <li class="divider"></li>
<!--     <li><a href="index.php?content=request"><i class="fa fa-thumb-tack"></i>  ร้องขอสินค้า</a></li> -->
<!--     <li><a href="index.php?content=order_monitor"><i class="fa fa-exclamation-triangle"></i>  ตรวจสอบออเดอร์</a></li> -->
    </ul>
  </li>

<!--  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-credit-card"></i>  บัญชี</a>
    <ul class="dropdown-menu">
  <li><a href="index.php?content=consign&consign_check=y"><i class="fa fa-check-square-o"></i>  กระทบยอดสินค้า</a></li>
      <li><a href="index.php?content=consign_check"><i class="fa fa-check-square-o"></i>  กระทบยอดสินค้าฝากขาย</a></li>
      <li><a href="index.php?content=consign"><i class="fa fa-check-square-o"></i>  ตัดยอดฝากขาย</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=payment_order"><i class="fa fa-check-square-o"></i>  ตรวจสอบยอดชำระเงิน</a></li>
      <li class="divider"></li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i>  ตรวจนับสินค้า (ภายใน)</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=checkstock"><i class="fa fa-check-square-o"></i>  ตรวจนับสินค้า</a></li>
          <li><a href="index.php?content=OpenCheck"><i class="fa fa-toggle-on"></i>  เปิด/ปิดการตรวจนับ</a></li>
          <li><a href="index.php?content=check_stock_moniter"><i class='fa fa-desktop'></i>  ภาพรวมการตรวจนับ</a></li>
          <li><a href="index.php?content=ProductCount"><i class="fa fa-exclamation-triangle"></i>  ตรวจสอบยอดสินค้าจากการตรวจนับ</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i>  ตรวจนับสินค้า (ฝากขาย)</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=export_consign_stock"><i class="fa fa-file-text-o"></i>  ส่งออกยอดตั้งต้น</a></li>
          <li><a href="#"><i class="fa fa-download"></i>  นำเข้ายอดตรวจนับ(ห้ามใช้)</a></li>
        </ul>
      </li>
    </ul>
  </li> -->

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i>  ระบบซื้อ</a>
  	<ul class="dropdown-menu">
    	<li><a href="index.php?content=po"><i class="fa fa-archive"></i>  สั่งซื้อ(PO)</a></li>
      <li><a href="index.php?content=po_role"><i class="fa fa-archive"></i>  เพิ่ม/แก้ไข ประเภทการสั่งซื้อ</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน</a>
    <ul class="dropdown-menu">
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ระบบคลังสินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=recieved_report">			<i class="fa fa-bar-chart"></i> รายงาน การรับสินค้า</a></li>
          <li><a href="index.php?content=received_by_document">	<i class="fa fa-bar-chart"></i> รายงาน การรับสินค้า แยกตามเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=received_by_product">	<i class="fa fa-bar-chart"></i> รายงาน การรับสินค้า แยกตามรุ่นสินค้า</a></li>
<!--          <li><a href="index.php?content=recieved_tranform_report"><i class="fa fa-bar-chart"></i> รายงาน การรับสินค้าจากการแปรสภาพ</a></li> -->
<!--          <li><a href="index.php?content=transform_by_document">	<i class="fa fa-bar-chart"></i> รายงาน การรับสินค้าจากการแปรสภาพ แยกตามเลขที่เอกสาร</a></li> -->
        	<li class="divider"></li>
        	<li><a href="index.php?content=current_stock"><i class='fa fa-list'></i>  รายงานสินค้าคงเหลือปัจจุบัน</a></li>
          <li><a href="index.php?content=stock_report"><i class="fa fa-bar-chart"></i>  รายงานสินค้าคงเหลือ</a></li>
          <li><a href="index.php?content=stock_zone_report"><i class="fa fa-bar-chart"></i>  รายงานสินค้าคงเหลือแยกตามโซน</a></li>
          <li><a href="index.php?content=stock_by_warehouse"><i class="fa fa-bar-chart"></i>  รายงานสินค้าคงเหลือเปรียบเทียบคลัง</a></li>
          <li><a href="index.php?content=movement_summary">		<i class="fa fa-bar-chart"></i>  รายงานสรุป ยอดความเคลื่อนไหวสินค้า เปรียบเทียบ เข้า - ออก</a></li>
          <li><a href="index.php?content=fifo"><i class="fa fa-bar-chart"></i>  รายงานความเคลื่อนไหวสินค้าแต่ละตัว</a></li>
          <li><a href="index.php?content=total_fifo"><i class="fa fa-bar-chart"></i>  รายงานยอดรวมสินค้าเข้า-ออก</a></li>
          <li><a href="index.php?content=non_move"><i class="fa fa-bar-chart"></i>  รายงานสินค้าไม่เคลื่อนไหว</a></li>
<!--          <li><a href="index.php?content=request_report"><i class="fa fa-bar-chart"></i>  รายงานการร้องขอสินค้า</a></li>-->
<!--          <li><a href="index.php?content=request_by_customer"><i class="fa fa-bar-chart"></i>  รายงานการร้องขอสินค้า แยกตามลูกค้า</a></li> -->
          <li class="divider"></li>
          <li><a href="index.php?content=stock_summary"><i class="fa fa-bar-chart"></i>  รายงานสรุปสินค้าคงเหลือ แยกตามรุ่นสินค้า</a></li>
          <li><a href="index.php?content=stock_summary_by_category"><i class="fa fa-bar-chart"></i>  รายงานสรุปสินค้าคงเหลือ แยกตามหมวดหมู่สินค้า</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ระบบขาย</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามรุ่นสินค้า เปรียบเทียบรายเดือน</a></li>
          <li><a href="index.php?content=sale_summary_by_category"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามหมวดหมู่สินค้า เปรียบเทียบรายเดือน</a></li>
        	<li><a href="index.php?content=sale_report_zone"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามพื้นที่การขาย</a></li>
          <li><a href="index.php?content=sale_report_employee"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามพนักงานขาย</a></li>
          <li><a href="index.php?content=sale_report_customer"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามลูกค้า</a></li>
          <li><a href="index.php?content=sale_report_product"><i class="fa fa-bar-chart"></i>  สรุปยอดขาย แยกตามรุ่นสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=sale_detail_by_customer"><i class="fa fa-bar-chart"></i>  รายงานสินค้า แยกตามลูกค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=sale_by_document"><i class="fa fa-bar-chart"></i>  รายงานยอดขาย แยกตามเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sale_amount_detail"><i class="fa fa-bar-chart"></i>  รายละเอียดการขาย แยกตามพนักงานขาย</a></li>
          <li><a href="index.php?content=sale_amount_document"><i class="fa fa-bar-chart"></i>  รายงานยอดขาย แยกตามพนักงานและเอกสาร</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=sale_by_attribute"><i class="fa fa-bar-chart"></i> รายงานจำนวนขาย แยกตามคุณลักษณะสินค้า</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ระบบซื้อ</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=po_backlog"><i class="fa fa-bar-chart"></i>  รายงาน ใบสั่งซื้อค้างรับ แยกตามผู้ขาย</a></li>
          <li><a href="index.php?content=product_backlog_by_supplier"><i class="fa fa-bar-chart"></i>  รายงาน สค้างรับแยกตามผู้ขาย แสดงรุ่นสินค้าและเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=product_backlog"><i class="fa fa-bar-chart"></i>  รายงาน สินค้าค้างรับ แบบละเอียด</a></li>
          <li class="divider"></li>
<!--        	<li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  รายงาน สรุปสินค้าค้างรับ แยกตามเลขที่เอกสาร(ห้ามใช้)</a></li> -->
          <li><a href="index.php?content=product_summary_backlog_by_product"><i class="fa fa-bar-chart"></i>  รายงาน สรุปสินค้าค้างรับ แยกตามรุ่นสินค้า</a></li>
          <li><a href="index.php?content=product_summary_backlog_by_item"><i class="fa fa-bar-chart"></i>  รายงาน สรุปสินค้าค้างรับ แยกตามรายการสินค้า</a></li>
<!--          <li><a href="index.php?content=product_summary_backlog_by_supplier"><i class="fa fa-bar-chart"></i>  รายงาน สรุปสินค้าค้างรับ แยกตามผู้ขาย</a></li>-->
<!--          <li class="divider"></li> -->
<!--          <li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  รายงาน สรุปยอดการสั่งซื้อ แยกตามเลขที่เอกสาร(ห้ามใช้)</a></li> -->
<!--          <li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  รายงาน สรุปยอดการสั่งซื้อ แยกตามรุ่นสินค้า(ห้ามใช้)</a></li> -->
<!--          <li><a href="index.php?content=po_by_product"><i class="fa fa-bar-chart"></i>  รายงาน สรุปยอดการสั่งซื้อ แยกตามรายการสินค้า</a></li>-->
<!--          <li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  รายงาน สรุปยอดการสั่งซื้อ แยกตามผู้ขาย(ห้ามใช้)</a></li>-->
<!--          <li class="divider"></li> -->
<!--          <li><a href="index.php?content=sale_summary"><i class="fa fa-bar-chart"></i>  รายงาน สรุปยอดการสั่งซื้อ เปรียบเทียบรายเดือน(ห้ามใช้)</a></li> -->
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ติดตาม</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=stock_backlogs"><i class="fa fa-bar-chart"></i>  รายงาน สินค้าค้างส่ง</a></li>
          <li><a href="index.php?content=order_backlogs"><i class="fa fa-bar-chart"></i>  รายงาน ออเดอร์ค้างส่ง</a></li>
          <li class="divider"></li>
        	<li><a href="index.php?content=sponsor_by_customer">	<i class="fa fa-bar-chart"></i>  รายงาน ยอดสปอนเซอร์ (ให้บุคคลภายนอก)</a></li>
          <li><a href="index.php?content=sponsor_summary">			<i class="fa fa-bar-chart"></i>  รายงานสรุป ยอดสปอนเซอร์ (ให้บุคคลภายนอก)</a></li>
<!--          <li><a href="index.php?content=support_by_employee">		<i class="fa fa-bar-chart"></i>  รายงาน ยอดเบิกอภินันทนาการ (สำหรับพนักงาน) </a></li> -->
<!--          <li><a href="index.php?content=support_summary">			<i class="fa fa-bar-chart"></i>  รายงานสรุป ยอดเบิกอภินันทนาการ (สำหรับพนักงาน) </a></li> -->
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ตรวจสอบ</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=discount_edit"><i class="fa fa-bar-chart"></i> รายงานการแก้ไขส่วนลด</a></li>
          <li><a href="index.php?content=sponsor_log"><i class="fa fa-bar-chart"></i> รายงาน การเพิ่ม/ลบ/แก้ไข สปอนเซอร์</a></li>
<!--     			<li><a href="index.php?content=support_log"><i class="fa fa-bar-chart"></i> รายงาน การเพิ่ม/ลบ/แก้ไข อภินันทนาการ</a></li> -->
<!--          <li><a href="index.php?content=delivery_fee"><i class="fa fa-bar-chart"></i> รายงาน ค่าจัดส่งสินค้า (ออนไลน์)</a></li> -->
          <li class="divider"></li>
          <li><a href="index.php?content=pdbcd"><i class="fa fa-bar-chart"></i> รายงานสินค้า แยกตามลูกค้า แสดงเลขที่เอกสาร</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=document_by_product_attribute"><i class="fa fa-bar-chart"></i> รายงานเอกสาร แยกตามรายการสินค้า</a></li>
          <li><a href="index.php?content=document_by_customer"><i class="fa fa-bar-chart"></i> รายงานเอกสาร แยกตามลูกค้า</a></li>
<!--          <li><a href="index.php?content=consignment_by_customer"><i class="fa fa-bar-chart"></i> รายงานบิลส่งของไปฝากขาย แยกตามลูกค้า เรียงตามเลขที่เอกสาร </a></li> -->
<!--          <li><a href="index.php?content=consign_by_customer"><i class="fa fa-bar-chart"></i> รายงานยอดขายสินค้าฝากขาย แยกตามลูกค้า เรียงตามเลขที่เอกสารตัดยอดฝากขาย </a></li> -->
<!--          <li><a href="index.php?content=sale_consign_product_by_customer"><i class="fa fa-bar-chart"></i> รายงานยอดขายสินค้าฝากขาย แยกตามลูกค้า แสดงรายการสินค้า </a></li> -->
<!--          <li class="divider"></li> -->
<!--          <li><a href="index.php?content=lend_by_doc"><i class="fa fa-bar-chart"></i> รายงานใบยืมสินค้าเรียงตามเลขที่เอกสาร</a></li> -->
<!--          <li><a href="index.php?content=lend_not_return"><i class="fa fa-bar-chart"></i> รายงานใบยืมสินค้า ยังไม่คืน แสดงรายการสินค้า /เลขที่เอกสาร /ผู้ยืม</a></li> -->
<!--          <li><a href="index.php?content=lend_by_product"><i class="fa fa-bar-chart"></i> รายงานใบยืมสินค้า แยกตามสินค้า</a></li> -->
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน วิเคราะห์</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=sale_product_deep_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์ขายแบบละเอียด</a></li>
          <li><a href="index.php?content=sponsor_product_deep_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์สปอนเซอร์แบบละเอียด</a></li>
<!--          <li><a href="index.php?content=support_product_deep_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์อภินันท์แบบละเอียด</a></li> -->
          <li><a href="index.php?content=stock_product_deep_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์สินค้าคงเหลือแบบละเอียด</a></li>
          <li><a href="index.php?content=received_product_deep_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์การรับสินค้าเข้าแบบละเอียด</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=customer_by_product"><i class="fa fa-bar-chart"></i> รายงานลูกค้า แยกตามสินค้า</a></li>
          <li><a href="index.php?content=customer_by_product_attribute"><i class="fa fa-bar-chart"></i> รายงานลูกค้า แยกตามรายการสินค้า</a></li>
          <li><a href="index.php?content=product_by_customer"><i class="fa fa-bar-chart"></i> รายงานสินค้า แยกตามลูกค้า</a></li>
          <li><a href="index.php?content=product_attribute_by_customer"><i class="fa fa-bar-chart"></i> รายงานรายการสินค้า แยกตามลูกค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=chart_movement_report"><i class="fa fa-line-chart"></i> กราฟรายงานภาพรวมการขาย</a></li>
          <li><a href="index.php?content=sale_chart_zone"><i class="fa fa-line-chart"></i> กราฟรายงานยอดขาย เปรียบเทียบพื้นที่การขาย</a></li>
          <li><a href="index.php?content=chart_move_movement_report"><i class="fa fa-line-chart"></i> กราฟรายงานภาพรวมสินค้าเปรียบเทียบยอด เข้า / ออก</a></li>
          <li><a href="index.php?content=attribute_chart_report"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์คุณลักษณะสินค้า</a></li>
          <li><a href="index.php?content=stock_chart_zone_report"><i class="fa fa-bar-chart"></i> กราฟรายงานการเคลื่อนไหวสินค้า แยกตามพื้นที่การขาย</a></li>
          <li><a href="index.php?content=attribute_analyz"><i class="fa fa-bar-chart"></i> รายงานวิเคราะห์จำนวนขาย แยกตามคุณลักษณะสินค้า</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน อื่นๆ</a>
      	<ul class="dropdown-menu">
<!--        	<li><a href="index.php?content=delivery_ticket"><i class="fa fa-ticket"></i> พิมพ์ตั๋วจัดส่ง</a></li> -->
<!--          <li class="divider"></li>-->
          <li><a href="index.php?content=sale_amount_report"><i class="fa fa-dashboard"></i> สรุปยอดขาย รวม</a></li>
          <li><a href="index.php?content=sale_leader_board"><i class="fa fa-dashboard"></i> สรุปยอดขาย แยกตามพนักงาน</a></li>
          <li><a href="index.php?content=sale_leader_group"><i class="fa fa-dashboard"></i> สรุปยอดขาย แยกตามพื้นที่</a></li>
          <li><a href="index.php?content=sale_calendar"><i class="fa fa-dashboard"></i> ปฏิทิน ยอดขาย</a></li>
<!--          <li class="divider"></li> -->
<!--          <li><a href="index.php?content=order_freq"><i class="fa fa-bar-chart"></i>  รายงานความถี่ในการสั่งสินค้า แยกตามช่วงเวลา</a></li> -->
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>  รายงาน ผู้บริหาร</a>
      	<ul class="dropdown-menu">
           <li><a href="index.php?content=sale_profit_by_item"><i class="fa fa-bar-chart"></i>  รายงานยอดขาย แยกตามรายการสินค้า แสดงกำไรขั้นต้น</a></li>
           <li><a href="index.php?content=sale_profit_by_customer"><i class="fa fa-bar-chart"></i>  รายงานยอดขาย แยกตามลูกค้า แสดงกำไรขั้นต้น</a></li>
        </ul>
      </li>
    </ul>
  </li>

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i>  การตั้งค่า</a>
    <ul class="dropdown-menu">
    	<li><a href="index.php?content=config"><i class="fa fa-cogs"></i>  การตั้งค่า</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=popup"><i class="fa fa-bullhorn"></i>  การแจ้งข่าว</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=Profile"><i class="fa fa-folder"></i>  เพิ่ม/แก้ไข โปรไฟล์</a></li>
      <li><a href="index.php?content=securable"><i class="fa fa-unlock-alt"></i>  กำหนดสิทธิ์</a></li>
    </ul>
  </li>

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-database"></i>  ฐานข้อมูล</a>
  	<ul class="dropdown-menu">
  		<li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tags"></i>  สินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=product"><i class="fa fa-tags"></i>  เพิ่ม/แก้ไข สินค้า</a></li>
          <li><a href="index.php?content=category"><i class="fa fa-bookmark"></i>  เพิ่ม/แก้ไข หมวดหมู่</a></li>
          <li><a href="index.php?content=product_group"><i class="fa fa-archive"></i>  เพิ่ม/แก้ไข กลุ่มสินค้า</a></li>
          <li><a href="index.php?content=color"><i class="fa fa-tint"></i>  เพิ่ม/แก้ไข สี</a></li>
          <li><a href="index.php?content=size"><i class="fa fa-tag"></i>  เพิ่ม/แก้ไข ไซด์</a></li>
<!--          <li><a href="index.php?content=attribute"><i class="fa fa-leaf"></i>  เพิ่ม/แก้ไข คุณลักษณะ</a></li> -->
        </ul>
  		</li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i>  คลังสินค้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=zone"><i class="fa fa-map-marker"></i>  เพิ่ม/แก้ไข โซนสินค้า</a></li>
    			<li><a href="index.php?content=warehouse"><i class="fa fa-home"></i>  เพิ่ม/แก้ไข คลังสินค้า</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i>  ลูกค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=customer"><i class="fa fa-user"></i>  เพิ่ม/แก้ไข ลูกค้า</a></li>
          <li><a href="index.php?content=address"><i class="fa fa-envelope"></i>  เพิ่ม/แก้ไข ที่อยู่ลูกค้า</a></li>
          <li><a href="index.php?content=group"><i class="fa fa-users"></i>  เพิ่ม/แก้ไข กลุ่มลูกค้า</a></li>
          <li><a href="index.php?content=customer_transfer"><i class="fa fa-retweet"></i>  โอน/ย้าย ลูกค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=add_sponsor"><i class="fa fa-user"></i>  เพิ่ม/แก้ไข รายชื่อสปอนเซอร์</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i>  พนักงาน</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=Employee"><i class="fa fa-user"></i>  เพิ่ม/แก้ไข พนักงาน</a></li>
          <li><a href="index.php?content=sale"><i class="fa fa-user"></i>  เพิ่ม/แก้ไข พนักงานขาย</a></li>
<!--          <li><a href="index.php?content=support"><i class="fa fa-user"></i>  เพิ่ม/แก้ไข  รายชื่ออภินันทนาการ</a></li> -->
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="index.php?content=supplier"><i class='fa fa-users'></i>  เพิ่ม/แก้ไข รายชื่อผู้ขาย</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=sender"><i class="fa fa-truck"></i>  เพิ่ม/แก้ไข รายชื่อผู้จัดส่ง</a></li>
      <li><a href="index.php?content=transport"><i class="fa fa-truck"></i>  เชื่อมโยงการจัดส่งของลูกค้า</a></li>
      <li class="divider"></li>
<!--      <li><a href="index.php?content=bank_account"><i class="fa fa-bank"></i>  เพิ่ม/แก้ไข บัญชีธนาคาร</a></li>
      <li class="divider"></li> -->
      <li><a href="index.php?content=product_db"><i class='fa fa-database'></i>  รายงาน รายการสินค้า</a></li>
<!--      <li><a href="index.php?content=export_product_db"><i class='fa fa-database'></i>  ส่งออกรายการสินค้า นำเข้า POS</a></li> -->
<!--      <li><a href="index.php?content=export_stock_zone"><i class='fa fa-database'></i>  ส่งออกยอดคงเหลือยกไปปลายงวด</a></li> -->
<!--      <li><a href="index.php?content=import_stock_zone"><i class='fa fa-database'></i>  นำเข้ายอดคงเหลือยกมาต้นงวด</a></li> -->
  	</ul>
  </li>

<!--
  <li><a href="../doc/index.php" target="_blank"><i class="fa fa-book"></i>  คู่มือการใช้งาน</a></li>
 -->
</ul>
<?php endif; ?>
