<template>
  <div>
    <div id="modal-view-request-client" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="request-time">
            <span uk-icon="icon: clock; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.request.schedule_date, 'HH:mm' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: calendar; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.request.schedule_date, 'ddd, DD MMMM YYYY' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: info; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ detailrequest.request.topic_name }}
          </div>
        </div>
        <div class="uk-modal-body">
          <div class="uk-grid-small uk-margin" uk-grid>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Tanggal Permintaan</h6>
                <p class="uk-margin-remove-top">
                  {{ $root.formatDate( detailrequest.request.created_at, 'DD MMM YYYY' ) }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">ID Konsultasi</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.request.apt_id }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Lokasi</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.request.location }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Catatan</h6>
                <p class="uk-margin-remove-top uk-text-justify">
                  {{ detailrequest.request.notes }}
                </p>
              </div>
            </div>
          </div>
          <hr>
          <div class="uk-grid-small uk-margin" uk-grid>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">ID Konsultan</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.consultant_id }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Nama Konsultan</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.consultant_fullname }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat Email</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.consultant_email }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">No. Telepon</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.consultant_phone_number }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Kota</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.city_name }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant.consultant_address }}
                </p>
              </div>
            </div>
          </div>
          <div v-show="detailrequest.request.feedback" class="uk-panel uk-margin">
            <hr>
            <h6 class="uk-h6 uk-margin-remove-bottom">Ulasan Saya</h6>
            <p class="uk-margin-remove-top">
              {{ detailrequest.request.review_description }}
            </p>
          </div>
          <div v-show="detailrequest.request.feedback" class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Penilaian anda</h6>
            <div class="uk-margin-remove-top">
              <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <span class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'excellent'}" class="far fa-smile-beam"></i>
                    </span>
                    <div class="gl-review-text">Hebat</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <span class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'good'}" class="far fa-smile"></i>
                    </span>
                    <div class="gl-review-text">Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <span class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'neutral'}" class="far fa-meh"></i>
                    </span>
                    <div class="gl-review-text">Netral</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <span class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'poor'}" class="far fa-frown"></i>
                    </span>
                    <div class="gl-review-text">Kurang Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <span class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'disappointed'}" class="far fa-angry"></i>
                    </span>
                    <div class="gl-review-text">Tidak dapat dipercaya</div>
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
  props: ['detailrequest']
}
</script>

<style lang="css" scoped>
</style>
