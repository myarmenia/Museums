
$(function(){

let count=0

  $('#add_event_config').on("click", function () {

    $('#config_div').css('display','block')
      let btnId=$(this).attr('data-id')
      count++
      $.get('/events/config/component/'+btnId+'/'+count, function(data) {
        console.log(data.errors)
            $('#event_config').append(data);
            // var errors = data.responseJSON.errors;
// console.log(errors)

            // $.each(errors, function (field_name, error) {
            //   $that.find('[name=' + field_name + ']').after('<span class="error text-strong text-danger">' + error + '</span>')
            // })

        });


    })

    $('.delete-event-config').on('click',function(){
       let id=$(this).attr('data-item-id')
      let tb_name = $(this).attr('data-tb-name')
      let url = `/delete-item/${tb_name}/${id}`
      let that=$(this)
      $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function (data) {
          let message = ''
          let type = ''
          if (data.result) {
            that.parents(".item_config").remove()

          }
        }
      });


    })
    // =====================event-config=====================
    $('#submit_event_config').on('submit', function (e){
      e.preventDefault()
      $(".event-config-log").html('')

      var $that = $(this)
      var formData = new FormData($(this)[0]);
      console.log(formData)
      var url='/events/event-config'
      //
      $.ajax({
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        type: 'post',
        beforeSend: function (x) {
          console.log('befor sebd')
        },
        success: function (data) {

          $('#config_div').css('display','none')
          // if (method == 'post') {
            if(data.message){
              window.location.reload();
            }
            // $('#events_config_append').append(444)
            // $.get('/call-edit-component', function(data) {
            //   console.log(data)
                  // $('#event_config').append(data);
                  // var errors = data.responseJSON.errors;
                  // console.log(errors)

                  // $.each(errors, function (field_name, error) {
                  //   $that.find('[name=' + field_name + ']').after('<span class="error text-strong text-danger">' + error + '</span>')
                  // })

              // });
        },
        error: function (data) {

          if(data.errorMessage){
            console.log(data.errorMessage)
          }

          var errors = data.responseJSON.errors;

          $.each(errors, function (field_name, error) {


            $that.find('[data-id="' + field_name + '"]').append("<div class='col-sm-10 mt-2 text-danger fts-14 event-config-log' >" + error + "</div>")
          })

        }
      });
  

    } )

})


