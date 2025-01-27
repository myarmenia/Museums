$(function () {
  let mistakeQuantity = [];
  $('input[name^="product"]').on('input', function () {
    console.log(mistakeQuantity)
    let ticketCount = parseInt($(this).val()) || 0;
    let productId = $(this).attr('name').match(/\[(\d+)\]/)[1];
    let minQuantity = parseInt($(this).attr('min_quantity'));
    let maxQuantity = parseInt($(this).attr('max_quantity'));
    if(ticketCount > 0){
      if(ticketCount < minQuantity || ticketCount > maxQuantity){
        mistakeQuantity.push(productId);
        $('#product-button').prop('disabled', true);
      }else {
        mistakeQuantity = mistakeQuantity.filter(item => item !== productId);
        $('#product-button').prop('disabled', false);
      }
    }else if (ticketCount == 0 || ticketCount == '' || ticketCount == null || ticketCount < 0) {
       mistakeQuantity = mistakeQuantity.filter(item => item !== productId);
       $('#product-button').prop('disabled', false);
    }
    console.log(mistakeQuantity)

    if (ticketCount > 0) {
      let priceTicket = $(this).attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#product-ticket-price_' + productId).text(readyPrice);
    } else {
      $('#product-ticket-price_' + productId).text(0);
    }

    let totalQuantity = 0;
    let totalPrice = 0;
    $('input[name^="product"]').each(function () {
      let product = parseFloat($(this).val());
      let productPrice = product * parseFloat($(this).attr('price'));
      if (!isNaN(product)) {
        totalQuantity += product;
      }
      if (!isNaN(productPrice)) {
        totalPrice += productPrice;
      }
    });
    $('#product-total-count').text(totalQuantity);
    $('#product-total-price').text(totalPrice);

    if(mistakeQuantity.length > 0){
      $('#product-error').attr('style', 'display: block !important; color:red;');
      ;
    }else {
      $('#product-error').attr('style', 'display: none !important');
    }
  });
});


$('.productCashierRadio').on('click',function(){

  $('.productCashierRadio').attr('checked', false);
  $(this).attr('checked', true);


  $(this).parent().parent().parent().find('button[type="button"]').removeAttr('disabled');
})
