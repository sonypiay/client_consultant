<template>
  <div>
    <div class="card-summary-dashboard">
      Dashboard
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'getuser'
  ],
  data() {
    return {
      usersummary: {
        client: 0,
        consultant: 0,
        admin: 0
      },
      requestsummary: {
        waiting: 0,
        accept: 0,
        cancel: 0,
        decline: 0,
        done: 0
      },
      feedbacksummary: {
        excellent: 0,
        good: 0,
        neutral: 0,
        poor: 0,
        disappointed: 0
      }
    }
  },
  methods: {
    getUserSummary()
    {
      axios({
        method: 'get',
        url: this.$root.url + '/cp/dashboard/user_summary'
      }).then( res => {
        let result = res.data;
        this.usersummary = {
          client: result.client,
          consultant: result.consultant,
          admin: result.admin
        };
      }).catch( err => {});
    },
    getRequestSummary()
    {
      axios({
        method: 'get',
        url: this.$root.url + '/cp/dashboard/request_summary'
      }).then( res => {
        let result = res.data;
        this.requestsummary = {
          waiting: result.waiting,
          accept: result.accept,
          cancel: result.cancel,
          decline: result.decline,
          done: result.done
        };
      }).catch( err => {});
    },
    getFeedbackSummary()
    {
      axios({
        method: 'get',
        url: this.$root.url + '/cp/dashboard/feedback_summary'
      }).then( res => {
        let result = res.data;
        this.feedbacksummary = {
          excellent: result.excellent,
          good: result.good,
          neutral: result.neutral,
          poor: result.poor,
          disappointed: result.disappointed
        };
      }).catch( err => {});
    }
  },
  mounted() {
    this.getUserSummary();
    this.getRequestSummary();
    this.getFeedbackSummary();
  }
}
</script>
