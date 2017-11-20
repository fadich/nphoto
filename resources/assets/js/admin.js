;(function () {
    "use strict";

    let $list = $('.photo-list')
    let $form = $('#upload-form');
    let $fileInput = $form.find('input#upload')

    $(document).ready(function () {
        getPhotos()
    })

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
                    for (let photo of res.photos) {
                        if (photo) {
                            photoForm(photo)
                        }
                    }
                },
                error: function (error) {
                    console.error(error.status + ': ' + error.statusText)
                }
            })
        }
    })

    /*********** HELPERS ***********/
    function photoForm(photo) {
        $list.prepend(photoItemTemplate(photo))
    }

    function photoItemTemplate(photo) {
        return `
            <div class="photo-list-item col-md-4">
                <div class="actions">
                    <a class="pull-right">
                        <span class="delete">X</span>
                    </a>
                </div>
                <form action="/admin/photos/update/` + photo.id + `" method="post">
                    <div class="photo-list-item-image">
                        <img src="/` + photo.fullPath + `">
                    </div>
                    ` + textFieldTemplate('clientFilename', photo.clientFilename, 'disabled') + `
                    ` + textFieldTemplate('title', photo.title, 'placeholder="Photo title"') + `
                    ` + textAreaTemplate('description', photo.description, 'placeholder="Description"') + `
                </form>
            </div>
        `;
    }

    function textFieldTemplate(name, value, attributes) {
        value = value || ''
        attributes = attributes || ''

        return `
            <div class="form-group">
                <input type="text" class="form-control" name="` + name + `" value="` + value + `" ` + attributes +`>
            </div>
        `;
    }

    function textAreaTemplate(name, value, attributes) {
        value = value || ''
        attributes = attributes || ''

        return `
            <div class="form-group">
                <textarea class="form-control" name="` + name + `" id="" cols="30" rows="10" ` + attributes + `>` + value + `</textarea>
            </div>
        `;
    }

    function getPhotos() {
        $.ajax({
            type: 'GET',
            url: '/admin/photos/list',
            success: function (res) {
                for (let photo of res.photos) {
                    if (photo) {
                        photoForm(photo)
                    }
                }
            },
            error: function (error) {
                console.error(error.status + ': ' + error.statusText)
            }
        })
    }

})()