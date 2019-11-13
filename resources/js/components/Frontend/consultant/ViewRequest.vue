<template>
  <div>
    <div id="modal-view-request" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="request-time">
            <span uk-icon="icon: clock; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.request.schedule_date, 'HH:mm' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: calendar; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.request.schedule_date, 'ddd, DD MMMM YYYY' ) }}
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
          </div>
          <hr>
          <div class="uk-grid-small uk-margin" uk-grid>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">ID Klien</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.client_id }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Nama Klien</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.client_fullname }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat Email</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.client_email }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">No. Telepon</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.client_phone_number }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Kota</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.city_name }}
                </p>
              </div>
            </div>
            <div class="uk-width-1-2">
              <div class="uk-panel uk-margin">
                <h6 class="uk-h6 uk-margin-remove-bottom">Alamat</h6>
                <p class="uk-margin-remove-top">
                  {{ detailrequest.client.client_address }}
                </p>
              </div>
            </div>
          </div>
          <div v-show="detailrequest.feedback" class="uk-panel uk-margin">
            <hr>
            <h6 class="uk-h6 uk-margin-remove-bottom">Ulasan Klien</h6>
            <p class="uk-margin-remove-top">
              {{ detailrequest.request.review_description }}
            </p>
          </div>
          <div v-show="detailrequest.request.feedback" class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Rate</h6>
            <div class="uk-margin-remove-top">
              <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Excellent" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'excellent'}" class="far fa-smile-beam"></i>
                    </a>
                    <div class="gl-review-text">Hebat</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Good" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'good'}" class="far fa-smile"></i>
                    </a>
                    <div class="gl-review-text">Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Neutral" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'neutral'}" class="far fa-meh"></i>
                    </a>
                    <div class="gl-review-text">Netral</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Poor" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'poor'}" class="far fa-frown"></i>
                    </a>
                    <div class="gl-review-text">Kurang Berpengalaman</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a uk-tooltip="Disappointed" class="gl-icon-review">
                      <i :class="{'fas': detailrequest.request.feedback === 'disappointed'}" class="far fa-angry"></i>
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
