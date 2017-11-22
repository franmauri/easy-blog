let actions = {
  updateUser: ({commit}, user) => {
    commit('ADD_USER', user)
  },
  updateProfile: ({commit}, profile) => {
    commit('UPDATE_PROFILE', profile)
  },
  deleteUser: ({commit}) => {
    commit('DELETE_USER')
  },
  updateLanguage: ({commit}, language) => {
    commit('UPDATE_LANGUAGE', language)
  },
  
}

export {actions}
