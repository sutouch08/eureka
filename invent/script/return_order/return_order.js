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



function viewDetail(id){
  window.location.href = 'index.php?content=order_return&viewDetail&id_return_order='+id;
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
