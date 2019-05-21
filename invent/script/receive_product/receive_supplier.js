$('#supName').autocomplete({
  source:'controller/autoComplete.php?get_supplier',
  autoFocus:true,
  close:function(event, ui){
    var data = $(this).val();
    var arr = data.split(' | ');
    if(arr.length == 3){
      $('#id_supplier').val(arr[2]);
      $('#supCode').val(arr[0]);
      $('#supName').val(arr[1]);
      $('#po').focus();
    }else{
      $('#id_supplier').val('');
      $('#supCode').val('');
      $('#supName').val('');
      $('#po').focus();
    }
  }
});


$('#supCode').autocomplete({
  source:'controller/autoComplete.php?get_supplier',
  autoFocus:true,
  close:function(event, ui){
    var data = $(this).val();
    var arr = data.split(' | ');
    if(arr.length == 3){
      $('#id_supplier').val(arr[2]);
      $('#supCode').val(arr[0]);
      $('#supName').val(arr[1]);
      $('#po').focus();
    }else{
      $('#id_supplier').val('');
      $('#supCode').val('');
      $('#supName').val('');
      $('#po').focus();
    }
  }
});


$('#supCode').keyup(function(e){
  if($(this).val() == ''){
    $('#id_supplier').val('');
    $('#supName').val('');
  }
});



$('#supName').keyup(function(e){
  if($(this).val() == ''){
    $('#id_supplier').val('');
    $('#supCode').val('');
  }
});
