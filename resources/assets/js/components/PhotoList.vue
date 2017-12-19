<template>
    <div class="component-wrapper">
        <h1 align="center">All photos</h1>

        <div class="all-uploads">
            <div class="all-uploads-item shadow-border"
                 v-for="(photo, index) in photos">

                <a href="#"
                   data-toggle="tooltip"
                   :title="getTooltip(photo)"
                   @click="preview(index)">
                    <img :src="'/' + photo.small" class="uploaded-image">
                </a>


                <div class="preview-modal"
                     v-if="currentPreview == index">
                    <div class="preview-modal-background"></div>
                    <div class="preview-modal-close">
                        <a href="#" @click="closePreview">
                            <span class="glyphicon glyphicon-remove-circle"></span>
                        </a>
                    </div>
                    <a href="#" class="preview-photo"
                       @click="nextPhoto"
                       v-touch:swipe.left="nextPhoto"
                       v-touch:swipe.right="previousPhoto">
                        <div :style="'background-image: url(\'/' + (getDisplay(index)) + '\');'">
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="load-more-wrap">
            <button id="load-more"
                    class="btn btn-success"
                    v-if="!lastPage && photos.length >= perPage"
                    @click="nextPage"
                    v-on:show="nextPage">
                More
            </button>
        </div>
    </div>
</template>

<script>


    export default {
        data () {
            return {
                photos: [],
                tempPhotos: [], // Preparing to render...
                page: 1,
                perPage: 30,
                lastPage: false,
                blocked: false,
                currentPreview: -1
            }
        },
        methods: {
            getPhotos () {
                this.blocked = true
                this.$http.get('/photos?page=' + (this.page) + '&per-page=' + this.perPage).then((response) => {
                    this.lastPage = !response.body.photos.length % this.perPage

                    this.tempPhotos = response.body.photos
                    this.blocked = false

                    if (this.page === 1) {
                        this.nextPage()
                    }
                })
            },
            nextPage () {
                if (this.blocked || this.lastPage) {
                    return
                }

                this.blocked = true
                this.page++
                this.photos = this.photos.concat(this.tempPhotos)
                this.getPhotos()
            },
            handleScroll () {
                if (this.lastPage) {
                    return
                }

                if (document.body.offsetHeight - (window.innerHeight + window.scrollY) < 100) {
                    this.nextPage()
                }
            },
            preview (index) {
                console.log(2)
                if (index != this.currentPreview) {
                    this.currentPreview = index
                    document.body.className += ' no-scroll'
                    return
                }
            },
            closePreview () {
                document.body.className = document.body.className.replace(/no-scroll/g, '')
                this.currentPreview = -1
            },
            getTooltip (photo) {
                let t = '';

                if (photo.title) {
                    t += photo.title
                }

                if (t && photo.description) {
                    return t + '\n\n' + photo.description
                }

                return photo.description
            },
            getDisplay (index) {
                let photo = this.photos[index]

                if (!photo) {
                    return ''
                }

                return (document.body.offsetWidth > 1024) ? photo.large : photo.medium
            },
            nextPhoto () {
                if (this.currentPreview == this.photos.length - 1) {
                    this.currentPreview = 0;
                    return
                }

                let photo = this.photos[this.currentPreview + 1]

                if (photo) {
                    this.currentPreview++
                }
                if (
                    this.photos.length - this.currentPreview <= 5 &&
                    !this.lastPage
                ) {
                    this.nextPage()
                }
            },
            previousPhoto () {
                if (this.currentPreview - 1 < 0) {
                    this.currentPreview = this.photos.length - 1;
                    return
                }

                this.currentPreview--
            }
        },
        mounted() {
            this.getPhotos()
        },
        created: function () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed: function () {
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>
