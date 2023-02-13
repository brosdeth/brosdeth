<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"  aria-current="page"  v-for="item in items" :key="item.id">
                <router-link :to="{name:item.text}" :class="item.active?'active': ''">{{item.text}}</router-link>
            </li>
        </ol>
        <div class="row">
            <div class="col-sm-12 mb-3">
               
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <img src="" alt="">
            </div>
            <div class="col-sm-8">
                <input type="text" :value="user.name">
            </div>
        </div>
    </div>
</template>
<script>
  export default {
  
    data() {
        return {
            items: [
                {
                    text: 'Dashboard',
                    active: true,
                }
            ],
            user:'',
            warehouses:[],
            isWarehouse: '',
            checked: 'logout',
        }
    },
    created(){
        this.funUserInfo();
    },
    
    methods:{
        funUserInfo(){
            axios.get("api/checkUserValid")
            .then((res) => {
                this.user = res.data.user;
                this.warehouses = res.data.warehouses;
                this.isWarehouse = res.data.isWarehouse;
                console.log(res.data);
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
        }
    }
  }
</script>