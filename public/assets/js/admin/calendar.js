document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'hy-am',
        timeZone: 'UTC',
        themeSystem: 'bootstrap5',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',

        },


        weekNumbers: true,
        dayMaxEvents: true, // allow "more" link when too many events
        events: '/api/demo-feeds/events.json',
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday'],

        // events: `/student-attendances/${student_id}`
    });


    calendar.render();
  let calendarTds = document.querySelectorAll('#calendar td')
  calendarTds.forEach(element => {
    element.addEventListener('click', reserved)
  });

  function reserved(){
    console.log(this)
  }


});

$( '#reserve' ).on('submit', function ( e ) {
    e.preventDefault()
    var formData = new FormData($(this)[0]);
    var educational_program_type = 'educational_program'
    var educational_program_id = $( "#educational_program_id" ).val()

    if($( "#educational_program_id" ).val() == 0){
          educational_program_type = 'excursion'
          educational_program_id = null
    }
    
    formData.append("type", educational_program_type)
    formData.append("educational_program_id", educational_program_id)

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
      success: function(data){
        alert(data);
      },
      error: function(data){
        var errors = data.responseJSON.errors;

        $.each(errors,function(field_name,error){
          $(document).find('[name='+field_name+']').after('<span class="error text-strong text-danger">' +error+ '</span>')
        })

      }
    });
})

