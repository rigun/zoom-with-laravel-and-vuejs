<template>
  <div class="meeting">
    <div class="container-zoom">
      <ZoomFrame :meetingPwd="meetingPwd" :meetingId="meetingId"/>
    </div>
  </div>
</template>
<script>
// eslint-disable-next-line no-unused-vars
import ZoomFrame from "@/components/dashboard/ZoomFrame";

export default {
    components: {
        ZoomFrame,
    },
    computed:{
        meetingId(){
            return this.$route.params.id
        },
        meetingPwd(){
            return this.$route.params.pwd
        }
    }
}
</script>