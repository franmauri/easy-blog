export default {
  updated () {
    let self = this
    document.addEventListener('keyup', (e) => {
      if (e.keyCode === 27 && this.hasOwnProperty('modals')) {
        $('.comment-form .input .form-control').css('width', '90%')
        Object.keys(this.modals).filter((modal) => {
          eval('self.modals.' + modal + ' = ' + 'false')
        })
      }
    })
  },
  methods: {
    showModal (modal, id = 0, index = '') {
      Bus.$emit('clearReply')
      if (id !== 0) this.selectedPostId = id
      if (index !== '') {
        this.selectedPostIndex = index
        setTimeout(() => {
          this.selectedPost.index = index
          $('.comment-form .input .form-control').css('width', '94%')
          $('.collapse-n-' + index + 1).removeClass('in')
        }, 200)
      }
      if (this.modals.hasOwnProperty(modal)) {
        eval('this.modals.' + modal + ' = ' + ' !this.modals.' + modal)
      }
    }
  }
}
