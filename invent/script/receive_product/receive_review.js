function saveReceived(){
  var id = $('#id_receive_product').val();
  var id_approver = $('#id_approver').val();
  if(id === '' || id == 0){
    swal('ไม่พบเลขที่เอกสาร');
    return false;
  }

  if($('#overAccept').length && (id_approver == 0)){
    getApprove();
    return false;
  }


  load_in();
  $.ajax({
    url:'controller/receiveProductController.php?saveAdd',
    type:'POST',
    cache:false,
    data:{
      'id_receive_product' : id,
      'id_approver' : id_approver
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          text:'รับเข้าเรียบร้อยแล้ว',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          viewDetail(id);
        }, 1500);
      }else{
        swal({
          title:'Error!!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}



$('#approveModal').on('shown.bs.modal', function(){
  $('#pwd-box').focus();
});


$('#pwd-box').keyup(function(e){
  if(e.keyCode == 13){
    confirmReceive();
  }
});


function getApprove(){
  $('#approveModal').modal('show');
}


function confirmReceive(){
  var pwd = $('#pwd-box').val();
  if(pwd.length == 0){
    $('#message').removeClass('hide');
    return false;
  }

  $.ajax({
    url:'controller/receiveProductController.php?getApprover',
    type:'GET',
    cache:false,
    data:{
      's_key' : pwd
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(isNaN(parseInt(rs)) || rs == 0){
        $('#message').removeClass('hide');
        $('#pwd-box').val('');
        $('#pwd-box').focus();
      }else{
        $('#id_approver').val(rs);
        $('#approveModal').modal('hide');
        saveReceived();
      }
    }
  });
}
