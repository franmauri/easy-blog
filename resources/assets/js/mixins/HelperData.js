export default {
  data () {
    return {
      loaded: false,
      ThereIsNoMoreData: false
    }
  },
  methods: {
    scrollFunction (Post, Object) {
      window.onscroll = () => {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
          window.onscroll = () => {
            return false
          }

          Post.loadImages(post => {
            this.pagination = post.pagination
            post.data !== null ? post.data.filter((d) => Object.photos.push(d)) : false
            this.loading = false
            this.loadMore(Post, this.images)
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
    }
  }
}
