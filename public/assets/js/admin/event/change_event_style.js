function changeEventType() {


  let eventType = document.getElementById("defaultSelect").value;
    if(eventType=='temporary'){
       document.getElementById('ticket_max_quantity').style.display = "none"
       $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function (data) {
          let message = ''
          let type = ''
          if (data.result) {
            message = 'Գործողությունը հաստատված է։'
            type = 'success'


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
