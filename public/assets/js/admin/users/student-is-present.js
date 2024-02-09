$(function(){
    $('.present').on('click', function(){
        let id = $(this).parents('.action').attr('data-id')
        let thisElement = $(this)
        $.ajax({
            type: "GET",
            url: "/student-is-present/"+id,
            cache: false,
            success: function (data) {
                if(data.result){
                    thisElement.closest('tr').find('.lesson_quantity').html(data.result)
                }
            }
        });

    })
})