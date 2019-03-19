function create_menu(basepath)
{
	var base = (basepath == 'null') ? '' : basepath;

	document.write(
		'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +
		'<td class="td" valign="top">' +

		'<h3>เริ่มต้นระบบ</h3>' +
		'<ul>' +
			'<li><a href="'+base+'index.php?content=login">การเข้าใช้ระบบ</a></li>' +
			'<li><a href="'+base+'index.php?content=pre_data">การเตรียมข้อมูล สำหรับเริ่มต้นระบบ</a></li>' +
			'<li><a href="'+base+'index.php?content=profile">สร้างโปรไฟล์</a></li>' +
			'<li><a href="'+base+'index.php?content=permission">กำหนดสิทธิ์</a></li>' +
			'<li><a href="'+base+'index.php?content=employee">เพิ่ม/แก้ไข พนักงาน</a></li>' +
			'<li><a href="'+base+'index.php?content=sale">เพิ่ม/แก้ไข พนักงานขาย</a></li>' +
			'<li><a href="'+base+'index.php?content=warehouse">เพิ่ม/แก้ไข คลังสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=zone">เพิ่ม/แก้ไข โซนสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=color">เพิ่ม/แก้ไข สี</a></li>' +
			'<li><a href="'+base+'index.php?content=size">เพิ่ม/แก้ไข ไซด์</a></li>' +
			'<li><a href="'+base+'index.php?content=attribute">เพิ่ม/แก้ไข คุณลักษณะ</a></li>' +
			'<li><a href="'+base+'index.php?content=category">เพิ่ม/แก้ไข หมวดหมู่สินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=product">เพิ่ม/แก้ไข สินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=customer_group">เพิ่ม/แก้ไข กลุ่มลูกค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=customer">เพิ่ม/แก้ไข ลูกค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=setting">กำหนดค่าต่างๆ</a></li>' +
		'</ul>' +
		
		'</td><td class="td_sep" valign="top">' +

		'<h3>สินค้า</h3>' +
		'<ul>' +
			'<li><a href="'+base+'index.php?content=product">เพิ่ม/แก้ไข สินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=category">เพิ่ม/แก้ไข หมวดหมู่สินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=color">เพิ่ม/แก้ไข สี</a></li>' +
				'<li><a href="'+base+'index.php?content=size">เพิ่ม/แก้ไข ไซด์</a></li>' +
			'<li><a href="'+base+'index.php?content=attribute">เพิ่ม/แก้ไข คุณลักษณะ</a></li>' +
		'</ul>' +
		
		
		'<h3>คลังสินค้า</h3>' +
		'<ul>' +
			'<li><a href="'+base+'index.php?content=receive_product">รับสินค้าเข้า</a></li>' +
			'<li><a href="'+base+'index.php?content=return_product">รับคืนสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=requisition">เบิกสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=lend">ยืมสินค้าและการคืนสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=move_zone">ย้ายพื้นที่จัดเก็บ</a></li>' +
			'<li><a href="'+base+'index.php?content=transfer">โอนสินค้าระหว่างคลัง</a></li>' +			
			'<li><a href="'+base+'index.php?content=check_zone">ตรวจนับสินค้า(เปรียบเทียบยอด)</a></li>' +
			'<li><a href="'+base+'index.php?content=adjust">ปรับปรุงยอดสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=warehouse">เพิ่ม/แก้ไข คลังสินค้า</a></li>' +
			'<li><a href="'+base+'index.php?content=zone">เพิ่ม/แก้ไข โซนสินค้า</a></li>' +
		'</ul>' +
		'<h3>ตรวจนับสินค้า</h3>' +
		'<ul>' +
		'<li><a href="'+base+'index.php?content=check_stock_review">วิธีการตรวจนับสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=open_check">เปิด/ปิด การตรวจนับสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=check_stock">ตรวจนับสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=check_moniter">ดูภาพรวมการตรวจนับสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=check_summary">สรุปยอดการตรวจนับสินค้า</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +
		
		'<h3>ออเดอร์</h3>' +
		'<ul>' +
		'<li><a href="'+base+'index.php?content=order">ออเดอร์</a></li>' +
		'<li><a href="'+base+'index.php?content=order_add">เพิ่มออเดอร์ใหม่</a></li>' +
		'<li><a href="'+base+'index.php?content=order_state">เปลี่ยนสถานะออเดอร์</a></li>' +
		'<li><a href="'+base+'index.php?content=order_edit">แก้ไขออเดอร์</a></li>' +
		'<li><a href="'+base+'index.php?content=order_cancle">ยกเลิกออเดอร์</a></li>' +
		'<li><a href="'+base+'index.php?content=search">ค้นหาออเดอร์</a></li>' +
		'<li><a href="'+base+'index.php?content=prepare">การจัดสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=qc">การตรวจสินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=bill">การเปิดบิล</a></li>' +
		'</ul>' +
		
		'<h3>สปอนเซอร์</h3>' +
		'<ul>' +
		'<li><a href="'+base+'index.php?content=sponsor">สปอนเซอร์</a></li>' +
		'</ul>' +
		
		'<h3>ฝากขาย</h3>' +
		'<ul>' +
		'<li><a href="'+base+'index.php?content=consign">ฝากขาย</a></li>' +
		'</ul>' +

		'<h3>การกำหนดค่า</h3>' +
		'<ul>' +
		'<li><a href="'+base+'index.php?content=">กำหนดค่า ทั่วไป</a></li>' +
		'<li><a href="'+base+'index.php?content=">กำหนดค่า สินค้า</a></li>' +
		'<li><a href="'+base+'index.php?content=">กำหนดค่า เอกสาร</a></li>' +
		'<li><a href="'+base+'index.php?content=">เพิ่ม/แก้ไข พนักงาน</a></li>' +
		'<li><a href="'+base+'index.php?content=">เพิ่ม/แก้ไข พนักงานขาย</a></li>' +
		'<li><a href="'+base+'index.php?content=">เพิ่ม/แก้ไข โปรไฟล์</a></li>' +
		'<li><a href="'+base+'index.php?content=">กำหนดสิทธิ์</a></li>' +
		'<li><a href="'+base+'index.php?content=">การแจ้งเตือน</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>รายงาน</h3>' +
		'<ul>' +
		'<li><a href="'+base+'libraries/caching.html">Caching Class</a></li>' +
		'<li><a href="'+base+'database/index.html">Database Class</a></li>' +
		'<li><a href="'+base+'libraries/javascript.html">Javascript Class</a></li>' +
		'</ul>' +

		'<h3>รายงานวิเคราะห์</h3>' +
		'<ul>' +
		'<li><a href="'+base+'helpers/array_helper.html">Array Helper</a></li>' +
		'<li><a href="'+base+'helpers/captcha_helper.html">CAPTCHA Helper</a></li>' +
		'<li><a href="'+base+'helpers/cookie_helper.html">Cookie Helper</a></li>' +
		'<li><a href="'+base+'helpers/date_helper.html">Date Helper</a></li>' +
		'<li><a href="'+base+'helpers/directory_helper.html">Directory Helper</a></li>' +
		'<li><a href="'+base+'helpers/download_helper.html">Download Helper</a></li>' +
		'<li><a href="'+base+'helpers/email_helper.html">Email Helper</a></li>' +
		'<li><a href="'+base+'helpers/file_helper.html">File Helper</a></li>' +
		'<li><a href="'+base+'helpers/form_helper.html">Form Helper</a></li>' +
		'<li><a href="'+base+'helpers/html_helper.html">HTML Helper</a></li>' +
		
		'</ul>' +

		'</td></tr></table>');
}