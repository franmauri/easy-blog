import HelperData from './HelperData'
export default {
  mixins: [HelperData],
  methods: {
    loadMorePosts () {
      this.busy = true
      this.loading = true
      if (!this.allLoaded) {
        axios.get('posts?limit=5&page=' + this.Page).then(post => {
          if (post.status === 200 && post.data.data !== null) {
            setTimeout(() => {
              if (this.posts[0] && this.posts[0].length > 0) {
                this.posts[0].push(...post.data.data)
              } else {
                this.posts.push(post.data.data)
              }
              this.Page = post.data.pagination.currentPage + 1
              this.busy = false
              this.loading = false
            }, 1000)
          } else {
            this.allLoaded = true
            this.loading = false
          }
        })
      }
    }
  }
}
