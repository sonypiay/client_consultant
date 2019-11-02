<template>
  <div>
    <header class="header">
      <div class="uk-container">
        <nav class="uk-navbar navbar" uk-navbar>
          <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo">Logo</a>
          </div>
          <div class="uk-navbar-right">
            <ul class="uk-navbar-nav nav">
              <li><a :href="$root.url + '/client/signup'">Become a Client</a></li>
              <li><a :href="$root.url + '/consultant/signup'">Become a Consultant</a></li>
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
