function addNew(){
  var id_supplier = $('#id_supplier').val();
  var id_po = $('#id_po').val();
  var po = $('#po').val();
  var invoice = $('#invoice').val();
  var dateAdd = $('#date_add').val();
  var id_zone = $('#id_zone').val();
  var zoneCode = $('#zone-code').val();
  var zoneName = $('#zone-box').val();
  var remark = $('#remark').val();

  if(id_po == "" && po.length > 0){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }


  if(id_po != '' && po.length == 0){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }


  if(id_supplier == ""){
    swal('ผู้จำหน่ายไม่ถูกต้อง');
    return false;
  }


  if(!isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(id_zone == '' || zoneCode.length == 0 || zoneName.length == 0){
    swal('โซนไม่ถูกต้อง');
    return false;
  }


  $.ajax({
    url:"controller/receiveProductController.php?add_new",
    type:"POST",
    cache:false,
    data:{
      "id_supplier" : id_supplier,
      "invoice" : invoice,
      "po_reference" : po,
      "id_po" : id_po,
      "id_zone" : id_zone,
      "date_add" : dateAdd,
      "remark" : remark
    },
    success: function(rs)
    {
      var rs = parseInt(rs);
      if(! isNaN(rs)){
        window.location.href = 'index.php?content=receive_product&edit=y&id_receive_product='+rs;
      }else{
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}


$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});
