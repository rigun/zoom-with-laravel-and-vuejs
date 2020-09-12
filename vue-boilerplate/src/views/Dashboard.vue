<template>
  <div id="Dashboard">
    <r-sidebar v-if="$store.getters['getisLoggedIn']" />
    <r-navbar v-if="$store.getters['getisLoggedIn']" />
    <main :class="$store.getters['getisLoggedIn'] ? !collapse ? 'r-content' : 'r-content r-move-container' : 'r-content-fullwidth'">
     <div class="r-container" >
        <router-view />
      </div>
    </main>
  </div>
</template>
<style lang="scss">
.r-content-fullwidth {
  width: 100vw;
  min-height: 100vh;
}
.r-move-container{
  width: 100% !important;
  padding-left: 0px !important;
}
.r-content {
  transition: all 0.2s ease;
  width: calc(100% - 230px);
  height: calc(100% - 125px);
  min-height: calc(100vh - 125px);
  background: #F6F6F6;
  padding-left: 230px;
  padding-top: 80px;
  padding-bottom: 45px;
}
</style>
<script>
function loadMain(view) {
  return () =>
    import(
      /* webpackChunkName: "main-[request]" */ `@/components/main/${view}.vue`
    );
}
export default {
  components: {
    "r-navbar": loadMain("navbar"),
    "r-sidebar": loadMain("sidebar"),
  },
  computed:{
    collapse(){
      return this.$store.getters['getCollapse']
    }
  }
};
</script>