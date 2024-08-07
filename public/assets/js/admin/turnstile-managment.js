document.addEventListener('DOMContentLoaded', function (e) {

  document.getElementById('openDoor').addEventListener('click', function () {
    console.log(899)
    var button = this;
    var isOpen = button.innerText === "Բացել";

    // button.innerText = isOpen ? "Փակել" : "Բացել";
    // button.classList.toggle("btn-danger", !isOpen);
    // button.classList.toggle("btn-primary", isOpen);

    var payload = {
      action: isOpen ? "open" : "close"
    };
    console.log(payload)


    // Send the POST request to the management-to-turnstile endpoint
    fetch('/museum/managment-to-turnstil', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })
      .then(response => response.json())
      .then(data => {
        console.log(data.data)
          if (data.data){
            resultAction.innerText = isOpen ? "Դուռը բացվեց" : "";
              button.classList.toggle("btn-danger", !isOpen);
              button.classList.toggle("btn-primary", isOpen);
              resultActionText.innerText = isOpen ? "Փակել" : "Բացել";
          }
          else{
            resultAction.innerText = 'Սխալ է տեղի ունեցել'
            setTimeout(() => {
              resultAction.innerText = ''
              // button.classList.toggle("btn-danger", isOpen);
              // resultActionText.innerText = "Բացել";
            }, 2000);

          }

      })
      .catch((error) => {
          resultAction.innerText = 'Սխալ է տեղի ունեցել'
          setTimeout(() => {
            resultAction.innerText = ''
            // button.classList.toggle("btn-danger", isOpen);
            // resultActionText.innerText = "Բացել";
          }, 2000);

      });

  })
})


