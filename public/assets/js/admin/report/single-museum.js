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

$('.selectdate').on('change', function () {

    if ($(this).attr('id') == 'datefrom' || $(this).attr('id') == 'dateto') {

      $("#multiple-select-time").val([])
      $('#multiple-select-time').parent().find('.select2-selection__rendered').html('ժամանակահատված')
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



$('#multiple-select-type').on('change', function () {

  if ($(this).val() == 'offline'){
    $('#multiple-select-hdm_transaction_type').attr('disabled', false)
  }
  else{
    $('#multiple-select-hdm_transaction_type').attr('disabled', true)

  }

  console.log($(this).val())
})
