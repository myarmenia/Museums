$(function () {

    let museums_sel = $('#multiple-select-museum').val()
    let time_sel = $('#multiple-select-time').val()
    let date_from = $('#datefrom').val()


    if ($("#multiple-select-museum option:selected").val() == 'all' || $("#multiple-select-museum").val().length == 0) {
        $("#multiple-select-museum").find('option:first-child').prop("selected", true)
    }

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

        $("#multiple-select-time").val([])
        $('#multiple-select-time').parent().find('.select2-selection__rendered').html('ժամանակահատված')

    }

    if ($(this).attr('id') == 'multiple-select-time') {
        if ($("#multiple-select-museum option:selected").val() == 'all' && $(this).val().length > 1){
          $("#multiple-select-museum").val([])
          $('#multiple-select-museum').parent().find('.select2-selection__rendered').html('Թանգարան')
        }

        $('#datefrom').val('')
        $('#dateto').val('')

    }


    if ($(this).attr('id') == 'multiple-select-museum') {
        var selectedOption = $(this).find(":selected").val();


        if (selectedOption == 'all'){
          $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", false)
          // $("#multiple-select-museum").find('option:not(:first-child)').val([])

          if($("#multiple-select-time").val().length > 1){
            $("#multiple-select-time").val([])
            $('#multiple-select-time').parent().find('.select2-selection__rendered').html('ժամանակահատված')
          }

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
      $ ('.search').removeAttr('disabled')
    }
    else{
        $('.search').attr('disabled', 'disabled')
    }

})


$('.compare').on('click', function(){
  $('#form').attr('action', '/reports/compare')
})

// $('.download_csv').on('click', function () {
//   $('#form').attr('action', '/export-report-excel')
// })
