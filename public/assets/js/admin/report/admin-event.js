$('#select-museum').on('change', function () {
  // let museum_id = $("#select-museum option:selected").val()
  let museum_id = $(this).val()

  console.log(museum_id)
})
$(document).ready(function () {
    $('#select-museum').on('change', function () {
      var selectedValue = $(this).val();

      $.ajax({
        url: '/get-event',
        method: 'POST',
        data: { museum_id: selectedValue },
        success: function (response) {
          var $eventSelect = $('#multiple-select-event');


          $eventSelect.empty().select2('destroy');

          if (response.length > 0) {

            $eventSelect.append(`<option disabled selected >Միջոցառում</option>`);
            response.forEach(function (event) {

              $eventSelect.append(`<option value="${event.id}" >${event.item_translations[0].name}</option>`);
            });
          } else {
            $eventSelect.append(new Option('Нет доступных событий', '', true, true)).prop('disabled', true);
          }


          $eventSelect.prop('disabled', false).select2({
            theme: 'bootstrap-5',
            placeholder: 'Միջոցառում'
          });

        },
        error: function () {
          console.log('Ошибка при получении событий');
        }
      });
    });


  $('#multiple-select-event').select2({
    theme: 'bootstrap-5',
    placeholder: 'Միջոցառում'
  });
});
