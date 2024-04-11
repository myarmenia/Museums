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
      $("#multiple-select-time").val(null).trigger("change")
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
// $("#multiple-select-museum").find('option:first-child').prop("selected", true).trigger("change");

// $("#multiple-select-museum").each(function () {
//   if($(this).val('all')){
//     console.log(544444);
//     $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", true).trigger("change")
//   }
//   // $(this).prop("selected", true);
//   // $("#multiple-select-museum").trigger({
//   //   type: 'select2:select',
//   //   params: {
//   //     data: $(this).val()
//   //   }
//   // });
// });
// $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", true).trigger("change");

$('#multiple-select-museum').on('change', function(){
  console.log(7777);
    if ($(this).val('all')) {
      console.log(544444);
      $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", false).trigger("change")
      $("#multiple-select-museum").find('option:first-child').prop("selected", true).trigger("change");

    }
    else{
      $("#multiple-select-museum").find('option:first-child').prop("selected", false).trigger("change");
      $("#multiple-select-museum").find('option:not(:first-child)').prop("selected", true).trigger("change")


    }
})

$('.compare').on('click', function(){
  $('#form').attr('action', '/reports/compare')
})
