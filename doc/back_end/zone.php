<h1>เพิ่ม/แก้ไข โซนสินค้า</h1>
<p>
โซนสินค้า คือพื้นที่สำหรับจัดเก็บสินค้า ซึ่งในหนึ่งโซนสามารถจัดเก็บสินค้าได้มากกว่า 1 รายการ (แต่แนะนำว่า ควารเก็บ 1 รายการ/1 โซน) ในโปรแกรมนี้จะมีโซนของระบบ (ไม่สามารถลบหรือแก้ไขได้) คือ <br />
1. <strong style="color:red;">Buffer zone </strong> ระบบใช้สำหรับเก็บยอดสินค้าที่ถูกจัดออกจากโซน ขั้นตอนจัดเตรียมสินค้า ซึ่งยอดสินค้าจะถูกออกจากโซน แล้วมาเข้าใน Buffer zone และยอดจะถูกตัดออกจาก Buffer zone เมื่อเอกสารมีการเปิดบิล <br />
2. <strong style="color:red;">Cancle zone</strong> ระบบใช้สำหรับเก็บยอดสินค้าที่ถูกจัดเตรียมแล้วหรือมีการเปิดบิลแล้ว แล้วยกเลิก เพื่อให้เจ้าหน้าที่คลังสินค้า มาย้ายสินค้าจากโซนนี้ กลับเข้าโซนปกติต่อไป <br />
3. <strong style="color:red;">Move zone</strong> ระบบใช้สำหรับเก็บยอดสินค้าที่ถูกย้ายออกจากโซน ในขั้นตอนการย้ายพื้นที่จัดเก็บ และ ขั้นตอนโอนสินค้าระหว่างคลัง <br />
ซึ่งทั้ง 3 โซนที่กล่าวมานี้ จะไม่ผูกติดกับคลังใดๆ ความสัมพันธ์ระหว่างโซนต่างๆ และ คลัง ดูได้จากภาพด้านล่าง
</p>
<img class="pic" src="images/invent/warehouse_and_zone_chart.png"  /> <br />

<p>
จากรูป จะเห็นความสัมพันธ์ระหว่างคลังและโซน นอกจากโซนของระบบแล้ว โซนทั้งหมดจะต้องอยู่ในคลัง และ ทุกคลังสามารถเพิ่มโซนได้ไม่จำกัด ตราบใดที่ ชื่อและบาร์โค้ดไม่ซ้ำกัน วิธีเพิ่มโซนสินค้ามีดังนี้
</p>

<p>&nbsp;</p>
<p>
<img class="pic" src="images/invent/1.1.9.png"  /> <br />
<img class="pic" src="images/invent/1.2.1.png"  /> <br />
<img class="pic" src="images/invent/1.2.2.png"  /> <br />
</p>

<h2></h2>
<p style="width:100%; text-align:center">
หัวข้อก่อนหน้า : <a href="index.php?content=warehouse">เพิ่ม/แก้ไข คลังสินค้า</a>&nbsp;&nbsp; | &nbsp;&nbsp;  <a href="#top">ขึ้นบน</a>&nbsp;&nbsp; | &nbsp;&nbsp; หัวข้อถัดไป : <a href="index.php?content=color">เพิ่ม/แก้ไข สี</a>
</p>