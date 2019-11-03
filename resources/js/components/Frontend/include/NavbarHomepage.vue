<template>
  <div>
    <header class="headerhomepage">
      <div class="uk-container">
        <nav class="uk-navbar navbarhomepage" uk-navbar>
          <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo">Logo</a>
          </div>
          <div class="uk-navbar-right">
            <ul class="uk-navbar-nav navhomepage">
              <li><a :href="$root.url + '/client/signin'">Log in Client</a></li>
              <li><a :href="$root.url + '/consultant/signin'">Log in Consultant</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  </div>
</template>

<script>
export default {
}
</script>
