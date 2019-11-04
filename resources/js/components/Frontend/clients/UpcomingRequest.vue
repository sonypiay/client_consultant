<template>
  <div class="uk-margin-top">
    <h3 class=" upcoming-request_title">Upcoming Requests</h3>
    <div class="upcoming-request_leadtext">
      Here are the requests you created.
    </div>
    <div class="uk-margin-top container-request-list">
      <div v-if="getrequest.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-if="getrequest.total === 0" class="no-request-list">
          <p class="uk-margin-remove">
            You don't have any request
          </p>
          <a class="uk-button uk-button-primary gl-button-primary" :href="$root.url + '/search'">Find consultant</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      getrequest: {
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
    showUpcomingRequest( p )
    {
      this.getrequest.isLoading = true;
      let url = this.$root.url + '/client/request_list?page=' + this.getrequest.paginate.current_page;
      if( p !== undefined ) url = p;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getrequest.total = result.total;
        this.getrequest.results = result.data;
        this.getrequest.isLoading = false;
        this.getrequest.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        this.getrequest.isLoading = false;
        this.messages.errorMessage = err.response.statusText;
      });
    }
  },
  mounted() {
    this.showUpcomingRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
