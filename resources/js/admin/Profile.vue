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
                                 <h4>My Profile</h4>
                            </div>
                        </div>
                        <div class="card-body border-0 bg-white rounded_tx_8">
                            <div class=" card-body bg-white" style="height: 300px;">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.name') }} <sup class="text-danger">( * )</sup></label>
                                        <input :placeholder="$t('app.name')" type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }">
                                        <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.contact_number') }} <sup class="text-danger">( * )</sup></label>
                                        <input :placeholder="$t('app.contact_number')" type="text" class="form-control" v-model="form.contact_number" :class="{ 'is-invalid': form.errors.has('contact_number') }">
                                        <div v-if="form.errors.has('contact_number')" v-html="form.errors.get('contact_number')" />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.email') }}</label>
                                        <input :placeholder="$t('app.email')" type="text" class="form-control" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }">
                                        <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.role') }}</label>
                                        <select disabled :class="{ 'is-invalid': form.errors.has('roles') }" class="form-control" :placeholder="$t('app.role')" v-model="form.role_id">
                                            <option v-for="(role, index) in comRole" :key="'role'+index" :value="role.id">{{role.name}}</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.password') }}</label>
                                        <input :placeholder="$t('app.password')" type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }">
                                        <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="">{{ $t('app.confirm_password') }}</label>
                                        <input :placeholder="$t('app.confirm_password')" type="password" class="form-control" v-model="form.confirm_password" :class="{ 'is-invalid': form.errors.has('confirm_password') }">
                                        <div v-if="form.errors.has('confirm_password')" v-html="form.errors.get('confirm_password')" />
                                    </div>
                                </div>
                                <div class="float-right pt-4">
                                    <button type="button" class="btn btn-success btn-lg" @click="funUpdateData()"> {{ $t('app.submit')}}</button>
                                </div>
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
            role_name: '',
            form: new Form({
                id: '',
                name: '',
                contact_number: '',
                email: '',
                password: '',
                confirm_password: '',
                role_id: '',
            }),
            comRole: [],
        };
    },
    created() {
        this.funUserInfo();
        this.funGetcomRole();
    },
    methods: {
        funGetcomRole() {
            axios.get('api/com-roles').then((res) => {
                this.comRole = res.data;
            });
        },
        funUserInfo() {
            axios.get("api/checkUserValid").then((res) => {
                this.form.fill(res.data.data);
            });
        },
        funUpdateData() {
            this.form.post("api/users").then((res) => {
                this.$toast.success(res.data.message);
            });
        },
    }
};
</script>
