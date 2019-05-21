
$("#pdCode").autocomplete({
	minLength: 2,
	source: "controller/autoComplete.php?product_code",
	autoFocus: true
});



$('#pdCode').keyup(function(e){
	if(e.keyCode == 13){
		if($(this).val() != ''){
			get_product();
		}
	}
});



function get_product(){
	var product  = $("#pdCode").val();
	load_in();
	$.ajax({
		url:"controller/poController.php?get_product",
		type:"POST",
    cache:false,
    data:{
      "product_code" : product
    },
		success: function(dataset){
			var dataset = $.trim(dataset);
			if(dataset !="fail" && dataset !=""){
				var arr = dataset.split("|");
				var data = arr[0];
				var table_w = arr[1];
				var title = arr[2];
				var id_product = arr[3];
				$("#id_product").val(id_product);
				$("#modal").css("width",table_w+"px");
				$("#modal_title").html(title);
				$("#modal_body").html(data);
				load_out();
				$('#productGrid').modal('show');
			}else{
				load_out();
				swal("ไม่มีรายการสินค้าที่ค้นหา");
			}
		}
	});
}


function insert_item()
{
	$('#productGrid').modal('hide');
	var id = $("#id_receive_product").val();
	var id_pd = $('#id_product').val();
	var items = [];

  $('.input_qty').each(function(){
    let arr = $(this).attr('id').split('-');
    let id_pa = arr[1];
    var qty = parseInt($(this).val());

    if(qty > 0){
      var item = {
        'id_product' : id_pd,
        'id_product_attribute' : id_pa,
        'qty' : qty
      }

      items.push(item);
    }
  });

  var data = JSON.stringify(items);
	var count = items.length;
	load_in();

  $.ajax({
    url:'controller/receiveProductController.php?addItems',
		type:'POST',
		cache:false,
		data:{
			'id_receive_product' : id,
			'items' : data
		},
		success:function(rs){
			load_out();
			if(rs == 'success'){
				swal({
					title:'Success',
					text:'เพิ่ม '+count+' รายการ เรียบร้อยแล้ว',
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
				}, function(){
					$('#productGrid').modal('show');
				});
			}
		}
  });
}



function insertPoItems()
{
	$('#poGrid').modal('hide');

	var id = $("#id_receive_product").val();
	var items = [];

  $('.receive_qty').each(function(){
    let arr = $(this).attr('id').split('-');
    let id_pd = arr[1];
		let id_pa = arr[2];
    var qty = parseInt($(this).val());

    if(qty > 0){
      var item = {
        'id_product' : id_pd,
        'id_product_attribute' : id_pa,
        'qty' : qty
      }

      items.push(item);
    }
  });

  var data = JSON.stringify(items);
	var count = items.length;
	load_in();

  $.ajax({
    url:'controller/receiveProductController.php?addItems',
		type:'POST',
		cache:false,
		data:{
			'id_receive_product' : id,
			'items' : data
		},
		success:function(rs){
			load_out();
			if(rs == 'success'){
				swal({
					title:'Success',
					text:'เพิ่ม '+count+' รายการ เรียบร้อยแล้ว',
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
				},function(){
					$('#poGrid').modal('show');
				});
			}
		}
  });
}
