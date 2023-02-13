<template>
    <div class="main" :class="!$route.meta.hideNavbar?'admin_menu':'staff_menu'">
        <desktop-site v-if="!$route.meta.hideNavbar"></desktop-site>
        <staff-desktop-site v-else></staff-desktop-site>
        <div class="d_content overflow-auto">
            <transition name="scale" mode="out-in">
                <keep-alive>
                    <router-view v-if="$route.meta.keepAlive"></router-view>
                </keep-alive>
            </transition>
            <transition name="scale" mode="out-in">
                <router-view v-if="!$route.meta.keepAlive"></router-view>
            </transition>
        </div>
    </div>
</template>
<script>
import DesktopSite from './layouts/DesktopSite.vue';
import StaffDesktopSite from './layouts/StaffDesktopSite.vue';
  export default {
    components:{
        DesktopSite,
        StaffDesktopSite,
    },
    data() {
        return {
            user:'',
            active:'dashboard',
            hidden:false,
            status:false // True = under maintenent, false = Ready
        }
    },
    created(){
        this.funUserInfo();
        if(localStorage.active){
            this.active = localStorage.active;
        }
        
    },
    watch: {
        '$route'() {
            this.funUserInfo();
        },
    },
    methods:{
        funUserInfo(){
            axios.get("api/checkUserValid")
            .then((res) => {
                this.user = res.data.user;
            })
            .catch((error) =>{
                console.log(error);
            });
        },
        funLogout(){
            axios.post('/logout').then(response => {
                this.$router.push("/");
                this.user = "";
                localStorage.setItem('checked', 'logout');
            })
            .catch(error => {
                location.reload();
            });
        },
        checkActiveMenu(e){
            this.active =e;
            localStorage.active = e;
        },
        hideLogo(){
            this.hidden = this.hidden===false? true : false;
        }
    }
  }
</script>

<style lang="scss">

</style>

