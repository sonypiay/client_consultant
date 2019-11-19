<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Daftar Klien</div>
    </div>
    <div class="uk-grid-small uk-margin-top uk-child-width-auto" uk-grid>
      <div>
        <select class="uk-select gl-input-default" v-model="forms.limit" @change="showClient()">
          <option value="6">6 baris</option>
          <option value="12">12 baris</option>
          <option value="24">24 baris</option>
          <option value="36">36 baris</option>
        </select>
      </div>
      <div>
        <select class="uk-select gl-input-default" v-model="forms.city" @change="showClient()">
          <option value="all">Semua kota</option>
          <option v-for="city in getcity" :value="city.city_id">{{ city.city_name }}</option>
        </select>
      </div>
      <div>
        <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Cari ID atau nama klien" @keyup.enter="showClient()" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'haslogin',
    'getuser',
    'getcity'
  ],
  data() {
    return {
      getclient: {
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
      forms: {
        keywords: '',
        limit: 6,
        city: 'all'
      }
    }
  },
  methods: {
    showClient( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&city=' + this.forms.city;
      let url = this.$root.url + '/consultant/client_list?page=' + this.getclient.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      this.getclient.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getclient.total = result.total;
        this.getclient.results = result.data;
        this.getclient.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getclient.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  mounted() {}
}
</script>

<style lang="css" scoped>
</style>
