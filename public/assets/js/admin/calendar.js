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

