<template>
    <div class="component-wrapper">
        <h1 align="center">All photos</h1>

        <div class="all-uploads">
            <div class="all-uploads-item shadow-border"
                 v-for="photo in photos"
                 :class="currentPreview == photo.id ? 'previewed' : ''">

                <a data-toggle="tooltip"
                   :title="photo.title"
                   @click="preview(photo)">

                    <img :src="'/' + (currentPreview == photo.id ? photo.original : photo.miniature)"
                         class="uploaded-image">
                </a>

                <div class="bg-modal" v-if="currentPreview"></div>
            </div>
        </div>
        <div class="load-more-wrap">
            <button id="load-more"
                    class="btn btn-success"
                    v-if="!lastPage"
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
                if (photo.id == this.currentPreview) {
                    this.currentPreview = 0
                    return
                }

                this.currentPreview = photo.id
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
