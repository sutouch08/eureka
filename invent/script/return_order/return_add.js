$('#date_add').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(){
    $('#txt-bill').focus();
  }
});


$('#txt-customer').autocomplete({
  source:'controller/customerController.php?getCustomer',
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    if(rs == 'ไม่พบข้อมูล'){
      $(this).val('');
      $('#id_customer').val('');
      $('#id_emp').val('');
    }else{
      var arr = rs.split(' | ');
      $(this).val(arr[0]);
      $('#id_customer').val(arr[1]);
      updateSaleName();
    }
  }
});


function updateSaleName(){
  var id = $('#id_customer').val();
  $.ajax({
    url:'controller/customerController.php?getSaleIdByCustomer',
    type:'GET',
    cache:false,
    data:{
      'id_customer' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        var ds = $.parseJSON(rs);
        $('#txt-emp').val(ds.name);
      }
    }
  });
}

$('#txt-bill').keyup(function(e){
  if(e.keyCode == 13){
    getBillDetail();
  }
});



function getBillDetail(){
  var reference = $('#txt-bill').val();
  if(reference.length == 0){
    return false;
  }
  
  load_in();
  $.ajax({
    url:'controller/returnOrderController.php?getBillDetail',
    type:'GET',
    cache:'false',
    data:{
      'reference' : reference
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        ds = $.parseJSON(rs);
        $('#txt-customer').val(ds.customerName);
        $('#id_customer').val(ds.id_customer);
        $('#txt-emp').val(ds.empName);
        $('#txt-customer').attr('disabled', 'disabled');
        source = $('#bill-template').html();
        data = ds.data;
        output = $('#result');
        render(source, data, output);
        inputInit();
      }else{
        swal('Error!', rs, 'error');
        $('#txt-customer').val('');
        $('#id_customer').val('');
        $('#txt-emp').val('');
        $('#txt-customer').removeAttr('disabled');
        $('#result').html('');
      }

      $('#btn-save').removeAttr('disabled');
    }
  });
}


function inputInit(){
  $('.qty').keyup(function(e){
    if(e.keyCode == 13){
      $('.qty').eq($(this).index() + 1).focus();
    }else{
      ids = $(this).attr('id').split('-');
      id = ids[1];
      limit = parse_int($('#qty-'+id).text());
      qty = parse_int($(this).val());

      if(qty > limit || qty < 0){
        $(this).val('');
      }
      reCal();
    }

  });
}



function reCal(){
  console.log('recal');
  var sumQty = 0;
  var sumAmount = 0;
  $('.qty').each(function(index, el) {
    ids = $(this).attr('id').split('-');
    id  = ids[1];
    qty = parseInt($(this).val());
    qty = isNaN(qty) ? 0 : qty;
    price = parseFloat($('#price-'+id).text());
    price = isNaN(price) ? 0 : price;

    sumQty += qty;
    sumAmount += (qty * price);
    $('#cnAmount-'+id).text(addCommas(qty * price));
  });

  $('#sumQty').text(addCommas(sumQty));
  $('#sumAmount').text(addCommas(sumAmount));
}



function save(){
  swal({
    title:'ยืนยันการคืนสินค้า',
    text:'ต้องการยืนยันการคืนสินค้าหรือไม่ ?',
    showCancelButton:true,
    confirmButtonText:'ยืนยัน',
    closeOnConfirm:false
  }, function(){
    saveReturn();
  });
}



function saveReturn(){
  var billCode = $('#txt-bill').val();
  var date_add = $('#date_add').val();
  var id_customer = $('#id_customer').val();
  var remark = $('#remark').val();
  var count = 0;
  if(billCode == ''){
    swal('เลขที่บิลไม่ถูกต้อง');
    return false;
  }

  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  ds = [
    {'name' : 'billCode', 'value' : billCode},
    {'name' : 'date_add', 'value' : date_add},
    {'name' : 'id_customer', 'value' : id_customer},
    {'name' : 'remark' , 'value' : remark}
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

  if(count == 0){
    swal('ไม่พบรายการรับคืน');
    return false;
  }

  $.ajax({
    url:'controller/returnOrderController.php?addNew',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      //--- rs = id_return_order
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
