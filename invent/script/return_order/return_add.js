$('#date_add').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(){
    $('#txt-bill').focus();
  }
});


$('#txt-customer').autocomplete({
  source:'controller/customerController.php?getCustomer',
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    if(rs == 'ไม่พบข้อมูล'){
      $(this).val('');
      $('#id_customer').val('');
      $('#id_emp').val('');
    }else{
      var arr = rs.split(' | ');
      $(this).val(arr[0]);
      $('#id_customer').val(arr[1]);
      updateSaleName();
    }
  }
});


function updateSaleName(){
  var id = $('#id_customer').val();
  $.ajax({
    url:'controller/customerController.php?getSaleIdByCustomer',
    type:'GET',
    cache:false,
    data:{
      'id_customer' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        var ds = $.parseJSON(rs);
        $('#txt-emp').val(ds.name);
      }
    }
  });
}



$('#txt-bill').keyup(function(e){
  if(e.keyCode == 13){
    getBillDetail();
  }
});



$('#txt-customer').keyup(function(e){
  if(e.keyCode == 13){
    if(id_customer != ''){
      $('#remark').focus();
    }
  }
});




function getBillDetail(){
  var reference = $('#txt-bill').val();
  if(reference.length == 0){
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/returnOrderController.php?getBillDetail',
    type:'GET',
    cache:'false',
    data:{
      'reference' : reference
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        ds = $.parseJSON(rs);
        $('#txt-customer').val(ds.customerName);
        $('#id_customer').val(ds.id_customer);
        $('#txt-emp').val(ds.empName);
        $('#txt-customer').attr('disabled', 'disabled');
        source = $('#bill-template').html();
        data = ds.data;
        output = $('#result');
        render(source, data, output);
        //inputInit();
        reCal();
        //hideControl();
      }else{
        swal('Error!', rs, 'error');
        $('#txt-customer').val('');
        $('#id_customer').val('');
        $('#txt-emp').val('');
        $('#txt-customer').removeAttr('disabled');
        $('#result').html('');
      }

      $('#btn-save').removeAttr('disabled');
    }
  });
}




// function inputInit(){
//   $('.qty').keyup(function(e){
//     if(e.keyCode == 13){
//       $('.qty').eq($(this).index() + 1).focus();
//     }else{
//       ids = $(this).attr('id').split('-');
//       id = ids[1];
//       limit = parse_int($('#qty-'+id).text());
//       qty = parse_int($(this).val());
//
//       // if(qty > limit || qty < 0){
//       //   $(this).val('');
//       // }
//       reCal();
//     }
//
//   });
// }



function reCal(){
  var sumQty = 0;
  var sumAmount = 0;
  $('.qty').each(function(index, el) {
    ids = $(this).attr('id').split('-');
    id  = ids[1];
    qty = parseInt($(this).val());
    qty = isNaN(qty) ? 0 : qty;
    price = parseFloat($('#price-'+id).text());
    price = isNaN(price) ? 0 : price;

    amount = (qty * price);
    sumQty += qty;
    sumAmount += amount;
    $('#cnAmount-'+id).text(addCommas(amount.toFixed(2)));
  });

  $('#sumQty').text(addCommas(sumQty));
  $('#sumAmount').text(addCommas(sumAmount.toFixed(2)));
}



function save(){
  swal({
    title:'ยืนยันการคืนสินค้า',
    text:'ต้องการยืนยันการคืนสินค้าหรือไม่ ?',
    showCancelButton:true,
    confirmButtonText:'ยืนยัน',
    closeOnConfirm:false
  }, function(){
    saveReturn();
  });
}



function saveReturn(){
  var billCode = $('#txt-bill').val();
  var date_add = $('#date_add').val();
  var id_customer = $('#id_customer').val();
  var remark = $('#remark').val();
  var id_zone = $('#id_zone').val();
  var count = 0;

  if(billCode.length == 0){
    swal('กรุณาระบุเลขที่บิลขาย');
    return false;
  }


  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(id_customer == ''){
    swal('กรุณาระบุลูกค้า');
    return false;
  }


  if(id_zone == ''){
    swal('กรุณาระบุโซนรับสินค้า');
    return false;
  }


  ds = [
    {'name' : 'billCode', 'value' : billCode},
    {'name' : 'date_add', 'value' : date_add},
    {'name' : 'id_customer', 'value' : id_customer},
    {'name' : 'remark' , 'value' : remark},
    {'name' : 'id_zone', 'value' : id_zone}
  ];

  $('.qty').each(function(index, el) {
    ids = $(this).attr('id').split('-');
    id = ids[1];
    qty = parse_int($(this).val());
    if(qty > 0){
      name = 'qty['+id+']';
      ds.push({'name' : name, 'value' : qty});
      count++;
    }

  });


  $('.price').each(function(index, el) {
    ids = $(this).attr('id').split('-');
    id = ids[1];
    price = removeCommas($(this).text());
    price = parse_float(price);
    name = 'price['+id+']';
    ds.push({'name' : name, 'value' : price});
  });

  if(count == 0){
    swal('ไม่พบรายการรับคืน');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/returnOrderController.php?addNew',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      load_out();
      var rs = parseInt($.trim(rs));
      if(! isNaN(rs)){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          viewDetail(rs);
        }, 1200);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}



$('#zoneCode').keyup(function(e){
  if(e.keyCode == 13){
      setZone();
  }
});



function changeZone(){
  $('#id_zone').val('');
  $('#zoneCode').removeAttr('disabled').val('');
  $('#zoneName').removeAttr('disabled').val('');
  $('#btn-change-zone').addClass('hide');
  $('#btn-set-zone').removeClass('hide');
  $('#zoneCode').focus();
}


function setZone(){
  var code = $('#zoneCode').val();
  if(code.length > 0){
    $.ajax({
      url:'controller/zoneController.php?getZone',
      type:'GET',
      cache:false,
      data:{
        'zoneCode' : code
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(isJson(rs)){
          var ds = $.parseJSON(rs);
          $('#id_zone').val(ds.id_zone);
          $('#zoneName').val(ds.zone_name);
          $('#zoneCode').val(ds.barcode_zone);

          //-----
          $('#zoneCode').attr('disabled', 'disabled');
          $('#zoneName').attr('disabled', 'disabled');
          $('#btn-set-zone').addClass('hide');
          $('#btn-change-zone').removeClass('hide');
          updateZone();
        }else{
          swal('รหัสโซนไม่ถูกต้อง');
        }
      }
    });
  }
}


function updateZone(){
  var id = $('#id_return_order').val();
  var id_zone = $('#id_zone').val();

  //--- ถ้ายังไม่เคยบันทึก (กรณี add new )
  if(id == ''){
    return false;
  }

  //--- ถ้ายังไม่มี id_zone
  if(id_zone == ''){
    return false;
  }

  $.ajax({
    url:'controller/returnOrderController.php?updateZone',
    type:'POST',
    cache:false,
    data:{
      'id_return_order' : id,
      'id_zone' : id_zone
    },
    success:function(rs){
      console.log(rs);
    }
  });
}
