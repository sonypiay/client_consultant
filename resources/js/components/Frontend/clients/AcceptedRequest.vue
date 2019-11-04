<template>
  <div>
    <div class="uk-margin-large-top container-request-list">
      <div v-if="getrequest.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-if="getrequest.total === 0" class="no-request-list">
          <div class="uk-margin-remove">
            <div class="uk-margin-remove">
              <span class="far fa-frown"></span>
            </div>
            You have no accepted request.
          </div>
          <a class="uk-button uk-button-primary gl-button-primary" :href="$root.url + '/search'">Find consultant</a>
        </div>
        <div v-else class="uk-grid-medium" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-margin-small">
                <div class="request-time">{{ $root.formatDate( req.schedule_date, 'HH:mm' ) }}</div>
                <div class="request-date">{{ $root.formatDate( req.schedule_date, 'DD MMMM YYYY' ) }}</div>
              </div>
              <div class="uk-margin-small">
                <div class="request-pic">
                  {{ req.consultant_fullname }}
                </div>
              </div>
              <div class="uk-margin-small">
                <div class="uk-grid-small uk-child-width-auto" uk-grid>
                  <div>
                    <a uk-icon="pencil" class="request-icon"></a>
                  </div>
                  <div>
                    <a uk-icon="forward" class="request-icon"></a>
                  </div>
                  <div>
                    <a uk-icon="trash" class="request-icon"></a>
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
      let url = this.$root.url + '/client/request_list/accept?page=' + this.getrequest.paginate.current_page;
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
