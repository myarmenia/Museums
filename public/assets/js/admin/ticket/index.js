// =========== store or update educational program reservetion ==================================
$('body').on('submit', '.ticket_settings', function (e) {
  e.preventDefault()
  var $that = $(this)
  var formData = new FormData($(this)[0]);
  var url = $(this).attr('action')

  $('.error').html('')

  $.ajax({
    url: url,
    data: formData,
    processData: false,
    contentType: false,
    type: 'post',
    success: function (data) {

      $('.message').html(`<div class="alert alert-primary">Գործողությունը կատարված է</div>`)
      window.location.reload()

    },
    error: function (data) {
      var errors = data.responseJSON.errors;

      $.each(errors, function (field_name, error) {
        $that.find('[name=' + field_name + ']').after('<span class="error text-strong text-danger">' + error + '</span>')
      })

    }
  });
})
// ==================== E N D =======================================================

