<template>
  <div>
    <div class="card-summary-dashboard">
      <div class="card-summary-heading">User</div>
      <div class="uk-grid-small uk-margin-small-top uk-grid-match" uk-grid>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ usersummary.client }}
            </div>
            <div class="card-summary-text">Klien</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ usersummary.consultant }}
            </div>
            <div class="card-summary-text">Konsultan</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ usersummary.admin }}
            </div>
            <div class="card-summary-text">Admin</div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-summary-heading">Konsultasi</div>
      <div class="uk-grid-small uk-margin-small-top uk-grid-match" uk-grid>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.total }}
            </div>
            <div class="card-summary-text">Total Konsultasi</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.waiting }}
            </div>
            <div class="card-summary-text">Menunggu Tanggapan</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.accept }}
            </div>
            <div class="card-summary-text">Diterima</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.decline }}
            </div>
            <div class="card-summary-text">Ditolak</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.cancel }}
            </div>
            <div class="card-summary-text">Dibatalkan</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ requestsummary.done }}
            </div>
            <div class="card-summary-text">Selesai</div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-summary-heading">Ulasan</div>
      <div class="uk-grid-small uk-margin-small-top uk-grid-match" uk-grid>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.total }}
            </div>
            <div class="card-summary-text">Total Ulasan</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.excellent }}
            </div>
            <div class="card-summary-text">Luar Biasa</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.good }}
            </div>
            <div class="card-summary-text">Berpengalaman</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.neutral }}
            </div>
            <div class="card-summary-text">Netral</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.poor }}
            </div>
            <div class="card-summary-text">Kurang berpengalaman</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-card uk-card-default uk-card-body uk-card-small card-summary-body">
            <div class="card-summary-value">
              {{ feedbacksummary.disappointed }}
            </div>
            <div class="card-summary-text">Tidak dapat dipercaya</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
        done: 0,
        total: 0
      },
      feedbacksummary: {
        excellent: 0,
        good: 0,
        neutral: 0,
        poor: 0,
        disappointed: 0,
        total: 0
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
          client: result.data.client,
          consultant: result.data.consultant,
          admin: result.data.admin
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
          waiting: result.data.waiting,
          accept: result.data.accept,
          cancel: result.data.cancel,
          decline: result.data.decline,
          done: result.data.done,
          total: result.data.total
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
          excellent: result.data.excellent,
          good: result.data.good,
          neutral: result.data.neutral,
          poor: result.data.poor,
          disappointed: result.data.disappointed,
          total: result.data.total
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
