export default {
  computed: {
    canUpdatePost () {
      return _.includes(this.$root.getUser.permissions, 'update posts')
    },
    canDeletePost () {
      return _.includes(this.$root.getUser.permissions, 'delete posts')
    },
    canCreatePost () {
      return _.includes(this.$root.getUser.permissions, 'create posts')
    },
    isAdmin () {
      return this.$root.getUser.is_admin
    }
  }
}
