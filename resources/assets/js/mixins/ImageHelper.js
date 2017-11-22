import HelperData from './HelperData'
export default {
  mixins: [HelperData],
  methods: {
    loadMore (Gallery, Object) {
      this.loaded = true
      this.Page += 1
      if (!this.ThereIsNoMoreData) {
        setTimeout(() => {
          window.onscroll = () => {
            if ($(window).scrollTop() + $(window).height() === $(document).height()) {
              window.onscroll = () => {
                return false
              }

              Gallery.loadImages(post => {
                this.pagination = post.pagination
                post.data !== null ? post.data.filter((d) => Object.photos.push(d)) : false
                this.loading = false
                this.loadMore(Gallery, this.images)
                this.perPage += 12
                this.loaded = false
                if (post.data === null) {
                  this.ThereIsNoMoreData = true
                  this.loading = false
                } else {
                  $('html, body').animate({scrollTop: $('#posts').offset().top + document.getElementById('posts').clientHeight}, 500)
                }
              }, this.$route.params.id, this.Page)
            }
          }
        }, 50)
      } else {
        this.loading = false
      }
    }
  }
}
