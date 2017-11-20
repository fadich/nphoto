;(function () {
    "use strict";

    let $form = $('#upload-form');
    let $fileInput = $form.find('input#upload')

    $form.change(function (ev) {
        $form.submit()
    })

    $form.submit(function (ev) {
        ev.preventDefault()

        let formData = new FormData(this);
        let files = $fileInput.hasOwnProperty('0') ? $fileInput[0].files : []

        for (let file of files) {
            formData.set('photos[]', file)

            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (res) {
                    for (let file of res.files) {
                        if (file) {
                            let img = document.createElement('img')

                            img.src = '/' + file
                            document.body.appendChild(img)
                        }
                    }
                },
                error: function (error) {
                    console.error(error.status + ': ' + error.statusText)
                }
            })
        }
    })

})()