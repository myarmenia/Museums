
$(function(){

// let count=0
  $('#add_event_config').on("click", function () {
    // count++
    $('#config_div').css('display','block')
      let btnId=$(this).attr('data-id')

      // $('#event_config').append(k)
      $.get('/events/config/component/'+btnId , function(data) {
        console.log(data,count)
        // Append the loaded Blade component to the body
            $('#event_config').append(data);
        });
    })

})


