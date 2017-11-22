import Vue from 'vue'
import Vuex from 'vuex'
import { getters } from './src/getters'
import { actions } from './src/actions'
import { mutations } from './src/mutations'

Vue.use(Vuex)

let state = {
  user: {},
  GUILanguage: [],
  
  profile: []
}

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations
})
