$(document).ready(function () {
    let $dropArea = $('#app-drop-area');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        $dropArea.on(eventName, preventDefaults);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        $dropArea.on(eventName, () => $dropArea.addClass('app-hover'));
    });

    ['dragleave', 'drop'].forEach(eventName => {
        $dropArea.on(eventName, () => $dropArea.removeClass('app-hover'));
    });

    $dropArea.on('drop', function (e) {
        let files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        $.each(files, function (i, file) {
            uploadFile(file);
        });
    }

    function cleanResponses() {
        let $responses = $('.app-response');
        let number = 3;
        if ($responses.length > number) {
            $responses.eq(number).fadeOut('slow', function () {
                $(this).remove();
            });
        }
    }

    function uploadFile(file) {
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
            let $responses = $('#app-responses');
            let base64String = reader.result.split(',')[1];
            let data = {
                file: base64String,
                name: file.name,
            };

            let $loading = $('<div class=" alert alert-primary" role="alert" style="opacity: 0;">Loading...</div>');
            $responses.prepend($loading);
            $loading.fadeTo('slow', 1);

            $.ajax({
                url: '/api/upload',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    $loading.remove();
                    let formatedResponse = JSON.stringify(response, null, 2 );
                    let $response = $('<div class="app-response alert alert-success" role="alert" style="opacity: 0;">').text(formatedResponse);
                    $responses.prepend($response);
                    $response.fadeTo('slow', 1);

                    cleanResponses();
                },
                error: function (response) {
                    $loading.remove();
                    let formattedResponse = JSON.stringify(response.responseJSON);
                    let $response = $('<div class="app-response alert alert-danger" role="alert" style="opacity: 0;">').text(formattedResponse);
                    $('#app-responses').prepend($response);
                    $response.fadeTo('slow', 1);

                    cleanResponses();
                },
            });
        };
    }
});
