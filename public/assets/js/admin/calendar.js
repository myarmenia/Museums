document.addEventListener('DOMContentLoaded', function () {
      function calendar() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          locale: 'hy-am',
          timeZone: 'UTC',
          themeSystem: 'bootstrap5',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          views: {
            timeGridFourDay: {
              type: 'timeGrid',
              duration: { days: 4 }
            }
          },
          weekNumbers: true,
          dayMaxEvents: true, // allow "more" link when too many events
          // events: '/api/demo-feeds/events.json',
          events: `/educational-programs/calendar-data`
        });
        calendar.render();

      }

      calendar()


      // let calendarTds = document.querySelectorAll('#calendar td')
      //     calendarTds.forEach(element => {
      //       element.addEventListener('click', showReservetionInfo)
      //     });

      // function showReservetionInfo() {

      //   console.log(this)
      // }

      $('table[role=presentation]').on('click', "td", function(){
          var reserved_date = $(this).attr('data-date')
          $.ajax({
            url: '/educational-programs/get-day-reservations/' + reserved_date,
            processData: false,
            contentType: false,
            type: 'get',
            beforeSend: function (x) {
              console.log('befor sebd')
            },
            success: function (data) {

              $('#show_reservetion').click()
            },
            // error: function (data) {
            //   var errors = data.responseJSON.errors;

            //   $.each(errors, function (field_name, error) {
            //     $(document).find('[name=' + field_name + ']').after('<span class="error text-strong text-danger">' + error + '</span>')
            //   })

            // }
          });

      })

      // =========== store educational program reservetion ==================================
      $('#reserve').on('submit', function (e) {
        e.preventDefault()
        var formData = new FormData($(this)[0]);
        var educational_program_type = 'educational_program'
        var educational_program_id = $("#educational_program_id").val()

        if ($("#educational_program_id").val() == 'null_id') {
          educational_program_type = 'excursion'

        }

        formData.append("type", educational_program_type)


        $('.error').html('')
        $.ajax({
          url: '/educational-programs/reserve-store',
          data: formData,
          processData: false,
          contentType: false,
          type: 'POST',
          beforeSend: function (x) {
            console.log('befor sebd')
          },
          success: function (data) {

            $('.item').val('')
            $('.result_message').html(`<span class=" text-success">Ամրագրումը կատարված է</span>`)
            calendar()
          },
          error: function (data) {
            var errors = data.responseJSON.errors;

            $.each(errors, function (field_name, error) {
              $(document).find('[name=' + field_name + ']').after('<span class="error text-strong text-danger">' + error + '</span>')
            })

          }
        });
      })

      // ==================== E N D =======================================================


});

