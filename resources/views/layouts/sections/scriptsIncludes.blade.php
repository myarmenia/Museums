<!-- laravel style -->
<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('assets/js/config.js') }}"></script>
<<<<<<< HEAD
=======
{{-- <script>
  let window_close_logout_url ="{{ route('logout') }}"

    window.addEventListener('beforeunload', async (e) =>{

        try {
          const res = await fetch(window_close_logout_url, {
            method: "POST",
            body: JSON.stringify({
                token: 'true'
            }),
            headers: {
                "Content-Type": "application/json",
            }
        })
        } catch (error) {
          console.log(error);

        }

    });

</script> --}}

>>>>>>> e908f484c60cddd62d9707ba39129108fff06abe

{{-- <script src="{{ asset('assets/js/admin/turnstile-managment.js') }}"></> --}}

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
