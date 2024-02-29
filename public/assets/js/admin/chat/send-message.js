$(function () {
    $('.add-message').click(function (e) {
        e.preventDefault();

        let messageText = $('.publisher-input').val();
        let chatId = $('#chat_id').val();
        $('#loader-message').show();
        $('#send-message').hide();
        

        $.ajax({
            url: '/chats/send-message',
            method: 'POST',
            data: {
                text: messageText,
                chat_id: chatId
            },
            success: function (response) {
                $('#loader-message').hide();
                $('#send-message').show();
                let message = response.message;
                let messageClass = 'media media-chat-reverse';
                let currentDate = new Date(message.created_at);
                let year = currentDate.getFullYear();
                let month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
                let day = ('0' + currentDate.getDate()).slice(-2); 

                let hours = ('0' + currentDate.getHours()).slice(-2); 
                let minutes = ('0' + currentDate.getMinutes()).slice(-2); 
                let seconds = ('0' + currentDate.getSeconds()).slice(-2);
                let createdAt =  year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
                console.log(createdAt)
                let newMessageHtml = '<div class="' + messageClass + '">' +
                    '<div class="media-body">' +
                    '<p>' + message.text + '</p>' +
                    '<p class="meta"><time datetime="2018">' + createdAt + '</time></p>' +
                    '</div>' +
                    '</div>';

                $('#chat-content').append(newMessageHtml);
                $('.publisher-input').val('');
            },
            error: function (xhr, status, error) {
                $('#loader-message').hide();
                $('#send-message').show();
                console.error(error);
            }
        });
    });
});
