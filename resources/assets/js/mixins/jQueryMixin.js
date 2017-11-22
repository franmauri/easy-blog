require('moment/locale/sl')
export default {
  created () {
    setTimeout(() => {
      this.interfaceIupdate()
    }, 1000)
  },
  mounted () {
    Bus.$on('communityUpdated', () => {
      this.interfaceIupdate()
    })
  },
  updated () {
    this.interfaceIupdate()
  },
  methods: {
    interfaceIupdate () {
      jQuery('.tabset').tabset({
        tabLinks: 'a',
        addToParent: true
      })
      $('.datetimepicker1').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'sl'
      })
      $('.datetimepicker').datetimepicker({
        format: 'DD/MM/YYYY',
        locale: 'sl'
      })
      $('#datetimepickerI').datetimepicker({
        inline: true,
        format: 'LD',
        locale: 'sl'
      })
      jcf.setOptions('Select', {
        wrapNative: false,
        fakeDropInBody: true,
        flipDropToFit: true
      })
      $(function () {
        jcf.replaceAll()
      })
    }
  }
}
