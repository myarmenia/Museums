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

$('.selectdate').on('change', function(){

    if ($(this).attr('id') == 'datefrom' || $(this).attr('id') == 'dateto'){
      console.log(88);
        $("#multiple-select-time").val([])
        $('#multiple-select-time').parent().find('.select2-selection__rendered').html('')

    }

    if ($(this).attr('id') == 'multiple-select-time') {
      console.log(11);

        $('#datefrom').val('')
        $('#dateto').val('')

    }


    if ($(this).attr('id') == 'multiple-select-museum') {
      var selectedOption = $(this).find(":selected").val();
      console.log(99);

      if (selectedOption == 'all'){
        $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", false)
        $("#multiple-select-museum").find('option:not(:first-child)').val([])
      }

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
