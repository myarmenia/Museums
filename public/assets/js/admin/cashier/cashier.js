$(function () {
  let mistakeQuantity = [];

  $('.nav-tabs').on('click', function (event) {
    let activeButton = $("ul.nav-tabs .nav-link.active");
    let activeTab = activeButton.closest("li");
    let activeTabId = activeTab.attr('data-name');

    let forms = $(".tab-content form");

    mistakeQuantity = [];
    $('#educational-button').prop('disabled', false);
    $('#educational-error').attr('style', 'display: none !important');
    $('.session-message').remove();
    forms.each(function (form, index) {
      if ($(this).attr('data-name') !== activeTabId) {
        let classesRemove = $(this).find(".remove-value");
        this.reset();
        classesRemove.each(function () {
          $(this).text(0);
        })
      }
    });
  });


  $('.form-cashier').on('submit', function() {
      $('.form-cashier-button').prop('disabled', true).text('ՈՒղարկվում է․․․');
  });


  $(document).on('input', '.form-control-validate', function () {
    var inputValue = $(this).val();
    if (!/^[1-9][0-9]*$/.test(inputValue)) {
      $(this).val(inputValue.replace(/[^1-9]/g, ''));
    }
  });

  if ($('#pdf-path').val()) {
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

  console.log(mistakeQuantity, 777777777)
  $('input[name^="educational"]').on('input', function () {
    console.log(mistakeQuantity, 999)
    let ticketCount = parseInt($(this).val()) || 0;
    let productId = $(this).attr('name').match(/\[(\d+)\]/)[1];
    let minQuantity = parseInt($(this).attr('min_quantity'));
    let maxQuantity = parseInt($(this).attr('max_quantity'));
    if (ticketCount > 0) {
      if (ticketCount < minQuantity || ticketCount > maxQuantity) {
        mistakeQuantity.push(productId);
        $('#educational-button').prop('disabled', true);
      } else {
        mistakeQuantity = mistakeQuantity.filter(item => item !== productId);
        $('#educational-button').prop('disabled', false);
      }
    } else if (ticketCount == 0 || ticketCount == '' || ticketCount == null || ticketCount < 0) {
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
    console.log(mistakeQuantity, 4444444)

    if (mistakeQuantity.length > 0) {
      $('#educational-error').attr('style', 'display: block !important; color:red;');
    } else {
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
    console.log('/cashier/get-event-details/' + selectedId)
    if (selectedId) {
      $.ajax({
        type: "GET",
        url: '/cashier/get-event-details/' + selectedId,
        cache: false,
        success: function (data) {
          console.log(data.style + ' ///////')
          $('#event-total').attr('style', 'display: none !important ');
          $('#event-save').attr('style', 'display: none !important');
          $('#event-config').text('');
          $('#event-total-count').text(0);
          $('#event-total-price').text(0);
          let html = ``;

          if (data.event_configs.length == 0 && data.style == 'basic'){
            html = `<h3 class='m-3'>Բացակայում են միջոցառման ժամերը։</h3>`
          }else{
            $('#event-total').attr('style', 'display: block !important; display:flex !important; justify-content: end !important');
            $('#event-save').attr('style', 'display: block !important; display:flex !important; margin-top: 1rem !important; justify-content: flex-end !important;');
            let guidTotalCont = ''
            let guidCont = ''
            if (data.guide_price_am || data.guide_price_other){
                  guidTotalCont = `<div class="me-2">
                                        <span class="remove-value total_event_guid_quantity" id="git-total-count">0</span>
                                        <span>Էքսկուրսավար</span>
                                    </div>`

              guidCont = `<table class="table cashier-table">
                            <tbody class="table-border-bottom-0" style="border-top: 30px solid transparent">
                                ${data.guide_price_am ?
                                    `<tr class='table-default'>
                                        <td>Էքսկուրսավար(հայերեն)</td>
                                        <td>
                                            <input type="number" onwheel="return false;"
                                                price="${data.guide_price_am}" min="0"
                                                class="form-control form-control-validate event_guid" id="guide_price_am" name="guide_price_am">
                                        </td>
                                        <td class="remove-value event_guide_row_price ticket_price" id='event_guide_price_am'>0</td>
                                      </tr>` : ``}
                                       ${data.guide_price_other ?
                                        `<tr class='table-default'>
                                            <td>Էքսկուրսավար(այլ)</td>
                                            <td><input type="number" onwheel="return false;"
                                                    price="${data.guide_price_other}" min="0"
                                                    class="form-control form-control-validate event_guid" id="guide_price_other" name="guide_price_other">
                                            </td>
                                            <td class="remove-value event_guide_row_price ticket_price" id='event_guide_price_other'>0</td>
                                        </tr>` : ``}
                                    </tbody></dable>`
            }

            document.querySelector('.event-total-cont').innerHTML = guidTotalCont;

            html = `<table class="table cashier-table">
                        <thead>
                          <tr>`+
                              (data.style == 'basic' ? `<th>Օր</th>` : ``) +
                              `<th>Սկիզբ</th>
                              <th>Վերջ</th>` +
                              (data.style == 'basic' ? `<th>Հասանելի տեղեր</th>` : ``) +
                              `<th>Քանակ</th>
                              <th>Արժեք</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">`;
            data.style == 'basic' ?
                data.event_configs.map(element => {
                  html += `<tr class='table-default'>
                                <td>${element.day}</td>
                                <td>${element.start_time}</td>
                                <td>${element.end_time}</td>
                                <td>${element.visitors_quantity_limitation - element.visitors_quantity} մարդ</td>
                                <td class="d-flex">
                                    <div>
                                      <label for="event_${element['id']}" class="col col-form-label">Ստանդարտ </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="${data.price}"
                                          id="event_${element['id']}_standart" name="event[${element['id']}][standart]" data-id="${element['id']}">
                                    </div>
                                    ${data.discount_price ?
                                    `<div>
                                      <label for="event_${element['id']}" class="col col-form-label">Զեղչված </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="${data.discount_price}"
                                          id="event_${element['id']}_discount" name="event[${element['id']}][discount]" data-id="${element['id']}">
                                    </div>
                                    <div>
                                      <label for="event_${element['id']}" class="col col-form-label">Անվճար </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="0"
                                          id="event_${element['id']}_free" name="event[${element['id']}][free]" data-id="${element['id']}">
                                    </div>` : ``}
                                </td>
                                <td id = 'event-ticket-price_${element['id']}' class='remove-value ticket_price'>0</td>
                            </tr>`;

                }) :
                html += `<tr class='table-default'>
                                <td>${data.start_date}</td>
                                <td>${data.end_date}</td>
                                <td class="d-flex">
                                    <div>
                                      <label for="event_${data.id}" class="col col-form-label">Ստանդարտ </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="${data.price}"
                                          id="event_${data.id}_standart" name="event[${data.id}][standart]" data-id="${data.id}">
                                    </div>
                                    ${data.discount_price ?
                                    `<div>
                                      <label for="event_${data.id}" class="col col-form-label">Զեղչված </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="${data.discount_price}"
                                          id="event_${data.id}_discount" name="event[${data.id}][discount]" data-id="${data.id}">
                                    </div>
                                    <div>
                                      <label for="event_${data.id}" class="col col-form-label">Անվճար </label>
                                      <input type="number" min="0" class="form-control form-control-validate event_ticket" onwheel="return false;" price="0}"
                                          id="event_${data.id}_free" name="event[${data.id}][free]" data-id="${data.id}">
                                    </div>`: ``}
                                </td>
                                <td id = 'event-ticket-price_${data.id}' class='remove-value ticket_price'>0</td>
                        </tr>`;

            html += `</tbody></table>${guidCont}`

            data.style == 'basic' ? html += `<input type="hidden" name="style" value="basic">` : html += `<input type="hidden"  name="style" value="temporary">`
            }

          $('#event-config').html(html);
        }
      });
    } else {
      html = `<h3 class='m-3'>Բացակայում են միջոցառման ժամերը։</h3>`
      $('#event-config').html(html);
    }

  });


  $(document).on('input', '.event_ticket', function () {
      let eventId = $(this).attr('data-id');
      let rowTotalQuantity = 0
      let rowTotalPrice = 0
      let totalQuantity = 0

        $('input[data-id="' + eventId +'"]').each(function () {

            let eachTicketCount = parseFloat($(this).val());

            let eachTicketPrice = eachTicketCount * $(this).attr('price');
            if (!isNaN(eachTicketCount)) {
              rowTotalQuantity += eachTicketCount;
            }
            if (!isNaN(eachTicketPrice)) {
              rowTotalPrice += eachTicketPrice;
            }
        });

      $('#event-ticket-price_' + eventId).text(rowTotalPrice);

      $('input[name^="event"]').each(function () {
        let event = parseFloat($(this).val());
        let eventPrice = event * parseFloat($(this).attr('price'));
        if (!isNaN(event)) {
          totalQuantity += event;
        }

      });

      $('#event-total-count').text(totalQuantity);

      totalPrice()
  });


  $(document).on('input', '.event_guid', function () {

    let eventGuidPrice = $(this).attr('price')
    let eventGuidQuantity = parseFloat($(this).val())
    let eventGuidId = $(this).attr('id')

    let totalEventGuidQuantity = 0
    let totalEventGuidPrice = parseFloat($('#event-total-price').text())
    let thisGuidPrice = eventGuidPrice * eventGuidQuantity


    $('#event_' + eventGuidId).html(thisGuidPrice)
    totalEventGuidPrice += thisGuidPrice
    $('.event_guid').each(function () {
        let quantity = parseFloat($(this).val())

        if (!isNaN(quantity)) {
          totalEventGuidQuantity += quantity
        }

        $('.total_event_guid_quantity').text(totalEventGuidQuantity)

    });

    totalPrice()

  })


  function totalPrice(){
      let totalPrice = 0
      $('.ticket_price').each(function () {

        totalPrice += $(this).text()*1
      })

    $('#event-total-price').text(totalPrice);
  }

 $("#otherServices").on('input',function(){


      $.ajax({
        type: "GET",
        url: '/cashier/get-other-service/' + $(this).val(),
        cache: false,
        success: function (data) {
        $('#other-service-save').removeClass('d-none')

          console.log(data)
          let content = `<table class="table cashier-table">
                          <thead>
                            <tr>
                            <th>Անուն</th>
                            <th>Քանակ</th>
                            <th>Արժեք</th>
                            </tr>
                          </thead>
                            <tbody class="table-border-bottom-0" style="border-top: 30px solid transparent">
                                <tr class="table-default">
                                        <td>${data.item_translations[0].name}</td>
                                         <td>
                                             <input type="text" disabled onwheel="return false;" price="200012" value=1 class="form-control form-control-validate event_guid" id="other-service-price" name="other-service-price">
                                         </td>
                                        <td class="remove-value event_guide_row_price ticket_price" id="event_guide_price_am">${data.price }</td>
                                      </tr>


                                    </tbody></table>`

                            $('#other-service-config').html(content)

          }
        })

  })


});

 $("#partners").on('input',function(){


      $.ajax({
        type: "GET",
        url: '/cashier/get-partner/' + $(this).val(),
        cache: false,
        success: function (data) {
        $('#partnerPrint').removeClass('d-none')
        let partnerTotalGuideCount = 0
        let partnerTotalAmount = 0

          console.log(data)
          let content = `<table class="table cashier-table">
                          <thead>
                            <tr>
                            <th>Անուն</th>
                            <th>Քանակ</th>
                            <th>Արժեք</th>
                            </tr>
                          </thead>
                            <tbody class="table-border-bottom-0" style="border-top: 30px solid transparent">
                                <tr class="table-default">
                                        <td>${data.name}</td>
                                         <td>
                                             <input type="number" onwheel="return false;" price="200012"  class="form-control form-control-validate event_guid" id="ticketPrice" name="partner" data-museum-standart-ticket-price=${data.museum.standart_tickets.price }>
                                         </td>
                                        <td class="remove-value event_guide_row_price ticket_price" id="partner-ticket-price">0</td>
                                </tr>
                                <tr class='table-default'>
                                        <td>Էքսկուրսավար(հայերեն)</td>
                                        <td>
                                            <input type="number" onwheel="return false;"
                                                price="${data.museum.guide.price_am}" min="0"
                                                class="form-control form-control-validate event_guid" id="partner_guide_price_am" name="guide_am" >
                                        </td>
                                        <td class="remove-value event_guide_row_price ticket_price" id='partner_guide_am'>0</td>
                                      </tr>
                                      <tr class='table-default'>
                                        <td>Էքսկուրսավար(այլ)</td>
                                        <td>
                                            <input type="number" onwheel="return false;"
                                                price="${data.museum.guide.price_other}" min="0"
                                                class="form-control form-control-validate event_guid" id="partner_guide_price_other" name="guide_other" >
                                        </td>
                                        <td class="remove-value event_guide_row_price ticket_price" id='partner_guide_other'>0</td>
                                      </tr>
                                       <tr class='table-default'>
                                        <td>Մեկնաբանություն</td>
                                        <td>
                                            <textarea name="comment"></textarea>
                                        </td>

                                      </tr>



                                    </tbody></table>`

                            $('#partner-config').html(content)
                            $('#ticketPrice').on('input',function(){
                              $price = $(this).val()*$('#ticketPrice').data('museum-standart-ticket-price')

                              $('#partner-ticket-price').html($price)
                              $('#partner-total-count').html($(this).val())


                              partnerTotalAmount = $('#partner_guide_am').text()*1+$('#partner_guide_other').text()*1+$('#partner-ticket-price').text()*1
                              $('#partner-total-price').html(partnerTotalAmount)

                            })

                            $('#partner_guide_price_am').on('input',function(){

                              $partner_guide_price_am = $(this).val()*$('#partner_guide_price_am').attr('price')
                              partnerTotalGuideCount = $('#partner_guide_price_other').val()*1+$(this).val()*1


                              $('#partner_guide_am').html($partner_guide_price_am)
                              $('#partner-total-guide-count').html(partnerTotalGuideCount)
                              partnerTotalAmount = $('#partner_guide_am').text()*1+$('#partner_guide_other').text()*1+$('#partner-ticket-price').text()*1
                              $('#partner-total-price').html(partnerTotalAmount)

                            })

                            $('#partner_guide_price_other').on('input',function(){
                              $partner_guide_price_other = $(this).val()*$('#partner_guide_price_other').attr('price')
                              partnerTotalGuideCount = $('#partner_guide_price_am').val()*1+$(this).val()*1


                              $('#partner_guide_other').html($partner_guide_price_other)
                              $('#partner-total-guide-count').html(partnerTotalGuideCount)

                              partnerTotalAmount = $('#partner_guide_am').text()*1+$('#partner_guide_other').text()*1+$('#partner-ticket-price').text()*1
                              $('#partner-total-price').html(partnerTotalAmount)


                            })

              }
        })




  })
  // ==========================
  document.getElementById('myForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Отключаем стандартное поведение отправки формы

        let form = event.target;  // Ссылка на саму форму
        let formData = new FormData(form);  // Собираем данные формы

        // Отправка данных через fetch
        fetch(form.action, {
            method: 'POST',  // Метод, указанный в форме
            body: formData,  // Данные формы
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Для Laravel
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    });








