$(function () {

  let time_sel = $('#multiple-select-time').val()
  let date_from = $('#datefrom').val()

  if (time_sel.length > 1) {
    $('.compare').removeAttr('disabled')
    $('.search').attr('disabled', 'disabled')

  }
  else {
    $('.compare').attr('disabled', 'disabled')
    $('.search').removeAttr('disabled')
  }

  if ((time_sel.length == 1 && date_from == '') || (time_sel.length == 0 && date_from != '')) {
    $('.search').removeAttr('disabled')
  }
  else {
    $('.search').attr('disabled', 'disabled')

  }
})

$('.multiselect, #datefrom').on('change', function () {

  if ($(this).attr('id') == 'datefrom') {
   
    $("#multiple-select-time").val(null).trigger("change")
  }

  if ($(this).attr('id') == 'multiple-select-time') {
    $('#datefrom').val('')
    $('#dateto').val('')

  }

  let time_sel = $('#multiple-select-time').val()
  let date_from = $('#datefrom').val()

  // ======= active compare button ===============================
  if (time_sel.length > 1) {
    $('.compare').removeAttr('disabled')
    $('.search').attr('disabled', 'disabled')

  }
  else {
    $('.compare').attr('disabled', 'disabled')
    $('.search').removeAttr('disabled')
  }
  if ((time_sel.length == 1 && date_from == '') || (time_sel.length == 0 && date_from != '')) {
    $('.search').removeAttr('disabled')
  }
  else {
    $('.search').attr('disabled', 'disabled')

  }

})


$('.compare').on('click', function () {
  $('#form').attr('action', '/museum/reports/compare')
})
