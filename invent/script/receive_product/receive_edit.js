function saveEdit(){
  var id = $('#id_receive_product').val();
  var id_po = $('#id_po').val();
  var id_zone = $('#id_zone').val();
  var unSaveItem = $('.receive-box').length; //--- รายการที่ยังไม่บันทึก
  var savedItem = $('.received-item').length;
  var max = unSaveItem + savedItem;
  var total = 0;
  if(isNaN(parseInt(id_zone))){
    swal("โซนไม่ถูกต้อง");
    return false;
  }

  if(max > 0){

    $('#btn-save').attr('disabled', 'disabled');

    $('#btn-change-zone').attr('disabled', 'disabled');

    load_in();

    $('.receive-box').each(function(index){
      let arr = $(this).attr('id').split('-');
      let id_pa = arr[1];
      let res = receiveItem(id, id_po, id_zone, id_pa);
      if( res === false){
        $('#btn-save').removeAttr('disabled');
        $('#btn-change-zone').removeAttr('disabled');
        return false;
      }else if(res === true){
        total++;
        if(total == unSaveItem){
          review();
        }
      }
    });

    load_out();
    if(total == 0){
      $('#btn-save').removeAttr('disabled');
      $('#btn-change-zone').removeAttr('disabled');
       swal('กรุณาระบุจำนวนที่จะรับเข้า');
       return false;
    }else{
      setTimeout(function(){
        review();
      },1500);
    }

  }
}





function updateReceiveProduct(){
  var id = $('#id_receive_product').val();
  var id_po = $('#id_po').val();
  var po  = $('#po').val();
  var remark = $('#remark').val();
  var invoice = $('#invoice').val();
  var date_add = $('#date_add').val();
  $.ajax({
    url:'controller/receiveProductController.php?updateReceiveProduct',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id,
      'date_add' : date_add,
      'id_po' : id_po,
      'po_reference' : po,
      'invoice' : invoice,
      'remark' : remark
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        $('#date_add').attr('disabled', 'disabled');
        $('#po').attr('disabled', 'disabled');
        $('#invoice').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#btn-get-po').attr('disabled', 'disabled');
        $('#btn-edit').removeClass('hide');
        $('#btn-save-edit').addClass('hide');
      }
    }
  });
}




function receiveItem(id, id_po, id_zone, id_pa){
  var qty = parseInt($('#receive-'+id_pa).val());
  if(isNaN(qty) || qty < 0){
    qty = 0;
  }

  var id_pd = $('#productId-'+id_pa).val();

  if(qty == 0){
    return 'novalue';
  }

  if(qty !== 0){
    var data = [
      {"name" : "id_receive_product", "value" : id},
      {"name" : "id_po", "value" : id_po},
      {"name" : "id_product", "value" : id_pd},
      {"name" : "id_product_attribute", "value" : id_pa},
      {"name" : "qty", "value" : qty},
      {"name" : "id_zone", "value" : id_zone}
    ];

    $.ajax({
      url:'controller/receiveProductController.php?receiveItem',
      type:'POST',
      cache:false,
      data: data,
      success:function(rs){
        if(rs == 'success'){
          setReceived(id_pa, qty);
          }else{
            swal({
              title:'Error!',
              text:'พบข้อผิดพลาดระหว่างการติดต่อกับ SERVER',
              type:'error'
            });

            return false;
          }
      }
    });
  }else{
    setReceived(id_pa, qty);
  }
  return true;
}





function setReceived(id, qty){
  $('#label-'+id).text(qty);
  $('#receive-'+id).addClass('hide');
  $('#label-'+id).removeClass('hide');
  $('#btn-remove-'+id).addClass('hide');
}


function setUnReceived(id){
  $('#receive-'+id).removeClass('hide');
  $('#label-'+id).addClass('hide');
  $('#btn-receive-'+id).removeClass('hide');
  $('#btn-remove-'+id).addClass('hide');
}


$('.receive-box').keyup(function(){
  if(isNaN(parseInt($(this).val()))) {
    $(this).val(0);
  }
});


function deleteRow(id){
  $('#row-'+id).remove();
  updateNo();
}


function getEdit(){
  $('#date_add').removeAttr('disabled');
  $('#po').removeAttr('disabled');
  $('#btn-get-po').removeAttr('disabled');
  $('#invoice').removeAttr('disabled');
  $('#remark').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-save-edit').removeClass('hide');
}


function getPO(){
  var id_po = $('#id_po').val();
  var received = $('.received-item').length;

  if(id_po == ''){
    return false;
  }

  if(received > 0){
    swal({
      title:'Warning',
      text:'ไม่สามารถดึงรายการได้เนื่องจากมีรายการที่เก่าค้างอยู่ กรุณาลบรายการที่ค้างอยู่ก่อนดึงใหม่อีกครั้ง',
      type:'warning'
    });

    return false;
  }

  load_in();

  $.ajax({
    url:'controller/receiveProductController.php?getPoDetail',
    type:'GET',
    cache:false,
    data:{
      'id_po' : id_po
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs))
      {
        $('#pre_label').remove();
        var source = $('#row-template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');

        render(source, data, output);

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
