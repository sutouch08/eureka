function saveEdit(){
  swal({
    title:'ยืนยันการคืนสินค้า',
    text:'ต้องการยืนยันการคืนสินค้าหรือไม่ ?',
    showCancelButton:true,
    confirmButtonText:'ยืนยัน',
    closeOnConfirm:false
  }, function(){
    saveReturnEdit();
  });
}



function saveReturnEdit(){
  var id = $('#id_return_order').val();
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
    {'name' : 'id_return_order', 'value' : id},
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
    url:'controller/returnOrderController.php?saveReturnEdit',
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


function unSaveReturn(){
  var id = $('#id_return_order').val();
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการยกเลิกการบันทึกรับคืนสินค้าหรือไม่ ?',
    showCancelButton:true,
    confirmButtonColor: '#DD6855',
    confirmButtonText:'ยืนยัน',
    closeOnConfirm:false
  }, function(){
    load_in();
    $.ajax({
      url:'controller/returnOrderController.php?unSaveReturn',
      type:'GET',
      cache:false,
      data:{
        'id_return_order' : id
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            goEdit(id);
          }, 1500);

        }else{
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          });
        }
      }
    });
  });
}


function getEdit(){
  $('#date_add').removeAttr('disabled');
  $('#txt-bill').removeAttr('disabled');
  $('#btn-get-detail').removeAttr('disabled');
  $('#txt-customer').removeAttr('disabled');
  $('#remark').removeAttr('disabled');

  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}


function updateHeader(){
  var id = $('#id_return_order').val();
  var billCode = $('#txt-bill').val();
  var date_add = $('#date_add').val();
  var id_customer = $('#id_customer').val();
  var remark = $('#remark').val();

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

  load_in();
  $.ajax({
    url:'controller/returnOrderController.php?updateHeader',
    type:'POST',
    cache:false,
    data:{
      'id_return_order' : id,
      'date_add' : date_add,
      'order_code' : billCode,
      'id_customer' : id_customer,
      'remark' : remark
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        $('#date_add').attr('disabled', 'disabled');
        $('#txt-bill').attr('disabled', 'disabled');
        $('#btn-get-detail').attr('disabled', 'disabled');
        $('#txt-customer').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        swal({
          title:'Success',
          text:'ปรับปรุงข้อมูลเรียบร้อยแล้ว',
          type:'success',
          timer:1000
        });

      }else{
        swal({
          title:'Error!',
          text: rs,
          type:'error'
        });
      }
    }
  });
}



function removeDetail(row_id){
  $('#row-'+row_id).remove();
  reCal();
  updateNo();
}
