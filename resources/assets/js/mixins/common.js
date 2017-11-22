export default {
    methods: {
        unsetApiBaseUrlAxios() {
            let baseUrl = window.axios.defaults.baseURL;
            window.axios.defaults.baseURL = document.location.origin;
        },
        setApiBaseUrlAxios(url) {
            window.axios.defaults.baseURL = url;
        },

    }
}
