<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Profil Saya</div>
    </div>
    <div class="uk-container uk-margin-large-bottom container-editprofile">
      <div class="uk-width-2-5 uk-align-center uk-card uk-card-body uk-card-default card-panel">
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Nama Lengkap</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.client_fullname }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Alamat Email</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.client_email }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.client_phone_number }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">ID NPWP</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            {{ getuser.client_npwp }}
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
            {{ getuser.client_address }}
          </p>
        </div>
        <div class="uk-panel uk-margin">
          <h4 class="uk-h4 uk-margin-remove-bottom">Tipe</h4>
          <p class="uk-text-muted uk-margin-remove-top">
            <span v-if="getuser.client_type === 'pt'">Perusahaan</span>
            <span v-else>Perorangan</span>
          </p>
        </div>
        <a class="uk-width-1-1 uk-button uk-button-default gl-button-default"><span uk-icon="icon: pencil; ratio: 0.7"></span> Ubah Profil</a>
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

    }
  }
}
</script>
