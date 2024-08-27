function changeEventType() {


  let eventType = document.getElementById("defaultSelect").value;
    if(eventType=='temporary'){
       document.getElementById('ticket_max_quantity').style.display = "none"
       $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function (data) {

          if (data.message == 'deleted') {
            $('#events_config_append').html('')



          }
          else {
            message = 'Սխալ է տեղի ունեցել։'
            type = 'danger'
          }


        }
      });

    }else{
        document.getElementById('ticket_max_quantity').style.display = "flex"
    }

}
