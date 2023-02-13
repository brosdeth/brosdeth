
<template>
    <div class="container-fluid  px-0">
        <div class="loading" v-show="isLoading">
            <img src="/icons/loading.gif" /> {{ $t('app.loading') }}
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 bg-transparent">
                    <div class="card-header border-0 bg-transparent px-0 py-0 mb_20  ">
                        <div class="d-flex justify-content-between">
                            <a class="page-title text-nowrap">
                                <h4 class="text-koulen">{{ $t('app.role') }}</h4>
                            </a>
                            <router-link v-if="$can('Role Create')" :to="'/role-create'" class="btn btn-success shadow-sm" data-toggle="modal">
                                {{ $t('app.add_new') }}
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body border-0 bg-white rounded_8">
                        <div class="col-sm-12 mb-3">
                            <div class="d-flex justify-content-between pt-3">
                                <div class="d-flex">
                                    <span class="pt-1">{{ $t('app.show') }}</span>
                                    <span class="px-2">
                                        <select @change="PageChange($event)"
                                            class="form-control form-control-sm shadow-sm">
                                            <option v-for="size in pageSizes" :key="size" :value="size">
                                                {{ size }}
                                            </option>
                                        </select>
                                    </span>
                                    <span class="pt-1">{{ $t('app.entries') }}</span>
                                </div>
                                <div>
                                    <input type="search"  class="rounded form-control form-control-sm" style="width: 200px" placeholder="Searching..." aria-label="Search">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <thead>
                                        <tr class="border-top border-bottom">
                                            <th>#</th>
                                            <th>{{ $t('Name') }}</th>
                                            <th>{{ $t('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody :class="roledata.length == 0 && isLoading == false ? 'no-data' : ''">
                                        <tr v-for="(role, index) in roledata" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ role.name }}</td>
                                            <td>{{ role.created_at }}</td>
                                            <td>
                                                <router-link v-if="$can('Role Edit')" :to="'/role-create/'+role.id" :title="$t('Edit')" @click="editRole(role.id)" class="btn btn-dark btn-sm s-btn-xs" :class="{'disabled': role.id == 1}"><i class="fa-solid fa-pen-to-square fa-sm"></i></router-link>
                                                <button v-if="$can('Role Delete')" type="button" :title="$t('Delete')" @click="funDelete(role.id)" class="btn btn-danger btn-sm s-btn-xs">
                                                    <i class="fa-solid fa-trash-can fa-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
            roledata: [],
            params:{
                searching:''
            }
        }
    },
    mounted() {
        this.getAllRole();
    },
    methods: {
        getAllRole() {
            this.isLoading = true;
            axios.get('api/roles',{
                params: this.params
            }).then((res) => {
                if (res.data == this.noPermission) {
                    this.$router.push({ name: 'noPermission' });
                }
                this.roledata = res.data;
                this.isLoading = false;
            }).catch((error) => {
                this.isLoading = false;
                console.log(error);
            });
        },
        funDelete(id) {
            this.$confirm({
                message: 'Are you sure?',
                button: {
                    no: 'No',
                    yes: 'Yes'
                },
                callback: confirm => {
                    if (confirm) {
                        axios.delete('api/roles/'+id).then((res) =>{
                            if(res.data.status == 1){
                                this.getAllRole();
                                this.$toast.success(res.data.message);
                            }else{
                                this.$toast.warning(res.data.message);
                            }
                            this.isLoading = false;
                        }).catch((error) => {
                            this.isLoading = false;
                            console.log(error);
                        });
                    }
                }
            });
        },
        Searching: function (e) {
            this.params.searching = e.target.value;
            this.getAllRole();
        },
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
