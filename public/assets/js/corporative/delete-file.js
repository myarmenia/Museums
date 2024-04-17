
$(function () {
    $('#delete-file').on('click', function () {
        let id = $(this).attr('data-attr')
        let url = '/corporative/delete-file/' + id
        $.ajax({
            type: "DELETE",
            url: url,
            cache: false,
            success: function (data) {
                let message = ''
                let type = ''
                if (data.result) {
                    message = 'Գործողությունը հաստատված է։'
                    type = 'success'
                    $("#showed-file").remove();
                }
                else {
                    message = 'Սխալ է տեղի ունեցել։'
                    type = 'danger'
                }
            }
        });
    })
})