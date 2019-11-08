<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Find Consultant</div>
    </div>
    <div class="uk-container uk-margin-top uk-margin-large-bottom">
      <div class="uk-grid-small uk-child-width-auto" uk-grid>
        <div>
          <select class="uk-select gl-input-default" v-model="forms.sorting" @change="showConsultant()">
            <option value="recommended">Recommended</option>
            <option value="latest">Latest</option>
            <option value="asc">A to Z</option>
            <option value="desc">Z to A</option>
          </select>
        </div>
        <div>
          <select class="uk-select gl-input-default" v-model="forms.limit" @change="showConsultant()">
            <option value="10">10 rows</option>
            <option value="20">20 rows</option>
            <option value="50">50 rows</option>
          </select>
        </div>
        <div>
          <input @keyup.enter="showConsultant()" type="search" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Find consultant name...." />
        </div>
      </div>

      <div v-if="getconsultant.isLoading" class="uk-margin-top uk-text-center">
        <span uk-spinner></span> load content...
      </div>
      <div v-else>
        <div v-if="getconsultant.errorMessage" class="uk-alert-danger uk-margin-top" uk-alert>
          {{ getconsultant.errorMessage }}
        </div>
        <div v-else>
          <div v-if="getconsultant.total == 0" class="uk-margin-top">
            No consultant is available
          </div>
          <div v-else class="uk-grid-medium uk-margin-top uk-grid-match" uk-grid>
            <div v-for="consultant in getconsultant.results" class="uk-width-1-4">
              <div class="uk-card uk-card-default uk-card-body card-consultant">
                <div class="consultant-panel-header">
                  <img class="uk-border-circle" :src="$root.url + '/assets/images/avatar/avatar.jpg'" alt="">
                </div>
                <div class="uk-margin-top uk-text-center consultant-panel-body">
                  <div class="consultant-name">
                    <a :href="$root.url + '/consultant/profile/' + consultant.consultant_id">
                      {{ consultant.consultant_fullname }}
                    </a>
                  </div>
                  <div class="consultant-location">{{ consultant.city_name }}</div>
                  <div class="consultant-rating" uk-tooltip="Excellent">
                    <span v-for="n in 5">
                      <i class="icon-rating" uk-icon="icon: star; ratio: 1"></i>
                    </span>
                  </div>
                </div>
              </div>
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
    'keywords'
  ],
  data() {
    return {
      forms: {
        keywords: this.keywords === '' ? '' : this.keywords,
        limit: 10,
        sorting: 'latest'
      },
      getconsultant: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: '',
          next_page_url: ''
        }
      },
      messages: {
        errorMessage: ''
      }
    }
  },
  methods: {
    showConsultant( p )
    {
      this.getconsultant.isLoading = true;
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&sorting=' + this.forms.sorting;
      let url = this.$root.url + '/search/consultant?page=' + this.getconsultant.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getconsultant.total = result.total;
        this.getconsultant.results = result.data;
        this.getconsultant.isLoading = false;
        this.getconsultant.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        this.getconsultant.isLoading = false;
        this.messages.errorMessage = err.response.statusText;
      });

      let urlbar = this.$root.url + '/search?page=' + this.getconsultant.paginate.current_page + '&' + param;
      window.history.pushState(null, null, urlbar);
    }
  },
  mounted() {
    this.showConsultant();
  }
}
</script>

<style lang="css" scoped>
</style>
