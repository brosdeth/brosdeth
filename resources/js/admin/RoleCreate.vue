
<template>
    <div class="container-fluid  px-0">
        <!-- <loading :loading="isLoading"></loading> -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 bg-transparent">
                    <div class="card-header border-0 bg-transparent px-0 py-0 mb_20  ">
                        <div class="d-flex justify-content-between">
                            <a class="page-title text-nowrap">
                                <h4 class="text-koulen">{{ $t('Role') }}</h4>
                            </a>
                        </div>
                    </div>
                    <div class="card-body border-0 bg-white rounded_8">
                        <label class="w-100">
                            <label for="">Role Name</label>
                            <input class="form-control" type="text" name="name" v-model="form.name">
                        </label>
                        <br>
                        <br>
                        <br>
                        <div class="border position-relative rounded mx-2">
                            <div class="position-absolute  rounded px-1 bg-white" style="top: -20px; left: 25px; border: #aaa solid 1px ;">
                                <div @click="checkAll()">
                                     <label class="text-nowrap" for="" style=" line-height: 15px">
                                        <input type="checkbox" v-model="form.check_all"/> 
                                        <span class="ml-2">Select all permission</span>
                                    </label>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="position-relative rounded d-flex flex-wrap px-3">
                                <div class=" rounded position-relative rounded mb-5 col-sm-3"
                                    v-for="(per, i) in form.permission" :key="'Permission'+i">
                                    <div class=" h-100 pt-3 pb-2 rounded px-2" style="border: #ececec solid 1px; box-shadow: 0px 2px 12px #ececec;">
                                        <div class="position-absolute rounded px-1 bg-white" style="top: -20px; left: 20px; border: #DEDBDB solid 1px;box-shadow: rgb(244 244 244) 0px 2px 6px;">
                                            <label class="text-nowrap" for="" style=" line-height: 15px" @click="checkGroup(i)">
                                                <input type="checkbox" v-model="per.check_group"/> <span class="ml-2">{{per.group_prefix}}</span> 
                                            </label>
                                        </div>
                                        <div v-for="sub in per.children" :key="sub.id">
                                            <label :for="'permission_' + sub.name" style=" line-height: 15px">
                                                <input :id="'permission_' + sub.name" type="checkbox" v-model="sub.allow" :value="sub.name">
                                                <span class="ml-2">{{ sub.name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end py-3 px-2">
                            <button type="button" class="btn btn-dark btn-sm mr-2">
                                <i class="fa-solid fa-arrow-left"></i> {{ $t('Close') }}
                            </button>
                            <button type="submit" :disabled="form.busy" class="btn btn-success btn-sm" @click="submitData"> 
                                <i class="fa-solid fa-floppy-disk"></i>
                                {{ $t('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
    data() {
        return {
            form: new Form({
                id: "",
                name: "",
                check_all: "",
                permission: "",
            }),
        }
    },
    mounted() {
        this.getShow();
    },
    methods: {
        getShow() {
            this.isLoading = true;
            axios.get("api/get-permission/"+this.$route.params.id).then((res) => {
                this.form.fill(res.data);
                this.isLoading = false;
            }).catch((error) => {
                this.isLoading = false;
                console.log(error);
            });
        },
        checkAll: function () {
            this.form.check_all = !this.form.check_all;
            if (this.form.check_all) {
                for (let index = 0; index < this.form.permission.length; index++) {
                    this.form.permission[index].check_group = true;
                    for (let j = 0; j < this.form.permission[index].children.length; j++) {
                        this.form.permission[index].children[j].allow = true;
                    }
                }
            }else{
                for (let index = 0; index < this.form.permission.length; index++) {
                    this.form.permission[index].check_group = false;
                    for (let j = 0; j < this.form.permission[index].children.length; j++) {
                        this.form.permission[index].children[j].allow = false;
                    }
                }
            }
        },
        checkGroup: function (index){
            this.form.permission[index].check_group = !this.form.permission[index].check_group;
            if(this.form.permission[index].check_group){
                for (let j = 0; j < this.form.permission[index].children.length; j++) {
                    this.form.permission[index].children[j].allow = true;
                }
            }else{
                for (let j = 0; j < this.form.permission[index].children.length; j++) {
                    this.form.permission[index].children[j].allow = false;
                }
            }
        },
        submitData(){
            this.isLoading = true;
            this.form.post("api/roles").then((res) =>{
                if(res.data.status == 1){
                    this.getShow();
                    this.$router.push('/UserRole')
                    this.$toast.success(res.data.message);
                }else{
                    this.$toast.warning(res.data.message);
                }
                
                this.isLoading = false;
            });
        }
    }
}
</script>
<style scoped>
.user-role {
    background: #f5f5ff;
    padding: 15px;
    margin-bottom: 15px;
}

table.user-roles tr td {
    vertical-align: top !important;
    background: rgb(233, 236, 239)
}

label {
    vertical-align: top;
    padding-top: 7px;
    padding-left: 5px;
}
</style>
