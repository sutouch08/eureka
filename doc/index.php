<?php 
$content = "";

$page = (isset($_GET['content'])&& $_GET['content'] !='')?$_GET['content']:'';
switch($page){
	/********** สารบัญ  ********/
		case "toc" :
		$content = "toc.php";
		$title = "Table of contents";
		break;
		
		case 'login':
		$content = "back_end/login.php";
		$title = "เข้าใช้งานระบบ";
		break;
		
		case 'pre_data':
		$content = "back_end/pre_data.php";
		$title = "เตรียมข้อมูล";
		break;
		
		case 'profile':
		$content = "back_end/profile.php";
		$title = "สร้างโปรไฟล์";
		break;
		
		case 'permission':
		$content = "back_end/permission.php";
		$title = "กำหนดสิทธิ์";
		break;
		
		case 'employee':
		$content = "back_end/employee.php";
		$title = "เพิ่ม/แก้ไข พนักงาน";
		break;
		
		case 'sale':
		$content = "back_end/sale.php";
		$title = "เพิ่ม/แก้ไข พนักงานขาย";
		break;
		
		case 'warehouse':
		$content = "back_end/warehouse.php";
		$title = "เพิ่ม/แก้ไข คลังสินค้า";
		break;
		
		case 'zone':
		$content = "back_end/zone.php";
		$title = "เพิ่ม/แก้ไข โซน";
		break;
		
		case 'color':
		$content = "back_end/color.php";
		$title = "เพิ่ม/แก้ไข สี";
		break;
		
		case 'size':
		$content = "back_end/size.php";
		$title = "เพิ่ม/แก้ไข ไซด์";
		break;
		
		case 'attribute':
		$content = "back_end/attribute.php";
		$title = "เพิ่ม/แก้ไข คุณลักษณะ";
		break;
		
		case 'category':
		$content = "back_end/category.php";
		$title = "เพิ่ม/แก้ไข หมวดหมู่สินค้า";
		break;
		
		case 'product':
		$content = "back_end/product.php";
		$title = "เพิ่ม/แก้ไข สินค้า";
		break;
		
		case 'setting':
		$content = "back_end/setting.php";
		$title = "กำหนดค่าต่างๆ";
		break;
		
		/************** คลังสินค้า ****************/
		case 'receive_product' :
		$content = "back_end/receive_product.php";
		$title = "รับสินค้าเข้า";
		break;
		
		case "return_product":
		$content = "back_end/return_product.php";
		$title = "รับคืนสินค้า";
		break;
		
		case "requisition":
		$content = "back_end/requisition.php";
		$title = "เบิกสินค้า";
		break;
		
		case "lend" :
		$content = "back_end/lend.php";
		$title = "ยืมสินค้า";
		break;
		
		case "transfer" :
		$content = "back_end/transfer.php";
		$title = "โอนสินค้าระหว่างคลัง";
		break;
		
		case "move_zone" :
		$content = "back_end/move_zone.php";
		$title = "ย้ายพื้นที่จัดเก็บ";
		break;
		
		case "check_zone" :
		$content = "back_end/check_zone.php";
		$title = "ตรวจสอบยอดสินค้าตามโซน";
		break;
		
		case "adjust" :
		$content = "back_end/adjust.php";
		$title = "ปรับปรุงยอดสต็อก";
		break;
		
		/*************************** ลูกค้า *********************************/
		
		case "customer_group":
		$content = "back_end/customer_group.php";
		$title = "เพิ่ม/แก้ไข กลุ่มลูกค้า";
		break;
		
		case 'customer':
		$content = "back_end/customer.php";
		$title = "เพิ่ม/แก้ไข ลูกค้า";
		break;
		
		case "add_sponsor" :
		$content = "back_end/add_sponsor.php";
		$title = "เพิ่มสปอนเซอร์";
		break;
		
		case "customer_transfer" :
		$content = "back_end/customer_transfer.php";
		$title = "โอนย้ายลูกค้า";
		break;
		
		/*************************** ตรวจนับสินค้า **********************/
		
		case "check_stock_review" :
		$content = "back_end/check_stock_review.php";
		$title = "ตรวจนับสินค้า";
		break;
		
		case "check_stock" :
		$content = "back_end/check_stock.php";
		$title = "ตรวจนับสินค้า";
		break;
		
		case "open_check" :
		$content = "back_end/open_check.php";
		$title = "เปิด/ปิด การตรวจนับ";
		break;
		
		case "check_moniter" :
		$content = "back_end/check_moniter.php";
		$title = "ภาพรวมการตรวจนับ";
		break;
		
		case "check_summary" :
		$content = "back_end/check_summary.php";
		$title = "สรุปยอดการตรวจนับ";
		break;
		
		/****************************  ออเดอร์  *****************/
		
		case "order" :
		$content = "back_end/order.php";
		$title = "Order";
		break;
		case "order_add" :
		$content = "back_end/order_add.php";
		$title = "Add new order";
		break;
		case "order_state" :
		$content = "back_end/order_state.php";
		$title = "Change order state";
		break;
		case "order_edit" :
		$content = "back_end/order_edit.php";
		$title = "Edit order";
		break;
		case "order_cancle" :
		$content = "back_end/order_cancle.php";
		$title = "Cancle order";
		break;
		case "order_search" :
		$content = "back_end/order_search.php";
		$title = "Order";
		break;
		
		case "prepare" :
		$content = "back_end/prepare.php";
		$title = "การจัดสินค้า";
		break;
		
		case "qc" :
		$content = "back_end/qc.php";
		$title = "การตรวจสินค้า";
		break;
		
		case "bill" :
		$content = "back_end/bill.php";
		$title = "การเปิดบิล";
		break;
		
		/************** สปอนเซอร์  ******************/
		case "sponsor" :
		$content = "back_end/sponsor.php";
		$title = "Sponsor";
		break;
		
		/************** ฝากขาย  ******************/
		
		case "consign" : 
		$content = "back_end/consign.php";
		$title = "ฝากขาย";
		break;
		
		/****************** กำหนดค่า ***************/
		
		case "config_general" :
		$content = "back_end/config_general.php";
		$title = "กำหนดค่าทั่วไป";
		break;
		
		case "config_product" :
		$content = "back_end/config_product.php";
		$title = "กำหนดค่าสินค้า";
		break;
		
		case "config_document" :
		$content = "back_end/config_document.php";
		$title = "กำหนดค่าเอกสาร";
		break;
		
		case "pop_up" :
		$content = "back_end/pop_up.php";
		$title = "แจ้งข่าว";
		break;
		
		/*********************     **********************/
		
		default :
		$content = "main.php";
		$title = "ยินดีต้อนรับ";
		break;
}

require_once 'template.php';

?>