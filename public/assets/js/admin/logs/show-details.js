$(function () {

  $('.show_details').on('click', function(e){
    e.preventDefault()
    console.log(777)

    let id = $(this).attr('data-id')

    $.ajax({
      url: '/cashier-logs-show-more',
      method: 'POST',
      data: { id: id },
      success: function (response) {

        $('.show-details-component').html(response);
        $('.show-more-details').click()

      },
      error: function () {
        console.log('Ошибка при получении result');
      }
    });
  })

})
