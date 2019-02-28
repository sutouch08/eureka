function saveAdd(){
  var id = $('#id_receive_product').val();
  var id_po = $('#id_po').val();
  var id_zone = $('#id_zone').val();
  var max = $('.receive-box').length;
  var hasItems = $('.received-item').length;

  if(isNaN(parseInt(id_zone))){
    swal("โซนไม่ถูกต้อง");
    return false;
  }

  var details = [];
  if(max > 0 ){
    $('#btn-save').attr('disabled', 'disabled');
    $('#btn-change-zone').attr('disabled', 'disabled');
    load_in();
    $('.receive-box').each(function(index){

      let arr = $(this).attr('id').split('-');
      let id_pa = arr[1];
      var qty = parseInt($('#receive-'+id_pa).val());
      var id_pd = $('#productId-'+id_pa).val();
      if(qty > 0){
        var item = {
          "id_product" : id_pd,
          "id_product_attribute" : id_pa,
          "qty" : qty
        }
        details.push(item);
      }
    });

    var data = JSON.stringify(details);

    $.ajax({
      url:'controller/receiveProductController.php?receiveItems',
      type:'POST',
      cache:false,
      data:{
        "id_receive_product" : id,
        "id_po" : id_po,
        "id_zone" : id_zone,
        "detail" : data
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs ==  'success'){
          review();
        }else{
          swal({
            title:'ข้อผิดพลาด !',
            text:rs,
            type:'error'
          });
        }
      }
    });
  }else{
    swal({
      title:'ผิดพลาด!',
      text:'ไม่พบรายการรับเข้า กรุณาตรวจสอบ',
      type:'warning'
    });
    return false;
  }
}


function unSave(){
  var id = $('#id_receive_product').val();
  load_in();
  $.ajax({
    url:'controller/receiveProductController.php?unSaveRecieved',
    type:'POST',
    cache:false,
    data:{'id_receive_product' : id},
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs === 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        },1500);
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

function receiveItem(id, id_po, id_zone, id_pa){
  var qty = parseInt($('#receive-'+id_pa).val());
  var id_pd = $('#productId-'+id_pa).val();

  if(isNaN(qty)){
    swal("จำนวนไม่ถูกต้อง");
    return false;
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
        setReceived(id_pa, qty);
      }
    });
  }else{
    setReceived(id_pa, qty);
  }
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



function cancleReceiveItem(id_pa){
  var id_receive_product = $('#id_receive_product').val();

  load_in();

  $.ajax({
    url:'controller/receiveProductController.php?cancleReceiveItem',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id_receive_product,
      'id_product_attribute' : id_pa
    },
    success:function(rs){
      load_out();
      rs = parseInt(rs);
      if(rs === 1){
        $('#row-'+id_pa).remove();
        //setUnReceived(id_pa);
      }else if(rs > 1){
        swal({
          title:'Warning!',
          text:'ลบไป '+rs+' รายการ',
          type:'warning'
        });

        $('#row-'+id_pa).remove();
        //setUnReceived(id_pa);
      }else{
        swal({
          title:'Error!',
          text:'ลบรายการไม่สำเร็จ',
          type:'error'
        });
      }
    }
  });

}

$('.receive-box').keyup(function(){
  if(isNaN(parseInt($(this).val()))) {
    $(this).val(0);
  }
});



function addNew(){
  var id_po = $('#id_po').val();
  var po = $('#po').val();
  var invoice = $('#invoice').val();
  var dateAdd = $('#date_add').val();
  var remark = $('#remark').val();

  if(id_po == "" || po.length == 0){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  if(!isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:"controller/receiveProductController.php?add_new",
    type:"POST",
    cache:false,
    data:{
      "invoice" : invoice,
      "po_reference" : po,
      "id_po" : id_po,
      "date_add" : dateAdd,
      "remark" : remark
    },
    success: function(rs)
    {
      var rs = parseInt(rs);
      if(! isNaN(rs)){
        window.location.href = 'index.php?content=receive_product&add=y&id_receive_product='+rs;
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


$('#po').autocomplete({
  source:'controller/autoComplete.php?get_active_po',
  autoFocus:true,
  close:function(event, ui){
    var data = $(this).val();
    var arr = data.split(" | ");

    if(arr.length == 3){
      $('#id_po').val(arr[0]);
      $('#po').val(arr[1]);
    }else{
      $('#id_po').val('');
    }
  }
});


$('#po').keyup(function(e){
  if(e.keyCode == 13 && $(this).val().length){
    $('#invoice').focus();
  }
});



$('#invoice').keyup(function(e){
  if(e.keyCode == 13){
    $('#remark').focus();
  }
});


$('#remark').keyup(function(e){
  if(e.keyCode == 13){
    addNew();
  }
});
