;(function () {
    "use strict";

    const PER_PAGE = 8

    let $list = $('.photo-list')
    let $uploadForm = $('#upload-form')
    let $itemForm = $('.photo-list-item-form')
    let $fileInput = $uploadForm.find('input#upload')
    let page = 1
    let nextPage = true
    let photos = []
    let blockRequest = false

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
            $list.prepend(loadingItemTemplate(Date.now()))

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
                            let loadItems = $list.find('.photo-list-item.loading')

                            $list.prepend(photoItemTemplate(photo))

                            if (loadItems.length) {
                                loadItems[0].remove()
                            }
                        }
                    }
                },
                error: function (error) {
                    let loadItems = $list.find('.photo-list-item.loading')
                    if (loadItems.length) {
                        loadItems[0].remove()
                    }

                    console.error(error.status + ': ' + error.statusText)
                    alert(error.status + ': ' + error.statusText)
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

    $(document).on('change', '.photo-list-item-form', function () {
        $(this).submit()
    })

    $(document).on('click', '.delete', function () {
        let $this = $(this);
        let $item = $this.closest('.photo-list-item');
        let id = $item.data('id')

        if (!confirm("Delete this photo?")) {
            return
        }

        if (id) {
            // $.ajax({
            //     url: '/admin/photos/' + id + '/delete',
            //     method: 'post',
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success: function (res) {
            //         console.log(res)
            //         $item.remove()
            //     },
            //     error: function (err) {
            //         console.error(err)
            //         alert(err.status + ': ' + err.statusText)
            //     }
            // })

            $.ajax({
                url: '/admin/photos/' + id + '/update',
                data: {status: 0},
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    console.log(res)
                    $item.remove()
                },
                error: function (err) {
                    console.error(err)
                    alert(err.status + ': ' + err.statusText)
                }
            })
        }
    })

    $(document).on('submit', '.photo-list-item-form', function (ev) {
        let $this = $(this)
        let data = {}
        $.each($this.serializeArray(), function(_, kv) {
            data[kv.name] = kv.value;
        })
        let url = $this.attr('action')

        data.status = data.published ? 10 : 5
        delete data.published
        ev.preventDefault()

        $.ajax({
            url: url,
            data: data,
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log(res)
            },
            error: function (err) {
                console.error(err)
                alert(err.status + ': ' + err.statusText)
            }
        })
    })

    /*********** HELPERS ***********/
    function photoForm(photo) {
        $list.append(photoItemTemplate(photo))
    }

    function photoItemTemplate(photo) {
        return `
            <div class="photo-list-item col-md-3" data-id="` + photo.id + `">
                <div class="actions">
                    <a class="pull-right">
                        <span class="delete">X</span>
                    </a>
                </div>
                <form action="/admin/photos/` + photo.id + `/update" method="post" class="photo-list-item-form">
                    <div class="photo-list-item-image">
                        <img src="/` + photo.miniature + `">
                    </div>
                    ` + textFieldTemplate('clientFilename', photo.clientFilename, 'disabled') + `
                    ` + textFieldTemplate('title', photo.title, 'placeholder="Photo title"') + `
                    ` + textAreaTemplate('description', photo.description, 'placeholder="Description"') + `
                    ` + checkBoxTemplate('published', photo.status == 10, 'Published') + `
                </form>
            </div>
        `;
    }

    function loadingItemTemplate() {
        return `
            <div class="photo-list-item loading col-md-3">
                <p align="center">Loading...</p>
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

    function checkBoxTemplate(name, value, label) {
        value = value || ''
        label = label || ''

        return `
            <div class="form-group">
                <span><strong>` + label + `</strong></span>
                <input type="checkbox" 
                       class="checkbox checkbox--green pull-right" 
                       name="` + name + `" ` + (+value ? `checked` : ``) + `>
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
                if (!nextPage || blockRequest) {
                    return;
                }

                blockRequest = true
                getPhotos({
                    page: page++,
                    perPage: PER_PAGE
                })
                    .then((res) => {
                        photos = res.photos
                        if (photos.length < PER_PAGE) {
                            nextPage = false
                        }
                        blockRequest = false
                    })
            })
            .then(() => {
                photos = []
                if (nextPage) {
                    $list.parent().append(`
                        <div class="load-more-wrap">
                            <button id="load-more" class="btn btn-default">
                            Load more
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
