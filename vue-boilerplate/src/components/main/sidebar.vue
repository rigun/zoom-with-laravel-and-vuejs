<template>
    <div id="Sidebar" :class="!collapse ? '' : 'r-hide-sidebar'">
       <div class="r-sidebarContent">
           <div class="r-sidebarHeader r-center-flex" :class="!collapse ? '' : 'r-hide-sidebarHeader'">
               <div class="r-initialName">{{inisial}}</div>
                <div class="r-userDetail">
                    <p class="r-name r-ellipsis">{{user.name}}</p>
                    <p class="r-role r-gray-text r-ellipsis">{{user.role}}</p>
                </div>
                <div class="r-humburger" @click="setCollapse()">
                    <img :src="require('@/assets/icons/sidebar/menu.svg')">
                </div>
           </div>
            <div class="r-sidebarItems">
               <div class="r-sidebarItem" :class="accordion == sc.name ? 'r-show-accordion r-accordion-isActive' : 'r-hide-accordion'"  @click.prevent="goto(sc.link, sc.name, sc.children.length > 0)" v-for="(sc, index) in availableComponent" :key="`sidebar-${index}`">
                    <div class="r-sidebarButton r-center-flex" :class="activeTab == sc.name ? 'r-siebarItem-isActive': ''">
                        <img class="iconSidebar" :src="require(`@/assets/icons/sidebar/${activeTab == sc.name ? 'active': 'inactive'}/${sc.icon}.svg`)">
                        <p class="r-ellipsis" style="max-width: 174px">{{sc.name}}</p>
                        <img :src="require(`@/assets/icons/others/accordion.svg`)" style="margin-left: auto;" :style="accordion == sc.name ? '' : 'transform: rotate(180deg)'" v-if="sc.children.length > 0">
                    </div>
                    <div class="r-sidebarButton r-center-flex" v-for="(child, index) in sc.children" :key="`sidebar-child-${index}`" @click.prevent="goto(child.link, child.name, false)">
                        <div class="r-bullet-separator"></div>
                        <p class="r-ellipsis" style="max-width: 174px">{{child.name}}</p>
                    </div>
               </div>
           </div>
           <div class="r-sidebarFooter r-center-flex">
               <div class="r-sidebarItem">
                    <div class="r-sidebarButton r-center-flex" @click.prevent="$router.push({name: 'logoutComponent'})">
                        <img :src="require('@/assets/icons/sidebar/logout.svg')">
                        <p>Logout</p>
                    </div>
               </div>
           </div>
       </div>
    </div>
</template>
<script>
export default {
    computed:{
        activeTab(){
            return this.$store.getters['getActiveTab']
        },
        inisial() {
            if (this.user.name.length < 1) {
                return "YP";
            } else {
                var temp = this.user.name.split(" ");
                var inisial = "";
                for (var i = 0; i < temp.length; i++){
                    if(i > 1){
                        break
                    }
                    inisial += temp[i][0];
                }
                return inisial;
            }
        },
        collapse(){
            return this.$store.getters['getCollapse']
        }
        
    },
    mounted() {
        this.getMyIdentity();
        this.setSideBar();
    },
    data: () => ({
        accordion: null,
        availableComponent: [],
        sidebarComponent: [
            {
                name: 'Dashboard',
                link: {name: 'Dashboard'},
                icon: 'Dashboard',
                role: [],
                children: []
            },
        ],
        user: {
            name: "",
            role: ""
        },
         request:{
            page: 1,
            show: 16,
            sortBy: "begin_date",
            sorting: "DESC",
            search: null
        },
    }),
    methods:{
        setCollapse(){
         this.$store.commit('SET_COLLAPSE')
        },
        async setSideBar(){
            await this.$store.restored
            this.availableComponent = this.sidebarComponent.filter(row => {
                if(row.role.length > 0){
                    return row.role.filter(role => {return role == this.$store.getters['auth/getRole']}).length > 0
                }else{
                    return true
                }
            })
        },
        goto(link,name, isParent = false){
            if(isParent){
                if(this.accordion === name){
                    this.accordion = null
                }else{
                    this.accordion = name
                }
            }else{
                this.accordion = name
                this.$store.commit('SET_ACTIVETAB', name)
                this.$store.commit('RESET_COLLAPSE')
                this.$router.push(link)
            }
        },
        async getMyIdentity() {
            // var response = this.$store.getters["auth/getResponse"];
            await this.$store.restored
            this.user = this.$store.getters["auth/getUser"];
            if (this.$store.getters["auth/getAppkey"] != localStorage.getItem("appkey")) {
                this.$router.push({ name: "logoutComponent" });
            }
            // this.user = this.$store.getters["auth/getUser"];
        },
    }
}
</script>
<style lang="scss">
.r-initialName {
    background: linear-gradient(225deg, #CDD2FD 3.26%, #6979F8 100%);
    border-radius: 5.625px;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 24px;
    justify-content: center;
    text-align: center;
    color: #ffffff;
    height: 36px;
    min-width: 36px;
    max-width: 36px;
    display: flex;
    align-items: center;
    margin-right: 12px;
}
.r-humburger{
    opacity: 0;
}
.r-show-accordion{
    max-height: auto;
}
.r-hide-accordion{
    max-height: 40px;
}
.r-userDetail {
    cursor: default;
    .r-name {
        font-style: normal;
        font-weight: 600;
        font-size: 14px;
        line-height: 14px;
        margin-bottom: 4px;
        max-width: 122px;
    }
    .r-role {
        max-width: 122px;
        font-style: normal;
        font-weight: 500;
        font-size: 10px;
        line-height: 14px;
    }
}

#Sidebar{
    z-index: 100;
    color: white;
    position: fixed;
    bottom: 0px;
    top: 0px;
    left: 0px;
    overflow-x: hidden;
    transition: all 0.2s ease;
    background: var(--primary-color);
    .r-sidebarContent{
        position: relative;
        height: 100%;
        .r-sidebarHeader{
            min-height: 78px;
            padding: 0px 20px;
            border-bottom: 1px solid #082260;
            transition: all 0.2s ease;
        }
        .r-siebarItem-isActive{
            background-color: #071853 !important;
        }
        .r-accordion-isActive{
            background-color: #07175373 !important;
        }
        .r-sidebarItems{
            position: relative;
            border-bottom: 1px solid #082260;
            max-height: calc(100vh - 125px);
            overflow-y: scroll;
            overflow-x: hidden;
            margin-right: -16px;
            min-width: 230px;
        }
        .r-sidebarFooter{
            position: absolute;
            bottom: 0px;
            left: 0px;
            right: 0px;
            border-top: 1px solid #082260;
            .r-sidebarItem{
                width: 100%;
            }
        }
        .r-sidebarItem{
            cursor: pointer;
            overflow: hidden;
            min-width: 230px;
            .r-sidebarButton{
                min-height: 40px;
                max-height: 40px;
                padding: 0px 10px;
                .iconSidebar{
                    width: 20px;
                }
                img{
                    margin-right: 10px;
                }
            }
            &:hover{
                background-color: #071853 !important;
            }
        }
    }
}
</style>