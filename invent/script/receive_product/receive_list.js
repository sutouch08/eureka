function getDelete(id, reference){
  swal({
    title:'Are You Sure ?',
    text: 'ต้องการลบเอกสาร '+reference+'หรือไม่?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  }, function(){
    load_in();
    $.ajax({
      url:'controller/receiveProductController.php?delete_doc&id_receive_product='+id,
      type:'GET',
      cache:false,
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
				if( rs == 'success' ){
          $("#row-"+id).remove();
          updateNo();
          setTimeout(function(){
            swal({
              title:'Deleted',
              text: 'ลบเอกสารเรียบร้อยแล้ว',
              type: 'success',
              timer: 1500
            });
          }, 500);

				}else{
					swal("ข้อผิดพลาด", rs, "error");
				}
      }
    })
  });// swal
}
