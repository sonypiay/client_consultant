<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="banner-index_editprofile"></div>
    <div class="uk-container uk-margin-large-bottom">
      <div class="container-editprofile">
        <div class="uk-grid-medium" uk-grid>
          <div class="uk-width-1-4">
            <nav class="uk-card uk-card-body uk-card-default card-panel">
              <div class="uk-text-center uk-margin-bottom sidebar-profile">
                <div class="profile-name">{{ getuser.client_fullname }}</div>
                <div class="profile-join">
                  Member since
                  {{ $root.formatDate( getuser.created_at, 'DD MMM, YYYY' ) }}
                </div>
              </div>
              <ul class="uk-nav uk-nav-default nav-profile">
                <li>
                  <a :class="{'active': navpage === 'account_information'}" @click="navpage = 'account_information'">
                    Edit Profile
                  </a>
                </li>
                <li>
                  <a :class="{'active': navpage === 'edit_password'}" @click="navpage = 'edit_password'">
                    Change Password
                  </a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="uk-width-expand">
            <account-information v-show="navpage === 'account_information'"
            :getuser="getuser"
            :getcity="getcity"
            />
            <edit-password v-show="navpage === 'edit_password'" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AccountInformation from './AccountInformation.vue';
import EditPassword from './EditPassword.vue';

export default {
  props: [
    'haslogin',
    'getuser',
    'getcity'
  ],
  components: {
    'account-information': AccountInformation,
    'edit-password': EditPassword
  },
  data() {
    return {
      navpage: 'account_information'
    }
  }
}
</script>
