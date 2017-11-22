let mutations = {
  ADD_USER (state, user) {
    state.user = user
  },
  DELETE_USER (state) {
    state.user = null
  },
  UPDATE_PROFILE (state, profile) {
    state.profile = profile
  },
  UPDATE_LANGUAGE (state, language) {
    state.GUILanguage = language
  },
  
}

export { mutations }
