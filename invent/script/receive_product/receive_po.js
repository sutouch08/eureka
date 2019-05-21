$('#po').autocomplete({
  source:'controller/autoComplete.php?get_active_po',
  autoFocus:true,
  close:function(event, ui){
    var data = $(this).val();
    var arr = data.split(" | ");

    if(arr.length == 4){
      $('#id_po').val(arr[2]);
      $('#po').val(arr[0]);
      $('#id_supplier').val(arr[3]);
      updateSupplier(arr[3]);
    }else{
      $('#id_po').val('');
    }
  }
});



function updateSupplier(id){
  $.ajax({
    url:'controller/supplierController.php?get_supplier',
    type:'GET',
    cache:false,
    data:{
      'id_supplier' : id
    },
    success:function(rs){
      if(isJson(rs)){
        let data = $.parseJSON(rs);
        $('#supCode').val(data.code);
        $('#supName').val(data.name);
      }
    }
  });
}



$('#po').keyup(function(e){
  if($(this).val() == ''){
    $('#id_po').val('');
  }


  if(e.keyCode == 13 && $(this).val().length){
    $('#invoice').focus();
  }
});



$('#invoice').keyup(function(e){
  if(e.keyCode == 13){
    $('#zone-code').focus();
  }
});


function getPO(){
  var id_po = $('#id_po').val();
  var title = $('#po').val();
  if(id_po == ''){
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
        $('#po-title').text(title);
        var source = $('#po-template').html();
        var data = $.parseJSON(rs);
        var output = $('#po-body');

        render(source, data, output);

        $('#poGrid').modal('show');

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
