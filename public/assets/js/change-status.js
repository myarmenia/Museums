$(function () {
  $('.change_status').on("change", function () {
    let id = $(this).parents('.action').attr('data-id')
    let tb_name = $(this).parents('.action').attr('data-tb-name')
    let status = $(this).prop('checked')
    let field_name = $(this).attr('data-field-name')
    let changeElemen = $(this)


    $.ajax({
      type: "post",
      url: "change-status",
      data: { id, tb_name, status, field_name },
      cache: false,
      success: function (data) {

        let status_word = ''
        let status_class = ''
        if (data.result == 1) {
          field_name == 'payment_status' ?
            (status ? (status_word = 'Оплачено', status_class = 'success') : (status_word = 'Не оплачено', status_class = 'danger')) :
            (status ? (status_word = 'Активный', status_class = 'success') : (status_word = 'Не активный', status_class = 'danger'));

          changeElemen.closest('tr').children('.' + field_name).html(`
            <span class="badge bg-label-${status_class} me-1">${status_word}</span>`)
        }

      }
    });

  });

});
