<template>
    <div class="component-wrapper">
        <h1 align="center">All photos</h1>

        <div class="all-uploads">
            <div class="all-uploads-item shadow-border"
                 v-for="photo in photos">

                <a data-toggle="tooltip"
                   :title="getTooltip(photo)"
                   @click="preview(photo)">
                    <img :src="'/' + photo.small" class="uploaded-image">
                </a>

                <a @click="preview(photo)">
                    <div class="bg-modal"
                         v-if="currentPreview == photo.id"
                         :style="'background-image: url(\'/' + (getDisplay(photo)) + '\');'">
                    </div>
                </a>
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
                currentPreview: 0
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
            preview (photo) {
                if (photo.id != this.currentPreview) {
                    this.currentPreview = photo.id
                    document.body.className += ' no-scroll'
                    return
                }

                document.body.className = document.body.className.replace(/no-scroll/g, '')
                this.currentPreview = 0
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
            getDisplay (photo) {
                return document.body.offsetWidth > 1024 ? photo.large : photo.medium
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
