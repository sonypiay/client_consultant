<template>
  <div>
    <div class="uk-height-viewport banner-homepage-overlay">
      <div class="uk-grid-collapse uk-grid-match" uk-grid>
        <div class="uk-width-2-5">
          <div class="uk-tile uk-tile-default uk-text-center banner-text-homepage">
            <div class="uk-position-center uk-padding">
              <div uk-slider class="uk-light">
                <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">
                  <ul class="uk-slider-items uk-child-width-1-1@s uk-grid">
                    <li>
                      <div class="banner-slider">
                        <div class="uk-text-center">
                          <div class="banner-slider-text">
                            Solusi Anda untuk Masalah Pajak-Klien
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="banner-slider">
                        <div class="uk-text-center">
                          <div class="banner-slider-text">
                            Buat janji dan atur jadwal bersama
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="banner-slider">
                        <div class="uk-text-center">
                          <div class="banner-slider-text">
                            Bertemu dengan Konsultan
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="banner-slider">
                        <div class="uk-text-center">
                          <div class="banner-slider-text">
                            Masalah Terselesaikan! Beri Ulasan
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                </div>
                <!--<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>-->
              </div>
            </div>
          </div>
        </div>
        <div class="uk-width-expand">
          <div class="uk-background-cover uk-height-viewport" :style="{'background-image': 'url(' + $root.url + '/assets/images/banner/homebanner.jpg)'}">
            <navbar-homepage
            :haslogin="haslogin"
            :getuser="getuser"
             />
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
