<template>
  <div id="Navbar" :class="!collapse ? '' : 'r-move-navbar'">
    <div :class="!collapse ? '' : 'r-hide-sidebar'" @click="setCollapse()"></div>
    <div class="r-humburger-clone" @click="setCollapse()" :class="!collapse ? '' : 'r-move-burger'">
        <img :src="require('@/assets/icons/sidebar/menu.svg')">
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    setCollapse(){
      this.$store.commit('SET_COLLAPSE')
    }
  },
  computed: {
    collapse(){
      return this.$store.getters['getCollapse']
    } 
  }
};
</script>
<style lang="scss">
.r-move-navbar{
  width: 100% !important;
}
.r-move-burger{
  left: 0px !important;
}
#Navbar{
  transition: all 0.2s ease;
  background: white;
  width: calc(100% - 230px);
  position: fixed;
  right: 0px;
  top: 0px;
  z-index: 1000;
  height: 80px;
  box-shadow: inset 0px -1px 0px #E6E6E6;
  .r-backarrow{
    background: #0000000d;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .r-profile-space{
    padding: 0px 24px;
  }
}
.r-humburger-clone{
  position: absolute;
  z-index: 10000000;
  background: #16347a;
  height: 30px;
  display: flex;
  align-items: center;
  width: 30px;
  justify-content: center;
  border-radius: 0px 8px 8px 0px;
  top: 22px;
  cursor: pointer;
  transition: all 0.2s ease;
}
</style>
