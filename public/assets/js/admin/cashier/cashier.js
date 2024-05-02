

$(function () {
    $(document).on('input', '.form-control-validate', function() {
      var inputValue = $(this).val();
      if (!/^[1-9][0-9]*$/.test(inputValue)) {
        $(this).val(inputValue.replace(/[^1-9]/g, ''));
      }
    });

  if($('#pdf-path').val()){
    let path = $('#pdf-path').val();
    window.open(path, '_blank')
  }

  $('#standart').on('input', function () {
    let ticketCount = $('#standart').val();
    if (ticketCount > 0) {
      let priceTicket = $('#standart').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#standard-ticket-price').text(readyPrice);
    } else {
      $('#standard-ticket-price').text(0);
    }

  });

  $('#discount').on('input', function () {
    let ticketCount = $('#discount').val();
    if (ticketCount > 0) {
      let priceTicket = $('#discount').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#discount-price').text(readyPrice);
    } else {
      $('#discount-price').text(0);
    }
  });

  $('#guide_am').on('input', function () {
    let ticketCount = $('#guide_am').val();
    if (ticketCount > 0) {
      let priceTicket = $('#guide_am').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#guide_am_price').text(readyPrice);
    } else {
      $('#guide_am_price').text(0);
    }
  });

  $('#guide_other').on('input', function () {
    let ticketCount = $('#guide_other').val();
    if (ticketCount > 0) {
      let priceTicket = $('#guide_other').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#guide_other_price').text(readyPrice);
    } else {
      $('#guide_other_price').text(0);
    }
  });

  $('#standart, #discount, #free').on('input', function () {
    let parseStandard = parseInt($('#standart').val()) || 0;
    let parseSale = parseInt($('#discount').val()) || 0;
    let parseFree = parseInt($('#free').val()) || 0;

    let total = parseStandard + parseSale + parseFree;
    $('#ticket-total-count').text(total);

  })

  $('#guide_am, #guide_other').on('input', function () {
    let gitArm = parseInt($('#guide_am').val()) || 0;
    let gitOther = parseInt($('#guide_other').val()) || 0;

    let total = gitArm + gitOther;
    $('#git-total-count').text(total);
  })

  $('#standart, #discount, #free, #guide_am, #guide_other').on('input', function () {
    $('#ticket-total-price').text(parseInt($('#standard-ticket-price').text()) + parseInt($('#discount-price').text()) + parseInt($('#guide_am_price').text() || 0) + parseInt($('#guide_other_price').text() || 0));
  })

  let mistakeQuantity = [];
  $('input[name^="educational"]').on('input', function () {
    let ticketCount = parseInt($(this).val()) || 0;
    let productId = $(this).attr('name').match(/\[(\d+)\]/)[1];
    let minQuantity = parseInt($(this).attr('min_quantity'));
    let maxQuantity = parseInt($(this).attr('max_quantity'));
    if(ticketCount > 0){
      if(ticketCount < minQuantity || ticketCount > maxQuantity){
        mistakeQuantity.push(productId);
        $('#educational-button').prop('disabled', true);
      }else {
        mistakeQuantity = mistakeQuantity.filter(item => item !== productId);
        $('#educational-button').prop('disabled', false);
      }
    }else if (ticketCount == 0 || ticketCount == '' || ticketCount == null || ticketCount < 0) {
       mistakeQuantity = mistakeQuantity.filter(item => item !== productId);
       $('#educational-button').prop('disabled', false);
    }

    if (ticketCount > 0) {    
      let priceTicket = $(this).attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#educational-ticket-price_' + productId).text(readyPrice);
    } else {
      $('#educational-ticket-price_' + productId).text(0);
    }

    let totalQuantity = 0;
    let totalPrice = 0;
    $('input[name^="educational"]').each(function () {
      let educational = parseFloat($(this).val());
      let educationalPrice = educational * parseFloat($(this).attr('price'));
      if (!isNaN(educational)) {
        totalQuantity += educational;
      }
      if (!isNaN(educationalPrice)) {
        totalPrice += educationalPrice;
      }
    });
    $('#educational-total-count').text(totalQuantity);
    $('#educational-total-price').text(totalPrice);

    if(mistakeQuantity.length > 0){
      $('#educational-error').attr('style', 'display: block !important; color:red;');
      ;
    }else {
      $('#educational-error').attr('style', 'display: none !important');
    }
  });

  $('#aboniment-ticket').on('input', function () {
    let ticketCount = $('#aboniment-ticket').val();
    if (ticketCount > 0) {
      let priceTicket = $('#aboniment-ticket').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#aboniment-ticket-price').text(readyPrice);
    } else {
      $('#aboniment-ticket-price').text(0);
    }

  });

  $('#corporative-coupon').on('click', function () {
    let coupon = $('#corporative-coupon-input').val();
    $.ajax({
      type: "POST",
      url: '/cashier/check-coupon',
      data: {
        coupon: coupon,
      },
      cache: false,
      success: function (data) {
        if (data.success) {
          $('#corporative-name').text(data.data.companyName);
          $('#count-corporative-ticket').text(data.data.availableTickets);
          $('#corporative-sale').attr('style', 'display: block !important');
          $('#corporative-coupon-not-found').attr('style', 'display: none !important;');
        }
        else {
          message = data.message
          $('#corporative-coupon-not-found').attr('style', 'display: block !important; color:red;');
          $('#corporative-coupon-not-found').text(message);
          $('#corporative-sale').attr('style', 'display: none !important');
        }
      }
    });
  });

  $('#event-select').on('input', function () {
    let selectedId = $('#event-select').val();
    if(selectedId){
      $.ajax({
        type: "GET",
        url: '/cashier/get-event-details/' + selectedId,
        cache: false,
        success: function (data) {
          $('#event-total').attr('style', 'display: none !important ');
          $('#event-save').attr('style', 'display: none !important');
          $('#event-config').text('');
          $('#event-total-count').text(0);
          $('#event-total-price').text(0);
          let html = ``;
          if (data.event_configs.length) {
            $('#event-total').attr('style', 'display: block !important; display:flex !important; justify-content: end !important');
            $('#event-save').attr('style', 'display: block !important; display:flex !important; margin-top: 1rem !important; justify-content: flex-end !important;');
            
             html = `<table class="table cashier-table">
                        <thead>
                          <tr>
                              <th>Օր</th>
                              <th>Սկիզբ</th>
                              <th>Վերջ</th>
                              <th>Հասանելի տեղեր</th>
                              <th>Քանակ</th>
                              <th>Արժեք</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">`;
  
            data.event_configs.map(element => {
              html += `<tr class='table-default'>
                            <td>${element.day}</td>
                            <td>${element.start_time}</td>
                            <td>${element.end_time}</td>
                            <td>${element.visitors_quantity_limitation - element.visitors_quantity} մարդ</td>
                            <td><input type="number" min="0" class="form-control form-control-validate" onwheel="return false;" price="${element['price']}"
                              id="event_${element['id']}" name="event[${element['id']}]"></td>
                            <td id = 'event-ticket-price_${element['id']}'>0</td>
                        </tr>`;
            });
          } else {
            html = `<h3 class='m-3'>Բացակայում են միջոցառման ժամերը։</h3>`
          }
          $('#event-config').html(html);
        }
      });
    } else {
      html = `<h3 class='m-3'>Բացակայում են միջոցառման ժամերը։</h3>`
      $('#event-config').html(html);
    }
   
  });


  $(document).on('input', 'input[name^="event"]', function () { 
    let ticketCount = $(this).val();
    let eventId = $(this).attr('name').match(/\[(\d+)\]/)[1];
    if (ticketCount > 0) {
      let priceTicket = $(this).attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#event-ticket-price_' + eventId).text(readyPrice);
    } else {
      $('#event-ticket-price_' + eventId).text(0);
    }

    let totalQuantity = 0;
    let totalPrice = 0;
    $('input[name^="event"]').each(function () {
      let event = parseFloat($(this).val());
      let eventPrice = event * parseFloat($(this).attr('price'));
      if (!isNaN(event)) {
        totalQuantity += event;
      }
      if (!isNaN(eventPrice)) {
        totalPrice += eventPrice;
      }
    });
    $('#event-total-count').text(totalQuantity);
    $('#event-total-price').text(totalPrice);
  });




});