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
          $('#returned_info').html(`<div class="col-sm-10 text-success "> 
              <div class="col-sm-10 text-success my-2"><span class='fts-22'>Տոմսի տեսակը՝ ${data.type}</span></div>
              <button class="btn btn-primary col-2" data-id="${unique}" id="archive-ticket">Արխիվացնել</button>
          </div>`)
         }else {
            $('#returned_info').html('<div class="col-sm-10 text-danger fts-14" id="error">Տվյալ թոքենով տվյալ չի գտնվել</div>')
         }
       }
     });
   } else {
    alert('Թոքեն դաշտը պարտադիր է')
   }

  });

  $(document).on("click", '#archive-ticket', function () {
    let dataId = $(this).attr('data-id');

   if(dataId != ''){
    let url = `return-ticket/remove/`+dataId;
     $.ajax({
       type: "get",
       url: url,
       cache: false,
       success: function (data) {
         if (data.success) {
            //remove input by id
            $('#unique_id').val('');
            $('#returned_info').html('<div class="col-sm-10 text-success fts-14" id="success">Տոմսը հաջողությամբ չեղարկվեց</div>')
         }else {
            $('#returned_info').html('<div class="col-sm-10 text-danger fts-14" id="error">Ինչ որ բան այն չէ</div>')
         }
       }
     });
   } else {
    alert('Թոքեն դաշտը պարտադիր է')
   }
  });

});
