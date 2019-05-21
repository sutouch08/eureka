function saveEdit(){
  var id = $('#id_receive_product').val();
  var dateAdd = $('#date_add').val();

  var id_sup = $('#id_supplier').val();
  var supCode = $('#supCode').val();
  var supName = $('#supName').val();


  var id_po = $('#id_po').val();
  var poCode = $('#po').val();

  var invoice = $('#invoice').val();

  var id_zone = $('#id_zone').val();
  var zoneCode = $('#zone-code').val();
  var zoneName = $('#zone-box').val();

  var remark = $('#remark').val();

  if(!isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(id_sup == '' || supCode.length == 0 || supName.length == 0){
    swal('ผู้ขายไม่ถูกต้อง');
    return false;
  }

  if((id_po < 1 && poCode.length > 0) || (id_po > 0 && poCode.length == 0)){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }


  if(id_zone == '' || zoneCode.length == 0 || zoneName.length == 0 ){
    swal("โซนไม่ถูกต้อง");
    return false;
  }


  $('#btn-save').attr('disabled', 'disabled');

  load_in();

  $.ajax({
    url:'controller/receiveProductController.php?saveReceive',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id,
      'id_supplier' : id_sup,
      'invoice' : invoice,
      'po_reference' : poCode,
      'id_po' : id_po,
      'id_zone' : id_zone,
      'date_add' : dateAdd,
      'remark' : remark
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Saved',
          text:'บันทึกเอกสารเรียบร้อยแล้ว',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
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
}
//--- end saveEdit





function updateReceiveProduct(){
  var id = $('#id_receive_product').val();
  var id_po = $('#id_po').val();
  var po  = $('#po').val();
  var remark = $('#remark').val();
  var invoice = $('#invoice').val();
  var date_add = $('#date_add').val();
  var id_supplier = $('#id_supplier').val();
  var supCode = $('#supCode').val();
  var supName = $('#supName').val();
  var id_zone = $('#id_zone').val();
  var zoneCode = $('#zone-code').val();
  var zoneName = $('#zone-box').val();

  if(po.length > 0 && id_po < 1 ){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  if(po.length == 0 && id_po > 0){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  if(id_supplier == '' || supCode.length == 0 || supName.length == 0){
    swal('ชื่อผู้ขายไม่ถูกต้อง');
    return false;
  }

  if(id_zone == '' || zoneCode.length == 0 || zoneName.length == 0)
  {
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:'controller/receiveProductController.php?updateReceiveProduct',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id,
      'date_add' : date_add,
      'id_supplier' : id_supplier,
      'id_po' : id_po,
      'po_reference' : po,
      'invoice' : invoice,
      'id_zone' : id_zone,
      'remark' : remark
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        $('.input-box').attr('disabled');
        $('#btn-edit').removeClass('hide');
        $('#btn-save-edit').addClass('hide');
        swal({
          title:'Success',
          text:'Update complete',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          window.location.reload();
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
}





function unSave(){
  var id = $('#id_receive_product').val();
  load_in();
  $.ajax({
    url:'controller/receiveProductController.php?unSaveRecieved',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id
    },
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




function removeItem(id_pa){
  var id_receive_product = $('#id_receive_product').val();

  load_in();

  $.ajax({
    url:'controller/receiveProductController.php?removeItem',
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
        updateNo();
        reCal();
        swal({
          title:'Deleted',
          text:'ลบ 1 รายการเรียบร้อยแล้ว',
          type:'success',
          timer:1000
        });
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


function reCal(){
  var total = 0;
  $('.qty').each(function(){
    var qty = parseInt($(this).text());
    total += qty;
  });

  $('#total').text(addCommas(total));
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
  $('.input-box').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-save-edit').removeClass('hide');
}


$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});






function clearAll(){
  swal({
    title:'Are You Sure ?',
    text: 'ต้องการลบรายการทั้งหมดหรือไม่?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  }, function(){
      load_in();
      var id = $('#id_receive_product').val();
      $.ajax({
        url:'controller/receiveProductController.php?removeAllItems',
        type:'GET',
        cache:false,
        data:{
          'id_receive_product' : id
        },
        success:function(rs){
          load_out();
          var rs = $.trim(rs);
          if(rs == 'success'){
            $('#result').html('');

            setTimeout(function(){
              swal({
                title:'Deleted',
                text:'ลบทุกรายการเรียบร้อยแล้ว',
                type:'success',
                timer:1000
              }, 1000);
            });
          }else{
            swal({
              title:'Error!',
              text:rs,
              type:'error'
            });
          }
        }
      });

  });// swal
}
