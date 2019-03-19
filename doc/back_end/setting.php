<h1>การกำหนดค่าต่างๆ</h1>
<p>
เราสามารถกำหนดค่าต่างๆของระบบได้ 3 หัวข้อ คือ กำหนดค่าทั่วไป, กำหนดค่าสินค้า และ กำหนดค่าเอกสาร โดยระบบจะมีค่าเริ่มต้นมาให้แล้วแต่เราสามารถเปลี่ยนแปลงการตั้งค่าเองได้ ดั้งนี้
</p>
<h2>การกำหนดค่าทั่วไป</h2>
<p>
<img class="pic" src="images/invent/1.6.7.png"  /> <br />
<img class="pic" src="images/invent/1.6.8.png"  /> <br />
2. ใส่ชื่อบริษัท ซึ่งชื่อบริษัทจะถูกนำไปแสดงบนเอกสารพิมพ์บางรายการ<br />
3. ใส่แบรนด์สินค้า<br />
4. ใส่ที่อยู่บริษัท ซึ่งที่อยู่จะถูกนำไปแสดงบนเอกสารพิมพ์บางรายการ<br />
5. ใส่รหัสไปรษณีย์<br />
6. ใส่เบอร์โทรศัพท์ของบริษัท<br />
7. ใส่เบอร์แฟกซ์<br />
8. ใส่เลขประจำตัวผู้เสียภาษี<br />
9. ใส่อีเมล์ของผู้ดูแลระบบ<br />
10. เลือกรูปแบบบาร์โค้ดที่ใช้ (ใช้สำหรับพิมพ์เอกสารที่ต้องมีบาร์โค้ด)<br />
11. กำหนด URL หน้าหลักของเว็บไซต์<br />
12. กำหนดว่า อนุญาติให้สต็อกติดลบได้หรือไม่<br />
13. กำหนดจำนวนวันที่จะให้ระบบดึงมาแสดงในหน้าออเดอร์ <br />
14. ใส่รายละเอียดการชำระเงิน เช่น การเขียนเช็ค หรือ เลขที่บัญชีสำหรับโอนเงิน ซึ่งรายละเอียดนี้จะแสดงในหน้ายืนยันคำสั่งซื้อของลูกค้า<br />
15. ใส่อีเมล์เพื่อให้ระบบส่งอีเมล์แจ้งเตือนไปยังอีเมล์ที่กำหนดเมื่อมีลูกค้าสั่งสินค้า สามารถกำหนดได้มากกว่า 1 โดยใช้เครื่องหมาย " , " คั่นระหว่างอีเมล์<br />
</p>

<h2>การกำหนดค่าสินค้า</h2>
<p>
<img class="pic" src="images/invent/1.6.9.png"  /> <br />
<img class="pic" src="images/invent/1.7.0.png"  /> <br />
1. เลือกเมนู กำหนดค่า > สินค้า<br />
2. กำหนดอายุของสินค้าใหม่ ซึ่งเมื่อเราเพิ่มสินค้าเข้าในระบบสินค้าจะถูกแสดงในกลุ่มสินค้ามาใหม่ซึ่งจะมีป้ายคำว่า "ใหม่" แสดงอยู่บนรูปสินค้า จนกว่าจะเกินจำนวนวันที่กำหนด <br />
3. กำหนดจำนวนสินค้าใหม่ที่จะแสดงในบล็อก <a href="javascript:void(0);" onclick="document.getElementById('new_arrivals').style.display='inline';">New arrivals</a> โดยระบบจะดึงรุ่งสินค้าที่เพิ่มเข้าไปใหม่ล่าสุดมาแสดงตามจำนวนรายการที เรากำหนด<br />
4. กำหนดจำนวนสินค้าที่จะแสดงในบล็อก <a href="javascript:void(0);" onclick="document.getElementById('features').style.display='inline';">Features products</a> โดยเราควรกำหนดจำนวนที่จะแสดงหน้าแรกให้เหมาะสมกับการแสดงผล หากน้อยไปจะไม่น่าสนใจหรือใช้งานยาก หากมากเกินไปจะทำให้โหลดหน้าเว็บช้า ซึ่งรายการสินค้าที่จะแสดงในบล็อก Featrues product นี้ จะเป็นสินค้าที่อยู่ในหมวดหมู่ HOME หากเราต้องการให้สินค้าแสดงที่หน้านี้ต้องกำหนดให้หมวดหมู่สินค้าอยู่ในหมวดหมู่ HOME <br />
5. กำหนดจำนวนสินค้าสูงสุดที่จะแสดงให้ลูกค้าเห็น ตัวอย่างเช่น 300 เมื่อสินค้ามีสต็อก 1000 ระบบจะแสดงให้ลูกค้าเห็นไม่เกิน 300 แต่หากสินค้ามีน้อยกว่า 300 ระบบจะแสดงจำนวนจริงให้ลูกค้าเห็น<br />
6. กำหนดแนวการแสดงผลตารางสำหรับสั่งสินค้า<br />
</p>

<h2>การกำหนดค่าเอกสาร</h2>
<p>
<img class="pic" src="images/invent/1.7.1.png"  /> <br />
<img class="pic" src="images/invent/1.7.2.png"  /> <br />
</p>
<div id="new_arrivals" class="lightbox" style="display:none" onclick="document.getElementById('new_arrivals').style.display='none';">
<table class="lightbox_table"><tr><td class="lightbox_table_cell" align="center">
<div id="lightbox_content" style="width:1000px;">
<img class="pic" src="images/invent/new_arrivals.png"  />
</div>
</td></tr></table>
</div>
<div id="features" class="lightbox" style="display:none" onclick="document.getElementById('features').style.display='none';">
<table class="lightbox_table"><tr><td class="lightbox_table_cell" align="center">
<div id="lightbox_content" style="width:1000px;">
<img class="pic" src="images/invent/features_product.png"  />
</div>
</td></tr></table>
</div>
<h2></h2>
<p style="width:100%; text-align:center">
หัวข้อก่อนหน้า : <a href="index.php?content=customer">เพิ่ม/แก้ไข ลูกค้า</a>&nbsp;&nbsp; | &nbsp;&nbsp;  <a href="#top">ขึ้นบน</a>
</p>