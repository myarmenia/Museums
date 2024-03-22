$(function () {
  $('#standard-ticket').on('input', function() {
    let ticketCount = $('#standard-ticket').val();
    if(ticketCount > 0){
      let priceTicket = $('#standard-ticket').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#standard-ticket-price').text(readyPrice);
    }else {
      $('#standard-ticket-price').text(0);
    }

  });

  $('#sale-ticket').on('input', function() {
    let ticketCount = $('#sale-ticket').val();
    if(ticketCount > 0){
      let priceTicket = $('#sale-ticket').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#sale-ticket-price').text(readyPrice);
    }else {
      $('#sale-ticket-price').text(0);
    }
  });

  $('#git-arm').on('input', function() {
    let ticketCount = $('#git-arm').val();
    if(ticketCount > 0){
      let priceTicket = $('#git-arm').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#git-arm-price').text(readyPrice);
    }else {
      $('#git-arm-price').text(0);
    }
  });

  $('#git-other').on('input', function() {
    let ticketCount = $('#git-other').val();
    if(ticketCount > 0){
      let priceTicket = $('#git-other').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#git-other-price').text(readyPrice);
    }else {
      $('#git-other-price').text(0);
    }
  });

  $('#standard-ticket, #sale-ticket, #free-ticket').on('input', function() { 
    let parseStandard = parseInt($('#standard-ticket').val()) || 0;
    let parseSale = parseInt($('#sale-ticket').val()) || 0;
    let parseFree = parseInt($('#free-ticket').val()) || 0;

    let total = parseStandard + parseSale + parseFree;
    $('#ticket-total-count').text(total);

  })

  $('#git-arm, #git-other').on('input', function() { 
    let gitArm = parseInt($('#git-arm').val()) || 0;
    let gitOther = parseInt($('#git-other').val()) || 0;

    let total = gitArm + gitOther;
    $('#git-total-count').text(total);
  })

  $('#standard-ticket, #sale-ticket, #free-ticket, #git-arm, #git-other').on('input', function() { 
    $('#ticket-total-price').text(parseInt($('#standard-ticket-price').text()) +  parseInt($('#sale-ticket-price').text()) + parseInt($('#git-arm-price').text()) + parseInt($('#git-other-price').text()));
  })

});