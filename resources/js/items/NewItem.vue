<template>
    <div class="new_item">
        <div class="container-fluid px-0">
            <div class="loading" v-show="isLoading"><img src="/icons/loading.gif" /> {{ $t('app.loading') }}</div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card border-0 bg-transparent">
                        <div class="card-header border-0 bg-transparent px-0 py-0 mb_20">
                            <a class="page-title text-nowrap">
                                <h4 class="text-koulen">
                                    <router-link :to="{ name: 'Item' }">{{ $t('app.items') }}</router-link>
                                    <span class="px-1">/</span>
                                    <span>{{ $t('app.add_new') }}</span>
                                </h4>
                            </a>
                        </div>
                        <div class="card-body border-0 bg-white rounded_8">
                            <div class="menu px-2">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="bars nav-item nav-link border-0 active" id="nav-home-tab"
                                        data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home"
                                        aria-selected="true">{{ $t('app.general_information') }}</a>
                                    <a class="bars nav-item nav-link border-0" id="nav-desc-tab" data-toggle="tab"
                                        href="#nav-desc" role="tab" aria-controls="nav-desc" aria-selected="false">{{
                                            $t('app.description')
                                        }}</a>
                                </div>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                        aria-labelledby="nav-home-tab">
                                        <div class="row px-3 pt-2">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>{{ $t('app.item_code') }}</label>
                                                    <input v-model="form.item_code" type="text"
                                                        class="form-control form-control-sm"
                                                        :placeholder="$t('app.create_automatically')" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ $t('app.item_name') }}<sup class="text-danger"> ( *
                                                            )</sup></label>
                                                    <input v-model="form.item_name" type="text"
                                                        class="form-control form-control-sm"
                                                        :class="{ 'is-invalid': form.errors.has('item_name') }"
                                                        placeholder="" />
                                                    <div class="valid text-danger" v-if="form.errors.has('item_name')"
                                                        v-html="form.errors.get('item_name')" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ $t('app.price') }}</label>
                                                    <input v-model="form.price" type="text"
                                                        class="form-control form-control-sm numericOnly"
                                                        :class="{ 'is-invalid': form.errors.has('price') }"
                                                        placeholder="" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ $t('app.cost') }}</label>
                                                    <input v-model="form.cost" type="text"
                                                        class="form-control form-control-sm numericOnly"
                                                        :class="{ 'is-invalid': form.errors.has('cost') }"
                                                        placeholder="" />
                                                </div>
                                                <div class="form-check" @click="checkExpiredDate">
                                                    <input type="checkbox" :checked="form.is_expired == 2"
                                                        class="form-check-input" name="expired_date" />
                                                    <label class="form-check-label">{{ $t('app.has_expires') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>{{ $t('app.category') }}</label>
                                                    <label for=""><sup class="text-danger">( * )</sup></label>
                                                    <div class="d-flex">
                                                        <div class="w-100">
                                                            <v-select label="cat_name" :placeholder="$t('app.category')"
                                                                :class="{ 'is-invalid': form.errors.has('category_id') }"
                                                                v-model="form.category_id"
                                                                :reduce="comCategories => comCategories.id"
                                                                :options="comCategories" @search="funGetComCategories">
                                                                <span slot="no-options">
                                                                    {{ $t('app.no_data') }}
                                                                </span>
                                                            </v-select>
                                                        </div>
                                                    </div>
                                                    <div class="valid text-danger" v-if="form.errors.has('category_id')"
                                                        v-html="form.errors.get('category_id')" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ $t('app.qty_goods') }}</label>
                                                    <input type="text" class="form-control form-control-sm numericOnly"
                                                        v-model="form.balance_stock" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ $t('app.barcode') }}</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        v-model="form.barcode" placeholder="" />
                                                </div>
                                                <div class="form-row pt-4">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id=""
                                                            @change="onChanageImage" />
                                                        <label class="custom-file-label" for="">Upload Photo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-desc" role="tabpanel"
                                        aria-labelledby="nav-contact-tab">
                                        <textarea v-model="form.description" class="form-control mt-3" name="" cols="30"
                                            rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white pb-3 rounded_bx_10">
                            <div class="d-flex justify-content-end">
                                <button @click="store()" :disabled="form.busy" type="button"
                                    class="btn border bg-success text-white py-2 px-4">
                                    <img v-if="form.busy" src="/icons/loading.gif" alt="">
                                    {{ $t('app.submit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ItemAttrs from '../components/ItemAttrs.vue'
export default {
    components: {
        ItemAttrs
    },
    data() {
        return {
            comAttribute: [],
            form: new Form({
                id: '',
                item_code: '',
                barcode: '',
                category_id: '',
                item_name: '',
                unit_name: '',
                balance_stock: 0,
                cost: '',
                price: '',
                image: '',
                item_attribute: [],
                item_attribute_value: [],
                item_split: [],
                stock: [],
                description: '',
                is_expired: 1,
            }),
            attrActive: '',
            comCategories: [],
        }
    },
    created() {
        this.funGetComCategories();
        this.fGetAttribute();
        this.getShowDetail();
    },
    methods: {
        getShowDetail() {
            axios.post('api/items-show', { 'id': this.$route.params.id, 'is_details': 1 })
                .then((res) => {
                    if (res.data.data) {
                        this.form.fill(res.data.data);
                    }
                }).catch((error) => {
                    console.log(error);
                });
        },
        checkExpiredDate() {
            if (this.form.is_expired == 1) {
                this.form.is_expired = 2;
            } else {
                this.form.is_expired = 1;
            }
        },
        fGetAttribute() {
            axios.get('api/attributes', {
                params: {
                    page_size: 100,
                    page: 1
                }
            }).then((res) => {
                this.comAttribute = res.data.data
            }).catch((error) => {
                console.log(error);
            });
        },
        fChangeAttr(e) {
            let push_data = {
                id: null,
                attr_name: e,
                attr_value: [],
            };
            this.form.item_attribute.push(push_data);
        },
        removeAttr(index) {
            this.form.item_attribute.splice(index, 1);
        },
        addRow() {
            let push_data = {
                id: '',
                item_id: '',
                unit_name: '',
                item_split_key: 999, // 999 default value if remove default value when edit error
                price: '',
                code: '',
                divide: '',
                barcode: this.form.barcode,
            };
            this.form.item_split.push(push_data);
        },
        removeUnit(index) {
            this.form.item_split.splice(index, 1);
        },
        generateAtrr() {
            this.form.item_attribute_value = [];
            var data = this.form.item_attribute;
            if (data.length == 1) {
                for (let index = 0; index < data[0].attr_value.length; index++) {
                    let attributes = {
                        id: '',
                        item_id: '',
                        barcode: this.form.barcode,
                        balance_stock: this.form.balance_stock,
                        code: '',
                        price: this.form.price,
                        cost: this.form.cost,
                        attrs: [
                            {
                                key: data[0].attr_name,
                                value: data[0].attr_value[index],
                            }
                        ],
                    };
                    this.form.item_attribute_value.push(attributes);
                }

            } else if (data.length == 2) {
                for (let index = 0; index < data[0].attr_value.length; index++) {
                    if (data[1].attr_value.length > 0) {
                        for (let index2 = 0; index2 < data[1].attr_value.length; index2++) {
                            let attributes = {
                                id: '',
                                item_id: '',
                                barcode: this.form.barcode,
                                balance_stock: this.form.balance_stock,
                                code: '',
                                price: this.form.price,
                                cost: this.form.cost,
                                attrs: [
                                    {
                                        key: data[0].attr_name,
                                        value: data[0].attr_value[index],
                                    },
                                    {
                                        key: data[1].attr_name,
                                        value: data[1].attr_value[index2],
                                    },
                                ],
                            };
                            this.form.item_attribute_value.push(attributes);
                        }
                    }
                }
            } else if (data.length == 3) {
                for (let index = 0; index < data[0].attr_value.length; index++) {
                    if (data[1].attr_value.length > 0) {
                        for (let index2 = 0; index2 < data[1].attr_value.length; index2++) {
                            if (data[2].attr_value.length > 0) {
                                for (let index3 = 0; index3 < data[2].attr_value.length; index3++) {
                                    let attributes = {
                                        id: '',
                                        item_id: '',
                                        barcode: this.form.barcode,
                                        balance_stock: this.form.balance_stock,
                                        code: '',
                                        price: this.form.price,
                                        cost: this.form.cost,
                                        attrs: [
                                            {
                                                key: data[0].attr_name,
                                                value: data[0].attr_value[index],
                                            },
                                            {
                                                key: data[1].attr_name,
                                                value: data[1].attr_value[index2],
                                            },
                                            {
                                                key: data[2].attr_name,
                                                value: data[2].attr_value[index3],
                                            },
                                        ],
                                    };
                                    this.form.item_attribute_value.push(attributes);
                                }

                            }
                        }

                    }
                }
            } else if (data.length == 4) {
                for (let index = 0; index < data[0].attr_value.length; index++) {
                    if (data[1].attr_value.length > 0) {
                        for (let index2 = 0; index2 < data[1].attr_value.length; index2++) {
                            if (data[2].attr_value.length > 0) {
                                for (let index3 = 0; index3 < data[2].attr_value.length; index3++) {
                                    if (data[3].attr_value.length > 0) {
                                        for (let index4 = 0; index4 < data[3].attr_value.length; index4++) {
                                            let attributes = {
                                                id: '',
                                                item_id: '',
                                                barcode: this.form.barcode,
                                                balance_stock: this.form.balance_stock,
                                                code: '',
                                                price: this.form.price,
                                                cost: this.form.cost,
                                                attrs: [
                                                    {
                                                        key: data[0].attr_name,
                                                        value: data[0].attr_value[index],
                                                    },
                                                    {
                                                        key: data[1].attr_name,
                                                        value: data[1].attr_value[index2],
                                                    },
                                                    {
                                                        key: data[2].attr_name,
                                                        value: data[2].attr_value[index3],
                                                    },
                                                    {
                                                        key: data[3].attr_name,
                                                        value: data[3].attr_value[index4],
                                                    },
                                                ],
                                            };
                                            this.form.item_attribute_value.push(attributes);
                                        }

                                    }
                                }

                            }
                        }

                    }
                }
            } else if (data.length == 5) {
                for (let index = 0; index < data[0].attr_value.length; index++) {
                    if (data[1].attr_value.length > 0) {
                        for (let index2 = 0; index2 < data[1].attr_value.length; index2++) {
                            if (data[2].attr_value.length > 0) {
                                for (let index3 = 0; index3 < data[2].attr_value.length; index3++) {
                                    if (data[3].attr_value.length > 0) {
                                        for (let index4 = 0; index4 < data[3].attr_value.length; index4++) {
                                            if (data[4].attr_value.length > 0) {
                                                for (let index5 = 0; index5 < data[4].attr_value.length; index5++) {
                                                    let attributes = {
                                                        id: '',
                                                        item_id: '',
                                                        barcode: this.form.barcode,
                                                        balance_stock: this.form.balance_stock,
                                                        code: '',
                                                        price: this.form.price,
                                                        cost: this.form.cost,
                                                        attrs: [
                                                            {
                                                                key: data[0].attr_name,
                                                                value: data[0].attr_value[index],
                                                            },
                                                            {
                                                                key: data[1].attr_name,
                                                                value: data[1].attr_value[index2],
                                                            },
                                                            {
                                                                key: data[2].attr_name,
                                                                value: data[2].attr_value[index3],
                                                            },
                                                            {
                                                                key: data[3].attr_name,
                                                                value: data[3].attr_value[index4],
                                                            },
                                                            {
                                                                key: data[4].attr_name,
                                                                value: data[4].attr_value[index5],
                                                            },
                                                        ],
                                                    };
                                                    this.form.item_attribute_value.push(attributes);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                this.$toast.danger('Limite 5');
            }
        },
        removeAtrrGenerate(index) {
            this.form.item_attribute_value.splice(index, 1);
        },
        funGetComCategories() {
            axios.get('api/com-categories')
                .then((res) => {
                    this.comCategories = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        store() {
            this.isLoading = true;
            this.form.post("api/items").then((res) => {
                if (res.data.status == 1) {
                    this.$toast.success(res.data.message);
                    this.$router.push({ name: 'Item' })
                } else {
                    this.$toast.warning(res.data.message);
                }
                this.isLoading = false;
            })
                .catch((error) => {
                    console.log(error);
                    this.isLoading = false;
                })
        },
        onChanageImage(event) {
            let files = event.target.files
            if (!files.length) return;
            let render = new FileReader();
            render.onload = (file) => {
                this.form.image = file.target.result;
            };
            render.readAsDataURL(files[0]);
        },
        removeSub(stockId, type, index) {

            axios.get('api/items-delete-sub/' + (stockId != '' ? stockId : 0)).then((res) => {
                if (res.data.status == 1) {
                    if (type == 'attr') {
                        this.removeAtrrGenerate(index);
                    } else {
                        this.removeUnit(index);
                    }
                } else {
                    this.$toast.warning(res.data.message);
                }
            }).catch((error) => {
                console.log(error);
            });
        }



    }
}
</script>