$('#zone-box').autocomplete({
  source:'controller/autoComplete.php?getZoneName',
  autoFocus:true,
  close:function(event, ui){
    let data = $(this).val();
    let arr = data.split(' | ');
    if(arr.length === 3){
      $('#id_zone').val(arr[2]);
      $('#zone-code').val(arr[0]);
      $(this).val(arr[1]);
    }
  }
});



$('#zone-code').autocomplete({
  source:'controller/autoComplete.php?getZoneCode',
  autoFocus:true,
  close:function(event, ui){
    let data = $(this).val();
    let arr = data.split(' | ');
    if(arr.length === 3){
      $('#id_zone').val(arr[2]);
      $(this).val(arr[0]);
      $('#zone-box').val(arr[1]);
    }else{
      $('#id_zone').val('');
      $('#zone-box').val('');
      $('#zone-code').val('');
    }
  }
});


$('#zone-box').keyup(function(e){
  if($(this).val() == ''){
    $('#id_zone').val('');
    $('#zone-code').val('');
  }

  if(e.keyCode == 13){
    var id_zone = $('#id_zone').val();
    if(id_zone != '')
    {
      $('#remark').focus();
    }
  }
});


$('#zone-code').keyup(function(e){
  if($(this).val() == ''){
    $('#id_zone').val('');
    $('#zone-box').val('');
  }

  if(e.keyCode == 13){
    var id_zone = $('#id_zone').val();
    if(id_zone != '')
    {
      $('#remark').focus();
    }else{
      $('#zone-box').focus();
    }
  }
});
