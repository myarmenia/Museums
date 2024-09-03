function handleMuseumChange() {
    var selectedValue = $("#select-museum").val();
    var selectedEventId =$("#selected-event-input").val()

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

            $eventSelect.append(`<option value="${event.id}" ${selectedEventId == event.id ? 'selected' : ''}>${event.item_translations[0].name}</option>`);
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
}

$(function () {
    $('#select-museum').on('change', handleMuseumChange);
  console.log($("#selected-event-input").val());

    if ($("#select-museum option:selected").val() != 'Թանգարան') {
      handleMuseumChange();
    }

    $('#multiple-select-event').select2({
      theme: 'bootstrap-5',
      placeholder: 'Միջոցառում'
    });
});
