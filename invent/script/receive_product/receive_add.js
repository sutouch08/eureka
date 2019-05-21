// function saveAdd(){
//   var id = $('#id_receive_product').val();
//   var id_po = $('#id_po').val();
//   var id_zone = $('#id_zone').val();
//   var max = $('.receive-box').length;
//   var hasItems = $('.received-item').length;
//
//   if(isNaN(parseInt(id_zone))){
//     swal("โซนไม่ถูกต้อง");
//     return false;
//   }
//
//   var details = [];
//   if(max > 0 ){
//     $('#btn-save').attr('disabled', 'disabled');
//     $('#btn-change-zone').attr('disabled', 'disabled');
//     load_in();
//     $('.receive-box').each(function(index){
//
//       let arr = $(this).attr('id').split('-');
//       let id_pa = arr[1];
//       var qty = parseInt($('#receive-'+id_pa).val());
//       var id_pd = $('#productId-'+id_pa).val();
//       if(qty > 0){
//         var item = {
//           "id_product" : id_pd,
//           "id_product_attribute" : id_pa,
//           "qty" : qty
//         }
//         details.push(item);
//       }
//     });
//
//     var data = JSON.stringify(details);
//
//     $.ajax({
//       url:'controller/receiveProductController.php?receiveItems',
//       type:'POST',
//       cache:false,
//       data:{
//         "id_receive_product" : id,
//         "id_po" : id_po,
//         "id_zone" : id_zone,
//         "detail" : data
//       },
//       success:function(rs){
//         load_out();
//         var rs = $.trim(rs);
//         if(rs ==  'success'){
//           review();
//         }else{
//           swal({
//             title:'ข้อผิดพลาด !',
//             text:rs,
//             type:'error'
//           });
//         }
//       }
//     });
//   }else{
//     swal({
//       title:'ผิดพลาด!',
//       text:'ไม่พบรายการรับเข้า กรุณาตรวจสอบ',
//       type:'warning'
//     });
//     return false;
//   }
// }
//
//
//
//
//
//
// function setReceived(id, qty){
//   $('#label-'+id).text(qty);
//   $('#receive-'+id).addClass('hide');
//   $('#label-'+id).removeClass('hide');
//   $('#btn-remove-'+id).addClass('hide');
// }
//
//
//
//
// function setUnReceived(id){
//   $('#receive-'+id).removeClass('hide');
//   $('#label-'+id).addClass('hide');
//   $('#btn-receive-'+id).removeClass('hide');
//   $('#btn-remove-'+id).addClass('hide');
// }
//
//
//
// $('.receive-box').keyup(function(){
//   if(isNaN(parseInt($(this).val()))) {
//     $(this).val(0);
//   }
// });
//
//
//
// $('#date_add').datepicker({
//   dateFormat:'dd-mm-yy'
// });
