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

  if ((museums_sel.length > 1 && time_sel.length == 1) || (museums_sel.length == 1 && time_sel.length > 1) || (museums_sel.length > 1 && time_sel.length == 0 && date_from != '')){
    $('.compare').removeAttr('disabled')

  }
  else{
    $('.compare').attr('disabled', 'disabled')

  }

  if (time_sel.length == 0 && date_from == '') {
    $('.search').attr('disabled', 'disabled')

  }
  else{
    $('.search').removeAttr('disabled')

  }

  console.log($('#multiple-select-time').val());
})
