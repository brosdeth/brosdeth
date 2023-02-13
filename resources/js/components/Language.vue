<template>
    <div class="language">
        <ul class="list-group list-group-horizontal lang" >
            <li :class="lang === 'km' ? 'active':''"><span @click="changeLang('km')" class="px-2 border-right ">ខ្មែរ</span></li>
            <li :class="lang !== 'km' ? 'active':''"><span @click="changeLang('en')" class="px-2">English</span></li>
        </ul>
    </div>

    
</template>
<script>
    export default {
        data() {
            return {
                lang: 'km'
            }
        },
        created() {
            if(localStorage.lang) {
                this.lang = localStorage.lang;
            }
            this.changeLang(this.lang);
        },
        methods:{
            changeLang(e) {
                this.$i18n.locale = e;
                localStorage.lang = e;
                this.lang = e;
            },
    }
}
</script>
<style lang="scss">
    .language{
        span{
            cursor: pointer;
        }
    }
</style>
