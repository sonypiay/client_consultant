<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div id="modal-review" class="uk-modal-container" uk-modal>
      <a class="uk-modal-close uk-modal-close-outside"></a>
      <div class="uk-modal-dialog">
        <div class="uk-modal-body uk-height-large" uk-overflow-auto>
          <div class="card-panel-feedbacks">
            <div class="uk-card-title feedbacks-title">Ulasan</div>
            <div class="uk-margin feedbacks-filter">
              <div class="uk-grid-small uk-child-width-auto" uk-grid>
                <div>
                  <a @click="filter_feedback = 'all'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'all'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    Semua
                  </a>
                </div>
                <div>
                  <a @click="filter_feedback = 'disappointed'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'disappointed'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    <i class="fas fa-angry"></i> Tidak dapat dipercaya
                  </a>
                </div>
                <div>
                  <a @click="filter_feedback = 'poor'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'poor'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    <i class="fas fa-frown"></i> Kurang berpengalaman
                  </a>
                </div>
                <div>
                  <a @click="filter_feedback = 'neutral'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'neutral'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    <i class="fas fa-meh"></i> Netral
                  </a>
                </div>
                <div>
                  <a @click="filter_feedback = 'good'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'good'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    <i class="fas fa-smile"></i> Berpengalaman
                  </a>
                </div>
                <div>
                  <a @click="filter_feedback = 'excellent'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'excellent'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                    <i class="fas fa-smile-beam"></i> Luar Biasa
                  </a>
                </div>
              </div>
            </div>
            <div class="uk-margin">
              <div v-if="getfeedbacks.isLoading" class="uk-text-center">
                <span uk-spinner></span>
              </div>
              <div v-else>
                <div v-if="getfeedbacks.total === 0" class="uk-alert-warning" uk-alert>
                  Tidak ada ulasan dari klien
                </div>
                <div v-else class="uk-grid-divider uk-grid-small" uk-grid>
                  <div v-for="fd in getfeedbacks.results" class="uk-width-1-1">
                    <a class="uk-button uk-button-default uk-button-small gl-button-default">
                      <label v-if="fd.feedback === 'disappointed'">
                        <i class="fas fa-angry"></i> Tidak dapat dipercaya
                      </label>
                      <label v-else-if="fd.feedback === 'poor'">
                        <i class="fas fa-frown"></i> Kurang berpengalaman
                      </label>
                      <label v-else-if="fd.feedback === 'neutral'">
                        <i class="fas fa-meh"></i> Netral
                      </label>
                      <label v-else-if="fd.feedback === 'good'">
                        <i class="fas fa-smile"></i> Berpengalaman
                      </label>
                      <label v-else>
                        <i class="fas fa-smile-beam"></i> Luar biasa
                      </label>
                    </a>
                    <article class="uk-margin-small-top uk-article">
                      <p class="uk-article-meta uk-margin-remove-bottom">dari {{ fd.client_fullname }} tanggal {{ $root.formatDate( fd.created_at, 'DD MMMM YYYY' ) }}.</p>
                      <p class="uk-margin-remove-top">
                        {{ fd.review_description }}
                      </p>
                    </article>
                  </div>
                </div>
                <!-- pagination -->
                <ul class="uk-pagination uk-flex-center">
                  <li v-if="getfeedbacks.paginate.prev_page_url" @click="showFeedback( getfeedbacks.paginate.prev_page_url )">
                    <a>
                      <span uk-pagination-previous></span>
                    </a>
                  </li>
                  <li v-else class="uk-disabled">
                    <a>
                      <span uk-pagination-previous></span>
                    </a>
                  </li>
                  <li v-if="getfeedbacks.paginate.next_page_url" @click="showFeedback( getfeedbacks.paginate.next_page_url )">
                    <a>
                      <span uk-pagination-next></span>
                    </a>
                  </li>
                  <li v-else class="uk-disabled">
                    <a>
                      <span uk-pagination-next></span>
                    </a>
                  </li>
                </ul>
                <!-- pagination -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Profil Saya</div>
    </div>
    <div class="uk-container uk-margin-large-bottom container-editprofile">
      <div class="uk-width-2-5@xl uk-width-2-5@l uk-width-1-2@m uk-width-1-1@s uk-align-center uk-card uk-card-body uk-card-default card-panel">
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Nama Lengkap</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.consultant_fullname }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Alamat Email</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.consultant_email }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.consultant_phone_number }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Kota</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.city }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Alamat</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.consultant_address }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Rating Saya</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ rateIndex }} / 5
          </p>
        </div>
        <a :href="$root.url + '/consultant/edit_profile'" class="uk-width-1-1 uk-button uk-button-default gl-button-default"><span uk-icon="icon: pencil; ratio: 0.7"></span> Ubah Profil</a>
        <a @click="showFeedback()" class="uk-width-1-1 uk-button uk-button-default uk-margin-small gl-button-default"><span uk-icon="icon: star; ratio: 0.7"></span> Lihat Ulasan</a>
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
      filter_feedback: 'all',
      getfeedbacks: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: null,
          next_page_url: null
        }
      }
    }
  },
  methods: {
    showFeedback( p )
    {
      let param = 'feedback=' + this.filter_feedback;
      let url = this.$root.url + '/consultant/list_feedback?page=' + this.getfeedbacks.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      this.getfeedbacks.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getfeedbacks.isLoading = false;
        this.getfeedbacks.total = result.total;
        this.getfeedbacks.results = result.data;
        this.getfeedbacks.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        UIkit.modal('#modal-review').show();
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  computed: {
    rateIndex()
    {
      let rate = this.getuser.total_rate;
      let fd = this.getuser.total_feedback;
      let result = rate / fd;
      if( Number.isInteger(result) ) return result;
      else return result.toFixed(1);
    }
  }
}
</script>
