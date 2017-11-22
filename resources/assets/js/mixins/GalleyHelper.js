import HelperData from './HelperData'
export default {
  mixins: [HelperData],
  methods: {
    loadMore (Gallery, Object) {
      this.loaded = true
      this.Page += 1
      if (!this.ThereIsNoMoreData) {
        this.loading = true
        setTimeout(() => {
          window.onscroll = () => {
            if ($(window).scrollTop() + $(window).height() === $(document).height()) {
              window.onscroll = () => {
                return false
              }

              Gallery.load(post => {
                this.pagination = post.pagination
                post.data !== null ? post.data.filter((d) => Object[0].push(d)) : false
                this.loading = false
                this.loadMore(Gallery, this.albums, this.Page, this.maxLength)
                this.loaded = false
                if (post.data === null) {
                  this.ThereIsNoMoreData = true
                  this.loading = false
                } else {
                  $('html, body').animate({ scrollTop: $('#photos').offset().top + document.getElementById('photos').clientHeight }, 500)
                }
              }, this.Page)
            }
          }
        }, 50)
      } else {
        this.loading = false
      }
    }
  }
}
