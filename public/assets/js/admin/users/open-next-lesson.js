$(function(){
  $('.open-next-lesson').on('click', function(){
      let user_id = $('#user_id').attr('data-id')
      let course_language_id = $(this).parents('.action').attr('data-id')
      console.log(course_language_id)
      let thisElement = $(this)
      $.ajax({
          type: "post",
          url: "/open-next-lesson",
          data: { user_id, course_language_id},
          cache: false,
          success: function (data) {
              if(data.result){
                  thisElement.closest('tr').find('.lesson_number').html(data.result)
              }
          }
      });

  })
})
