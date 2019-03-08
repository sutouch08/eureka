function goBack(){
  window.location.href = 'index.php?content=order_return';
}



function leave(){
  swal({
    title:'ยกเลิกข้อมูลนี้ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'No',
    confirmButtonText:'Yes',
    closeOnConfirm:true
  },function(){
    goBack();
  });
}



function goAdd(id){
  if(id === undefined){
    window.location.href = 'index.php?content=order_return&add=Y';
  }else{
    window.location.href = 'index.php?content=order_return&add=Y&id_return_order='+id;
  }
}




function goEdit(id){
  window.location.href = 'index.php?content=order_return&edit=Y&id_return_order='+id;
}




function viewDetail(id){
  window.location.href = 'index.php?content=order_return&viewDetail&id_return_order='+id;
}


function goDelete(id, code){
  swal({
      title: 'คุณแน่ใจ ?',
      text: 'ต้องการลบเอกสาร ' + code + ' หรือไม่?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6855',
      confirmButtonText: 'ใช่ ฉันต้องการลบ',
      cancelButtonText: 'ยกเลิก',
      closeOnConfirm: false
  }, function() {
      $.ajax({
          url: "controller/returnOrderController.php?deleteReturn",
          type: "GET",
          cache: "false",
          data: {
            "id_return_order": id
          },
          success: function(rs) {
              var rs = $.trim(rs);
              if (rs == 'success') {
                  swal({
                    title: "สำเร็จ",
                    text: "ลบรายการเรียบร้อยแล้ว",
                    timer: 1000,
                    type: "success"
                  });

                  $('#row-'+id).remove();
                  updateNo();
              } else {
                  swal("ข้อผิดพลาด!!", "ลบรายการไม่สำเร็จ กรุณาลองใหม่อีกครั้ง", "error");
              }
          }
      });
  });
}




$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});


$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


function getSearch(){
  $('#searchForm').submit();
}


$('#sReference').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});

$('#sOrderCode').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});

$('#sCustomer').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});



function clearFilter(){
  $.get('controller/returnOrderController.php?clearFilter', function(){
    goBack();
  });
}


//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;


function printDetail(id){
  var target  = "controller/printController.php?printReturnOrder&id="+id;
  window.open(target, '_blank', prop);
}
