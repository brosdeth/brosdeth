<template>
    <div class="user">
        <div class="container-fluid px-0">
            <div class="loading" v-show="isLoading">
                <img src="/icons/loading.gif" /> {{ $t('app.loading') }}
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card border-0 bg-transparent">
                        <div class="card-header border-0 bg-transparent px-0 py-0 mb_20  ">
                            <div class="d-flex justify-content-between">
                                <a class="page-title text-nowrap">
                                    <h4 class="text-koulen">{{ $t('app.user') }}</h4>
                                </a>
                                <button v-if="$can('User Create')" class="btn btn-success shadow-sm" @click="funshowForm">
                                    {{ $t('app.add_new') }}
                                </button>
                            </div>
                        </div>
                        <div class="card-body border-0 bg-white rounded_tx_8">
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
                                        <input type="search" @input="Searching" class="rounded form-control form-control-sm" style="width: 230px" placeholder="Searching..." aria-label="Search">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr class="border-top border-bottom">
                                                <th scope="col">{{ $t('app.row') }}</th>
                                                <th scope="col">{{ $t('app.user_name') }}</th>
                                                <th scope="col">{{ $t('app.contact_number') }}</th>
                                                <th scope="col">{{ $t('app.email') }}</th>
                                                <th scope="col">{{ $t('app.role') }}</th>
                                                <th scope="col">{{ $t('app.status') }}</th>
                                                <th scope="col">{{ $t('app.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody :class="userData.length < 0  && isLoading == false ? 'no-data' : ''">
                                            <tr v-for="(user, index) in userData.data" :key="index">
                                                <td>
                                                    {{ index + 1 }}
                                                </td>
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.contact_number }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.role }}</td>
                                               
                                                <td>
                                                    <span v-show="user.is_active == 1">{{ $t('app.active') }}</span>
                                                    <span v-show="user.is_active == 2">{{ $t('app.inactive') }}</span>
                                                </td>
                                                <td>
                                                    <button v-if="$can('User Edit')" title="Edit" @click="funshowForm(user.id)"  class="btn btn-dark btn-sm s-btn-xs">
                                                            <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                                    </button>
                                                    <button v-if="$can('User Delete')" type="button"
                                                        :title="$t('app.inactive')" @click="funDelete(user.id)"
                                                        class="btn btn-outline-danger btn-sm border-0">
                                                        <i class="fa fa-toggle-off" :title="$t('app.inactive')"
                                                            v-show="user.is_active == 1"></i>
                                                        <i class="fa fa-toggle-on" :title="$t('app.active')"
                                                            v-show="user.is_active == 2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white pb-3 rounded_bx_8">
                            <div class="d-flex justify-content-between">
                                <div class="pt-2">
                                    {{ $t('app.showing') }} <strong>{{ current_page }}</strong> {{ $t('app.to') }}
                                    <strong>{{ to_page }}</strong> {{ $t('app.of') }} <strong class="text-primary">{{
                                            total_page
                                    }}</strong> {{ $t('app.entries') }}
                                </div>
                                <div>
                                    <pagination :data="userData" @pagination-change-page="funGetDataUser" :limit="5"> </pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- create and edit  -->
            <form method="post" @submit.prevent="submitData()">
                <div class="modal fade" id="useForm" aria-hidden="true" role="dialog" data-backdrop="static">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t('app.user') }}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.user_name') }} <sup class="text-danger">( * )</sup></label>
                                    <input :placeholder="$t('app.user_name')" type="text" class="form-control"
                                        v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.contact_number') }} <sup class="text-danger">( *
                                            )</sup></label>
                                    <input :placeholder="$t('app.contact_number')" type="text"
                                        class="form-control number-only dis-space" v-model="form.contact_number"
                                        :class="{ 'is-invalid': form.errors.has('contact_number') }">
                                    <div v-if="form.errors.has('contact_number')"
                                        v-html="form.errors.get('contact_number')" />
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.email') }} <sup>( Optional )</sup></label>
                                    <input :placeholder="$t('app.email')" type="text" class="form-control"
                                        v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.password') }} <sup>( * )</sup></label>
                                    <input :placeholder="$t('app.password')" type="password" class="form-control"
                                        v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.confirm_password') }} <sup>( * )</sup></label>
                                    <input :placeholder="$t('app.confirm_password')" type="password"
                                        class="form-control" v-model="form.confirm_password"
                                        :class="{ 'is-invalid': form.errors.has('confirm_password') }">
                                    <div v-if="form.errors.has('confirm_password')"
                                        v-html="form.errors.get('confirm_password')" />
                                </div>


                                <div class="col-sm-12 form-group">
                                    <label for="">{{ $t('app.role') }} <sup>( * )</sup></label>
                                    <div class="form-group">
                                        <select :class="{ 'is-invalid': form.errors.has('roles') }" class="form-control" :placeholder="$t('app.role')" v-model="form.role_id">
                                            <option v-for="(role, index) in comRole" :key="'role'+index" :value="role.id">{{role.name}}</option>
                                        </select>
                                        <div v-if="form.errors.has('roles')" v-html="form.errors.get('roles')" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    <img src="/icons/b_drop.png" alt=""> {{ $t('app.close') }}
                                </button>
                                <button type="submit" :disabled="form.busy" class="btn btn-primary">
                                    <img :src="form.busy ? ' /icons/loading.gif ' : '/icons/save.png'" alt="">
                                    {{ $t('app.submit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            userData: {  },
            form: new Form({
                'id':'',
                'name':'',
                'email':'',
                'contact_number':'',
                'password':'',
                'confirm_password':'',
                'role_id':'',
            }),
            params:{
                'page': 1,
                'per_page': 50,
                'search_text':''
            },
            comRole: [],

        }
    },
    created() {
        this.funGetDataUser();
        this.funGetcomRole();

    },
    methods: {
        funGetcomRole() {
            axios.get('api/com-roles')
                .then((res) => {
                    this.comRole = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        funDelete(id) {
            this.isLoading = true;
            this.form.delete("api/users/" + id)
                .then((res) => {
                    this.isLoading = false;
                    this.$toast.success(res.data.message);
                    this.funGetDataUser();
                })
                .catch((error) => {
                    setTimeout(() => (this.isLoading = false), 200);
                    console.log(error);
                });
        },
        submitData() {
            this.isLoading = true;
            this.form.post("api/users").then((res) => {
                if(res.data.status == 1){
                    this.form.reset();
                    this.$toast.success(res.data.message);
                    $("#useForm").modal("hide");
                    this.funGetDataUser();
                }else{
                    this.$toast.warning(res.data.message);
                }

                this.isLoading = false;

            })
            .catch((error) => {
                this.isLoading = false;
                console.log(error);
            })
        },
        funshowForm(id) {
            axios.get('api/users/create',{
                params:{'id':id}
            }).then((res)=>{
                if(res.data.status ==1){
                    this.form.fill(res.data.data);
                }else{
                    this.form.reset();
                }
                $("#useForm").modal("show");
            });
        },
        funGetDataUser(page = 1) {
            this.isLoading = true;
            axios.get('api/users', {
                params: this.params
            }).then((res) => {
                if (res.data == this.noPermission) {
                    this.$router.push({ name: 'noPermission' });
                }
                this.userData = res.data;
                this.total_page = res.data.meta.total;
                this.current_page = res.data.meta.current_page;
                this.per_page = res.data.meta.per_page;
                this.from_page = res.data.meta.from;
                this.to_page = res.data.meta.to;
                this.isLoading = false;
            }).catch((error) => {
                console.log(error);
            });
        },
        PageChange: function (e) {
            this.params.per_page = e.target.value;
            this.funGetDataUser();
        },
        Searching(e){
            this.params.search_text = e.target.value;
            this.funGetDataUser();
        }
    }
}
</script>