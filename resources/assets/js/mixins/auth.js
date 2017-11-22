import {PassportConfig, Config, appUrl} from '../config/app'

export default {
  data () {
    return {
      loginError: false
    }
  },
  beforeMount: function () {
    if (localStorage.getItem('token')) {
      this.$auth.destroyToken()
    }
  },
  mounted () {
    axios.get(appUrl + '/mytoken').then(data => {
      if (data.data.token === null || data.data.ad === null || data.data.token === 'undefined' || !data.data.token) {
        console.log('error')
      } else {
        this.$auth.setToken(data.data.token, 10 + Date.now())
        localStorage.setItem('viaAD', data.data.ad)
        window.location.href = '/'
      }
    })
  },
  methods: {
    // Javascript Promise to Send Response for login
    // @return true || false
    login () {
      if ((this.username !== '' || this.username !== 'undefined') && (this.password !== '' || this.password !== 'undefined')) {
        let PostData = {
          client_id: PassportConfig.clientId,
          client_secret: PassportConfig.clientSecret,
          grant_type: 'password',
          username: this.username,
          password: this.password
        }
        axios.post(Config.apiTokenLogin, PostData).then(response => {
          if (response.status === 200) {
            this.$auth.setToken(response.data.access_token, parseInt(response.data.expires_in) * 10 + Date.now())
            setTimeout(() => {
              window.location.href = '/'
            }, 100)
          }
        }, response => {
          this.loginError = true
          setTimeout(() => {
            this.loginError = false
          }, 1500)
        })
      }
    }
  }
}
