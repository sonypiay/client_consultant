<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

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
            {{ getuser.city_name }}
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
