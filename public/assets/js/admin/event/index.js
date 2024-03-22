
$(function(){


  let count = 0
  $('#add_event_config').on("click", function () {

      let btnId=$(this).attr('data-id')
      count++
      $(this).attr('data-conf-count',count)
      $.get('/events/config/component/'+btnId+'/'+count, function(data) {

            $('#event_config').append(data);

          $('.delete-config').on('click',function(){
            console.log($(this).parent().parent().remove())
                $(this).parent().parent().remove()


                if($('#event_config>div').length==0){
                  console.log($('.item_config').length);

                  $('#config_div').css('display','none')

                }
          })
            $('#config_div').css('display','block')
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

    $('.event_config_update').on('submit',function(e){
      e.preventDefault()
      var formData = new FormData($(this)[0]);
      let $that=$(this)
      let id=$(this).attr('data-config-id')
      // console.log(id)
     let tb_name = $(this).attr('data-tb-name')
     let url = `events/event-config-update`
     $('.invalid_error').html('')

     $.ajax({
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      type: 'Post',
      beforeSend: function (x) {
        console.log('befor sebd')
      },
      success: function (data) {

          if(data.message){

          //  $(this).find('.btn-outline-danger').
          //  $(this).css("background-color", "green");

          }

      },
      error: function (data) {



        var errors = data.responseJSON.errors;


        $.each(errors, function (field_name, error) {
          let k=$that.find('[data-id="' + field_name + '"]')

          $that.find('[data-id="' + field_name + '"]').innerHTML=''
          $that.find('[data-id="' + field_name + '"]').append("<div class='col-sm-10 mt-2 text-danger fts-14 event-config-log' >" + error + "</div>")
        })

      }
    });


   })

})


