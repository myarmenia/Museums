function changeEventType() {


  let eventType = document.getElementById("defaultSelect").value;
    if(eventType=='temporary'){
       document.getElementById('ticket_max_quantity').style.display = "none"
    }else{
        document.getElementById('ticket_max_quantity').style.display = "flex"
    }

}
