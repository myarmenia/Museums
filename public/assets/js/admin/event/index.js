
$(function(){

let count=0

  $('#add_event_config').on("click", function () {

    $('#config_div').css('display','block')
      let btnId=$(this).attr('data-id')


      $.get('/events/config/component/'+btnId+'/'+count, function(data) {
        count++
        console.log(data)
        // Append the loaded Blade component to the body
            $('#event_config').append(data);
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
    $('#submit_event_config').on('submit', function (e){
      e.preventDefault()
      var $that = $(this)
      var formData = new FormData($(this)[0]);
      console.log(formData)
    } )

})


