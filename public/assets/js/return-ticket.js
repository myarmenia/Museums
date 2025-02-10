$(function () {
  $('#send_unique_id').on("click", function () {
    let unique = $('#unique_id').val();

    if (unique != '') {
      let url = `return-ticket/check/` + unique;
      $.ajax({
        type: "get",
        url: url,
        cache: false,
        success: function (data) {
          if (data.success) {
            $('#returned_info').html(`
                <div class="col-sm-10 text-success">
                  <div >
                      <div class="col-sm-10 text-success my-2">
                              <span class='fts-22'>Տոմսի տեսակը՝ ${data.type}</span>
                      </div>
                      <div id='text-components'>
                      </div>
                  </div>

                    <div id="button-container"></div> <!-- Контейнер для кнопки -->
                </div>
            `);


            $('#button-container').html(`
                <button class="btn btn-primary col-2" data-id="${unique}" id="archive-ticket">Արխիվացնել</button>
            `);
          } else {
            $('#returned_info').html(`<div class="col-sm-10 text-danger fts-14" id="error">${data.message}</div>`)
          }
        }
      });
    } else {
      alert('Թոքեն դաշտը պարտադիր է')
    }

  });

  $(document).on("click", '#archive-ticket', function () {
    let dataId = $(this).attr('data-id');
    $(this).attr('disabled', true);

    let ticketApprove = true;
    let guideApprove = false;


    if (dataId != '') {
      let url = 'return-ticket/remove';
      $.ajax({
        type: "post",
        data: {
          json:JSON.stringify({
            "dataId": dataId,

          })
        },
        url: url,
        cache: false,
        success: function (data) {
          if (data.success) {
            //remove input by id
            $('#unique_id').val('');
            $('#returned_info').html('<div class="col-sm-10 text-success fts-14" id="success">Տոմսը հաջողությամբ չեղարկվեց</div>')
          } else {
            $('#returned_info').html(`<div class="col-sm-10 text-danger fts-14" id="error">${data.message}</div>`)
          }
        }
      });
    } else {
      alert('Թոքեն դաշտը պարտադիր է')
    }
  });

});
