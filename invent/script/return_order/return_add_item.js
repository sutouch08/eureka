function getProduct(barcode){
  $.ajax({
    url:'controller/productController.php?getProductByBarcode',
    type:'GET',
    cache:false,
    data:{
      'barcode' : barcode
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        var ds = $.parseJSON(rs);
        $('#id_pa').val(ds.id_pa);
        $('#pdCode').val(ds.pdCode);
        $('#price').val(ds.price);
        $('#qty').focus().select();
      }else{
        $('#id_pa').val('');
        swal({
          title:'Error',
          text:rs,
          type:'error'
        });
      }
    }
  });
}


function addDetail(){
  var id_pa = $('#id_pa').val();
  var barcode = $('#barcode').val();
  var product = $('#pdCode').val();
  var price = $('#price').val();
  var qty = parseInt($('#qty').val());

  if(id_pa != '' && !isNaN(qty) && qty > 0){
    if($('#qty-'+id_pa).length){
      var preQty = parseInt($('#qty-'+id_pa).val());
      var newQty = preQty + qty;
      var amount = newQty * price;

      $('#qty-'+id_pa).val(newQty);
      $('#cnAmount-'+id_pa).text(addCommas(amount));
      reCal();
      updateNo();
    }else{
      var ds = {
        'no' : 1,
        'id' : id_pa,
        'barcode' : barcode,
        'product' : product,
        'price' : price,
        'qty' : qty,
        'amount' : (qty*price)
      }

      var source = $('#row-template').html();
      var output = $('#result');
      render_append(source, ds, output);
      reCal();
      updateNo();
    }
    clearInput(); //--- reset input item

  }else{
    swal('จำนวนไม่ถูกต้อง');
  }
}


function clearInput(){
  $('#id_pa').val('');
  $('#barcode').val('');
  $('#pdCode').val('');
  $('#price').val(0.00);
  $('#qty').val(1);
  $('#barcode').focus();
}



$('#qty').keyup(function(e){
  if(e.keyCode == 13){
    addDetail();
  }
});


$('#barcode').keyup(function(e){
  if(e.keyCode ==13){
    var barcode = $.trim($(this).val());
    if(barcode.length > 0){
      getProduct(barcode);
    }
  }
});


function hideControl(){
  $('#item-control').addClass('hide');
  $('#item-hr').addClass('hide');
}

function showControl(){
  $('#item-control').removeClass('hide');
  $('#item-hr').removeClass('hide');
}
