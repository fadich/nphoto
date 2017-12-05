;(function () {
    "use strict";

    const PER_PAGE = 6

    let $list = $('.photo-list')
    let $uploadForm = $('#upload-form')
    let $itemForm = $('.photo-list-item-form')
    let $fileInput = $uploadForm.find('input#upload')
    let page = 1
    let nextPage = true
    let photos = []

    $(document).ready(function () {
        getPhotos({
            page: page++,
            perPage: PER_PAGE
        })
        .then((res) => {
            photos = res.photos
            if (photos.length < PER_PAGE) {
                nextPage = false
            }
        })
        .then(function () {
            paginatePhotos()
        })
    })

    $($uploadForm, $itemForm).change(function (ev) {
        $(this).submit()
    })

    $uploadForm.submit(function (ev) {
        ev.preventDefault()

        let formData = new FormData(this);
        let files = $fileInput.hasOwnProperty('0') ? $fileInput[0].files : []

        for (let file of files) {
            formData.set('photos[]', file)

            $.ajax({
                type: 'POST',
                url: $uploadForm.attr('action'),
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

    $(document).on('scroll', function () {
        if (isNearBottom()) {
            loadMore()
        }
    })

    $(document).on('click', '#load-more', loadMore)

    /*********** HELPERS ***********/
    function photoForm(photo) {
        $list.append(photoItemTemplate(photo))
    }

    function photoItemTemplate(photo) {
        return `
            <div class="photo-list-item col-md-4">
                <div class="actions">
                    <a class="pull-right">
                        <span class="delete">X</span>
                    </a>
                </div>
                <form action="/admin/photos/update/` + photo.id + `" method="post" class="photo-list-item-form">
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
                <textarea class="form-control" name="` + name + `" id="" cols="30" rows="5" ` + attributes + `>` + value + `</textarea>
            </div>
        `;
    }

    function displayPhotos(photos) {
        return new Promise(function (resolve, reject) {
            for (let photo of photos) {
                if (photo) {
                    photoForm(photo)
                }
            }

            resolve()
        })
    }

    function getPhotos(parameters) {
        return $.when(
            $.ajax({
                type: 'GET',
                url: '/admin/photos/list',
                data: parameters,
                async: true,
                cache: false,
            })
        )
    }

    function paginatePhotos() {

        displayPhotos(photos)
            .then(function () {
                getPhotos({
                    page: page++,
                    perPage: PER_PAGE
                })
                    .then((res) => {
                        photos = res.photos
                        if (photos.length < PER_PAGE) {
                            nextPage = false
                        }
                    })
            })
            .then(() => {
                photos = []
                if (nextPage) {
                    $list.parent().append(`
                        <div class="load-more-wrap">
                            <button id="load-more" class="btn btn-default">
                            -- Load more --
                            </button>
                        </div>
                    `)
                }
            })
    }

    function loadMore() {
        $('#load-more').remove()
        paginatePhotos()
    }

    function isNearBottom() {
        let height = $(document).height()
        let scrolled = $(document).scrollTop() + $(window).height()
        let trigger = height - height / 100 * 10

        return scrolled >= trigger;
    }

})()