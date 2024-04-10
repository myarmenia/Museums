$(function () {

  let museums_sel = $('#multiple-select-museum').val()
  let time_sel = $('#multiple-select-time').val()
  let date_from = $('#datefrom').val()

  if ((museums_sel.length > 1 && time_sel.length == 1) || (museums_sel.length == 1 && time_sel.length > 1) || (museums_sel.length > 1 && time_sel.length == 0 && date_from != '')) {
    $('.compare').removeAttr('disabled')

  }
  else {
    $('.compare').attr('disabled', 'disabled')

  }

  if ((museums_sel.length == 1 && time_sel.length == 1) || (museums_sel.length == 1 && date_from != '')) {
    $('.search').removeAttr('disabled')
  }
  else {
    $('.search').attr('disabled', 'disabled')
  }
})

$('.multiselect, #datefrom').on('change', function(){

    if ($(this).attr('id') == 'datefrom'){
      $("#multiple-select-time option:selected").remove();
      $('#multiple-select-time').val('')
    }

    if ($(this).attr('id') == 'multiple-select-time') {
        $('#datefrom').val('')
        $('#dateto').val('')

    }

    let museums_sel = $('#multiple-select-museum').val()
    let time_sel = $('#multiple-select-time').val()
    let date_from = $('#datefrom').val()

    // ======= active compare button ===============================
    if ((museums_sel.length > 1 && time_sel.length == 1) || (museums_sel.length == 1 && time_sel.length > 1) || (museums_sel.length > 1 && time_sel.length == 0 && date_from != '')){
      $('.compare').removeAttr('disabled')

    }
    else{
      $('.compare').attr('disabled', 'disabled')

    }

    if ((museums_sel.length == 1 && time_sel.length == 1) || (museums_sel.length == 1 &&  date_from != '')) {
      $('.search').removeAttr('disabled')
    }
    else{
      $('.search').attr('disabled', 'disabled')
    }

})


$('.compare').on('click', function(){
  $('#form').attr('action', '/reports/compare')
})
