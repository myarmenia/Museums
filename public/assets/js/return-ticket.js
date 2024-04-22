$(function () {
  $('#send_unique_id').on("click", function () {
    let unique = $('#unique_id').val();

   if(unique != ''){
    let url = `return-ticket/check/`+unique;
     $.ajax({
       type: "get",
       url: url,
       cache: false,
       success: function (data) {
         
         if (data.success) {

          $('#returned_info').html('<div class="col-sm-10 text-success fts-14" ></div>')
          console.log(data);
  
         }else {
            $('#returned_info').html('<div class="col-sm-10 text-danger fts-14" id="error">Տվյալ թոքենով տվյալ չի գտնվել</div>')
         }
  
       }
       
     });
   } else {
    alert('Թոքեն դաշտը պարտադիր է')
   }


  });

});
