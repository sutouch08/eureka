<div class="row" id="item-control">
  <div class="col-sm-2 padding-5 first">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm" id="barcode" placeholder="ระบุโซนที่จะรับเข้า" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>รหัสสินค้า</label>
    <input type="text" class="form-control input-sm" id="pdCode" placeholder="ระบุโซนที่จะรับเข้า" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>ราคา</label>
    <input type="number" class="form-control input-sm text-center" id="price" value="0.00" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>จำนวน</label>
    <input type="number" class="form-control input-sm text-center" id="qty" value="1" />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">change zone</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-add-item" onclick="addDetail()">เพิ่มรายการ</button>
  </div>
  <input type="hidden" id="id_pa" />

<script id="row-template" type="text/x-handlebarsTemplate">
  <tr class="font-size-12" id="row-{{id}}">
    <td class="middle text-center no">{{no}}</td>
    <td class="middle">{{barcode}}</td>
    <td class="middle">{{product}}</td>
    <td class="middle text-right price" id="price-{{id}}">{{price}}</td>
    <td class="middle text-right">
      <input type="number" class="form-control input-sm text-right qty" id="qty-{{id}}" value="{{qty}}" />
    </td>
    <td class="middle text-right" id="cnAmount-{{id}}">{{amount}}</td>
    <td class="middle text-right">
      <button type="button" class="btn btn-xs btn-danger" onclick="removeRow({{id}})">
        <i class="fa fa-trash"></i>
      </button>
    </td>
  </tr>
</script>
  <script src="script/return_order/return_add_item.js"></script>
</div>
<hr id="item-hr"/>
