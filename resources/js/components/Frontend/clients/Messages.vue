<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Pesan</div>
    </div>

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
      <div class="uk-box-shadow-small messages-container">
        <div class="uk-grid-collapse uk-grid-match" uk-grid>
          <div class="uk-width-1-5">
            <nav>
              <ul class="uk-nav uk-nav-default nav-message-sender">
                <li v-for="n in 10">
                  <a>John doe</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="uk-width-expand">
            <div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'haslogin',
    'getuser',
    'getconsultant'
  ],
  data() {
    return {
      getmessages: {
        total: 0,
        results: []
      }
    }
  },
  methods: {},
  mounted() {}
}
</script>
