document.addEventListener('DOMContentLoaded', function (e) {

  document.getElementById('openDoor').addEventListener('click', function () {

    var button = this;
    var isOpen = button.innerText === "Բացել";

    // button.innerText = isOpen ? "Փակել" : "Բացել";
    // button.classList.toggle("btn-danger", !isOpen);
    // button.classList.toggle("btn-primary", isOpen);

    var payload = {
      action: isOpen ? "open" : "close"
    };
    // console.log(payload)


    // Send the POST request to the management-to-turnstile endpoint
    fetch('http://192.168.10.44/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        // // 'Content-Length': 18
        'Access-Control-Allow-Origin': '*'

      },
      body: JSON.stringify(payload),
      mode: 'no-cors',
    })

      .then(data => {
        console.log('Success:', data); // Handle the parsed data
      })
      .catch(error => {
        console.error('Error:', error); // Handle any errors
      });
    // .then(response => {
    //   console.log(response.text() + 777)
    //   // if (!response.ok) {
    //   //   console.log(response)
    //   //   // throw new Error('Network response was not ok');
    //   // }

    //   const contentType = response.headers.get('Content-Type');
    //   if (contentType && contentType.includes('application/json')) {
    //     console.log(response.json())

    //     return response.json(); // parse JSON if the response is JSON
    //   } else {
    //     console.log(444);
    //     console.log(response.text());


    //     return response.text(); // parse text otherwise
    //   }
    // })
    // .then(data => {
    //   console.log(data)
    //   if (data) {
    //     console.log(11111)
    //     resultAction.innerText = isOpen ? "Դուռը բացվեց" : "";
    //     button.classList.toggle("btn-danger", !isOpen);
    //     button.classList.toggle("btn-primary", isOpen);
    //     resultActionText.innerText = isOpen ? "Փակել" : "Բացել";
    //   }
    //   else {
    //     console.log('response catch')
    //     resultAction.innerText = 'Սխալ է տեղի ունեցել'
    //     setTimeout(() => {
    //       resultAction.innerText = ''
    //       // button.classList.toggle("btn-danger", isOpen);
    //       // resultActionText.innerText = "Բացել";
    //     }, 2000);

    //   }

    // })
    // .catch((error) => {
    //   console.log('catch')
    //   resultAction.innerText = 'Սխալ է տեղի ունեցել'
    //   setTimeout(() => {
    //     resultAction.innerText = ''
    //     // button.classList.toggle("btn-danger", isOpen);
    //     // resultActionText.innerText = "Բացել";
    //   }, 2000);

    // });

  })
})


