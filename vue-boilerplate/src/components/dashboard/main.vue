<template>
    <div id="Dashboard">
        <div class="r-center-flex">
            <div class="r-inputField" style="margin-right: 10px;">
                <div class="r-hasIcon-right r-md-size">
                    <input class="r-input" type="email" v-model="email" placeholder="input your email">
                </div>
            </div>
            <button class="r-button r-primary-btn r-md-size" @click.prevent="sendData()">
                <span v-if="!loading">Create Meeting</span>
                <img :src="require('@/assets/loadingDot.svg')" alt style="width: 50px;" v-else />
            </button>
        </div>
        <div class="r-table r-box">
            <table>
                <tr class="hideThead">
                    <td class="r-table-firstColumn">Email</td>
                    <td >ID</td>
                    <td >Password</td>
                    <td class="r-table-actionColumn">Action</td>
                </tr>
                <tr v-for="(item, index) in items" :key="`${index}-data`">
                    <td data-label="Email">{{item.email}}</td>
                    <td data-label="ID">{{item.meetingId}}</td>
                    <td data-label="Password">{{item.meetingPwd}}</td>
                    <td data-label="Action">
                        <button class="r-button r-success-btn r-md-size" @click.prevent="$router.push({name: 'Zoom Data', params: {id: item.meetingId, pwd: item.meetingPwd,zoomdata: item.id}})">
                            Join Meeting
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>

import {regex} from '@/components/helper/regex'

export default {
    async mounted(){
        await this.$store.restored
        this.getData()
        localStorage.setItem('name',this.$store.getters['auth/getName'])
    },
    data:()=>({
        email: null,
        loading: false,
        items: []
    }),
    methods:{
           async sendData(){
            if(RegExp(regex.email).test(this.email)){
                this.loading = true
                await this.$store.dispatch('zoom/submit',{url: '/meetings',data: {email: this.email}})
                this.getResponse()
            }else{
                this.$store.dispatch("showSnackbar", {
                    type: "error",
                    text: "Mohon periksa kembali"
                })
            }
        },
        getResponse(){
            this.loading = false
            var status = this.$store.getters["zoom/getStatus"]
            var msg = this.$store.getters["zoom/getMsg"]
            if(status === 1){
                this.$store.dispatch("showSnackbar", {
                    type: "success",
                    text: msg
                })
                this.resetData()
                this.getData()
            }else{
                this.$store.dispatch("showSnackbar", {
                    type: "error",
                    text: msg
                })
            }
        },
        resetData(){
            this.email = null
        },
        async getData(){
            this.items = this.$store.getters[`zoom/getItems`]('meetings')
            await this.$store.dispatch("zoom/fetchData", {url: '/zoomdata',type: 'meetings'})
            var status = this.$store.getters["zoom/getStatus"]
            var msg = this.$store.getters["zoom/getMsg"]
            if(status === 1){
                this.items = this.$store.getters[`zoom/getItems`]('meetings')
            }else{
                this.$store.dispatch("showSnackbar", {
                    type: "error",
                    text: msg
                })
            }
        }
    }
}
</script>