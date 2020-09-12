<template>
  <div id="app">
    <main>
      <div class="r-container" >
        <router-view />
      </div>
    </main>
    <r-snackbar />
  </div>
</template>
<script>
import RSnackbar from '@/components/main/snackbar.vue'
export default {
    components: {
    "r-snackbar": RSnackbar
  },
}
</script>
<style lang="scss">
html{
  overflow-y: auto !important;
}
</style>