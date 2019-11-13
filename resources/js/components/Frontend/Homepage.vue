<template>
  <div>
    <div class="uk-background-cover uk-height-viewport" :style="{'background-image': 'url(' + $root.url + '/assets/images/banner/homepage.jpg' + ')'}">
      <div class="uk-overlay uk-position-cover uk-padding-remove banner-homepage-overlay">
        <navbar-homepage
        :haslogin="haslogin"
        :getuser="getuser" />
        <div class="uk-width-2-3 uk-align-center uk-text-center banner-find-consultant">
          <div class="banner-text">
            Find a professional consultant you needs. <br>Make your appointment today.
          </div>
          <!--
          <input @keyup.enter="searchConsultant()" v-model="forms.keywords" type="search" class="uk-width-2-3 uk-input banner-search-form" placeholder="Enter your keywords..." />
          -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'haslogin',
    'getuser'
  ],
  data() {
    return {
      forms: {
        keywords: ''
      }
    }
  },
  methods: {
    searchConsultant()
    {
      let url = this.$root.url + '/search?keywords=' + this.forms.keywords;
      document.location = url;
    }
  }
}
</script>

<style lang="css" scoped>
</style>
