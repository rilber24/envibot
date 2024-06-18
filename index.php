<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envibot</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="wrapper" id="chat-wrapper">
        <div class="title">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="msg-header">
                    <p>Hola, Bienvenido a Envibol</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <textarea id="data" placeholder="Escribe algo aquí.." required></textarea>
                <button id="send-btn" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
    <div id="chat-button">
        <img src="chat.png" alt="">
    </div>
    <script>
        $(document).ready(function() {
            // Show/hide chat on button click
            $('#chat-button').click(function() {
                $('#chat-wrapper').toggle();
            });

            // Function to send message
            function sendMessage() {
                let value = $("#data").val();
                if (value.trim() === '') {
                    return;
                }
                let msg = '<div class="user-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>' + value + '</p></div></div>';
                $(".form").append(msg);
                $("#data").val('');
                // Reset textarea height
                $("#data").css('height', '40px');
                // Disable send button
                $("#send-btn").attr('disabled', true);
                // Start AJAX code
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    data: 'prompt=' + value,
                    success: function(result) {
                        // Parse result to find URLs and make them clickable
                        let formattedResult = result
                            .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>')
                            .replace(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi, '<a href="mailto:$1">$1</a>')
                            .replace(/(\+59167010618)/g, '<a href="https://api.whatsapp.com/send/?phone=59167010618&text&type=phone_number&app_absent=0">$1</a>');
                        let replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-robot"></i></div><div class="msg-header"><p>' + formattedResult + '</p></div></div>';
                        $(".form").append(replay);
                        // When chat goes down the scroll bar automatically comes to the bottom
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
                });
            }

            // Adjust textarea height and enable/disable send button
            $("#data").on("input", function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';

                if ($(this).val().trim() !== '') {
                    $("#send-btn").removeAttr('disabled');
                } else {
                    $("#send-btn").attr('disabled', true);
                }
            });

            // Send message on button click
            $("#send-btn").on("click", sendMessage);

            // Send message on enter key press
            $("#data").on("keypress", function(e) {
                if (e.which == 13 && !e.shiftKey) {
                    sendMessage();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
