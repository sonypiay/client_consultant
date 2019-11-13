<template>
  <div>
    <div id="modal-view-request" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="request-time">
            <span uk-icon="icon: clock; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.schedule_date, 'HH:mm' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: calendar; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.schedule_date, 'ddd, DD MMMM YYYY' ) }}
          </div>
        </div>
        <div class="uk-modal-body">
          <div class="uk-grid-small uk-margin" uk-grid>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Tanggal Permintaan</h6>
                <p class="uk-margin-remove-top">
                  {{ $root.formatDate( detailrequest.created_at, 'DD MMM YYYY' ) }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">ID Konsultasi</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.apt_id }}
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
                  {{ detailrequest.consultant_id }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Nama Konsultan</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant_fullname }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat Email</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant_email }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">No. Telepon</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant_phone_number }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Kota</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.city_name }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.consultant_address }}
                </p>
              </div>
            </div>
          </div>
          <div v-show="detailrequest.feedback" class="uk-panel uk-margin">
            <hr>
            <h6 class="uk-h6 uk-margin-remove-bottom">Ulasan Saya</h6>
            <p class="uk-margin-remove-top">
              {{ detailrequest.review_description }}
            </p>
          </div>
          <div v-show="detailrequest.feedback" class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Penilaian anda</h6>
            <div class="uk-margin-remove-top">
              <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Excellent" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.feedback === 'excellent'}" class="far fa-smile-beam"></i>
                    </a>
                    <div class="gl-review-text">Hebat</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Good" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.feedback === 'good'}" class="far fa-smile"></i>
                    </a>
                    <div class="gl-review-text">Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Neutral" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.feedback === 'neutral'}" class="far fa-meh"></i>
                    </a>
                    <div class="gl-review-text">Netral</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Poor" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.feedback === 'poor'}" class="far fa-frown"></i>
                    </a>
                    <div class="gl-review-text">Kurang Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Disappointed" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.feedback === 'disappointed'}" class="far fa-angry"></i>
                    </a>
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
