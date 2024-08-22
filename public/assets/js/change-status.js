$(function () {
  $('.change_status').on("change", function () {
    let id = $(this).parents('.action').attr('data-id')
    let tb_name = $(this).parents('.action').attr('data-tb-name')
    let status = $(this).prop('checked')
    let field_name = $(this).attr('data-field-name')
    let changeElemen = $(this)


    $.ajax({
      type: "post",
      url: "/change-status",
      data: { id, tb_name, status, field_name },
      cache: false,
      success: function (data) {
        console.log(data.result)
        let status_word = ''
        let status_class = ''
        console.log(changeElemen.closest('tr').children('.' + field_name))
        if (data.result == 1) {

          if (field_name == 'online_sales') {
            status ? (status_word = 'Առցանց վաճառք', status_class = 'success') : (status_word = 'Առցանց վաճառք', status_class = 'danger')
          }
          else {
            status ? (status_word = 'Ակտիվ', status_class = 'success') : (status_word = 'Ապաակտիվ', status_class = 'danger')
          }
          // status && field_name == 'online_sales' ? (status_word = 'Ակտիվ1', status_class = 'success') : (status_word = 'Ապաակտիվ2', status_class = 'danger')
          console.log(status_word + '--status_word--', status_class + '--status_class --')
          if (tb_name == 'events') {
            changeElemen.closest('tr').children('.statuses').children('.' + field_name).html(`
              <span class="badge bg-label-${status_class} me-1">${status_word}</span>`)
          } else {
            changeElemen.closest('tr').children('.' + field_name).html(`
              <span class="badge bg-label-${status_class} me-1">${status_word}</span>`)
          }


        }

      }

    });

  });

});
