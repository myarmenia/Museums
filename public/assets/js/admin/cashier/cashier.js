$(function () {

    $('.form-control').on('input', function() {
        var inputValue = $(this).val();
        console.log(inputValue)
      
        if (!/^[1-9][0-9]*$/.test(inputValue)) {
          $(this).val(inputValue.replace(/[^1-9]/g, ''));
      }
    });

  $('#standard-ticket').on('input', function () {
    let ticketCount = $('#standard-ticket').val();
    if (ticketCount > 0) {
      let priceTicket = $('#standard-ticket').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#standard-ticket-price').text(readyPrice);
    } else {
      $('#standard-ticket-price').text(0);
    }

  });

  $('#sale-ticket').on('input', function () {
    let ticketCount = $('#sale-ticket').val();
    if (ticketCount > 0) {
      let priceTicket = $('#sale-ticket').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#sale-ticket-price').text(readyPrice);
    } else {
      $('#sale-ticket-price').text(0);
    }
  });

  $('#git-arm').on('input', function () {
    let ticketCount = $('#git-arm').val();
    if (ticketCount > 0) {
      let priceTicket = $('#git-arm').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#git-arm-price').text(readyPrice);
    } else {
      $('#git-arm-price').text(0);
    }
  });

  $('#git-other').on('input', function () {
    let ticketCount = $('#git-other').val();
    if (ticketCount > 0) {
      let priceTicket = $('#git-other').attr('price');
      let readyPrice = priceTicket * ticketCount;
      $('#git-other-price').text(readyPrice);
    } else {
      $('#git-other-price').text(0);
    }
  });

  $('#standard-ticket, #sale-ticket, #free-ticket').on('input', function () {
    let parseStandard = parseInt($('#standard-ticket').val()) || 0;
    let parseSale = parseInt($('#sale-ticket').val()) || 0;
    let parseFree = parseInt($('#free-ticket').val()) || 0;

    let total = parseStandard + parseSale + parseFree;
    $('#ticket-total-count').text(total);

  })

  $('#git-arm, #git-other').on('input', function () {
    let gitArm = parseInt($('#git-arm').val()) || 0;
    let gitOther = parseInt($('#git-other').val()) || 0;

    let total = gitArm + gitOther;
    $('#git-total-count').text(total);
  })

  $('#standard-ticket, #sale-ticket, #free-ticket, #git-arm, #git-other').on('input', function () {
    $('#ticket-total-price').text(parseInt($('#standard-ticket-price').text()) + parseInt($('#sale-ticket-price').text()) + parseInt($('#git-arm-price').text()) + parseInt($('#git-other-price').text()));
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
    console.log(mistakeQuantity)

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
        }
        else {
          message = 'Սխալ է տեղի ունեցել։'
          type = 'danger'
        }
      }
    });
  });

  $('#event-select').on('input', function () {
    let selectedId = $('#event-select').val();
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
                          <td><input type="number" min="0" class="form-control" onwheel="return false;" price="${element['price']}"
                            id="event_${element['id']}" name="event[${element['id']}]"></td>
                          <td id = 'event-ticket-price_${element['id']}'>0</td>
                      </tr>`;
          });

        } else {
          html = `<h3 class='m-3'>Բացակայում են միջոցառման ժամերը։</h3>`
        }
        $('#event-config').append(html);
      }
    });
  });


  $(document).on('input', 'input[name^="event"]', function () { 
    let ticketCount = $(this).val();
    let eventId = $(this).attr('name').match(/\[(\d+)\]/)[1];
    console.log(this,eventId, "this")
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