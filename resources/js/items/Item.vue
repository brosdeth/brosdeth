<template>
    <div class="item">
        <div class="container-fluid px-0">
            <div class="loading" v-show="isLoading">
                <img src="/icons/loading.gif" /> {{ $t('app.loading') }}
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card border-0 bg-transparent">
                        <div class="card-header border-0 bg-transparent px-0 py-0 mb_20">
                            <div class="mb_20">
                                <a class="page-title text-nowrap">
                                    <h4 class="text-koulen">{{ $t('app.items') }}</h4>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <header-link></header-link>
                                <div>
                                    <router-link to="/new-items" class="btn btn-success" v-if="$can('Item Create')">
                                        {{ $t('app.add_new') }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-0 bg-white rounded_tx_8">
                            <div class="col-sm-12 mb-3">
                                <div class="d-flex justify-content-between pt-3">
                                    <div class="d-flex">
                                        <span class="pt-1">{{ $t('app.show') }}</span>
                                        <span class="px-2">
                                            <select @change="PageChange" class="form-control form-control-sm shadow-sm">
                                                <option v-for="size in pageSizes" :key="size" :value="size">
                                                    {{ size }}
                                                </option>
                                            </select>
                                        </span>
                                        <span class="pt-1">{{ $t('app.entries') }}</span>
                                    </div>
                                    <div>
                                        <input type="search" @input="Filters"
                                            class="rounded form-control form-control-sm" style="width: 230px"
                                            placeholder="Searching..." aria-label="Search">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive table_scroll">
                                    <table class="table table-borderless table_fixed">
                                        <thead class="fixed_head">
                                            <tr class="border-top border-bottom">
                                                <th class="text-nowrap py-1">{{ $t('app.row') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.item_code') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.barcode') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.category') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.item_name') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.unit') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.cost') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.price') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.description') }}</th>
                                                <th class="text-nowrap py-1">{{ $t('app.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody :class="itemData.data == '' && isLoading == false ? 'no-data' : ''">
                                            <tr v-for="(value, index) in itemData.data" :key="index">
                                                <td>{{ index+ 1}}</td>
                                                <td class="text-nowrap">{{ value.item_code }}</td>
                                                <td class="text-nowrap">{{ value.barcode }}</td>
                                                <td class="text-nowrap">{{ value.category.cat_name }}</td>
                                                <td class="text-nowrap">{{ value.item_name }}</td>
                                                <td class="text-nowrap">{{ value.unit_name }}</td>
                                                <td class="text-nowrap">{{ value.cost | currency }}</td>
                                                <td class="text-nowrap">{{ value.price | currency }}</td>
                                                <td class="text-nowrap">{{ value.description }}</td>
                                                <td class="text-nowrap">
                                                    <router-link v-if="$can('Item Show')" :to="'item-show/' + value.id"
                                                        title="Show" class="btn btn-warning btn-sm s-btn-xs">
                                                        <i class="fa-solid fa-eye fa-sm"></i>
                                                    </router-link>
                                                    <router-link v-if="$can('Item Edit')" :to="'new-items/' + value.id"
                                                        title="Edit" class="btn btn-dark btn-sm s-btn-xs">
                                                        <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                                    </router-link>
                                                    <button type="button" v-if="$can('Item Delete')"
                                                        :title="$t('app.delete')" @click="funDelete(value.id)"
                                                        class="btn btn-danger btn-sm s-btn-xs">
                                                        <i class="fa-solid fa-trash-can fa-sm"></i>
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
                                    <strong>{{ to_page }}</strong> {{ $t('app.of') }} <strong
                                        class="text-primary">{{ total_page }}</strong> {{ $t('app.entries') }}
                                </div>
                                <div>
                                    <pagination :data="itemData" @pagination-change-page="funGetData" :limit="5">
                                    </pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <item-import @onImport="onImport"></item-import>
    </div>
</template>
<script>
import ItemImport from "./ItemImport.vue";
import HeaderLink from './HeaderLink.vue';
export default {
    components: {
        ItemImport,
        HeaderLink
    },
    data() {
        return {
            itemData: { data: [] },
            params: {
                per_page: 100,
                page: 1,
                querys: "",
            },
        };
    },
    created() {
        this.funGetData();
    },

    methods: {
        funShowData(id) {
            axios
                .post("api/items-show", { id: id, is_details: 1 })
                .then((res) => {
                    console.log(res.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        funDelete(id) {
            Vue.swal({
                title: 'Are you sure?',
                text: "You want to delete it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.isLoading = true;
                    axios.delete("api/items/" + id).then((res) => {
                        if (res.data.status == 1) {
                            this.funGetData();
                            this.$toast.success(res.data.message);
                        } else {
                            this.$toast.warning(res.data.message);
                        }
                        this.isLoading = false;
                    }).catch((error) => {
                        this.isLoading = false
                        console.log(error);
                    });
                }
            })
        },
        funGetData(page = 1) {
            this.isLoading = true;
            this.params.page = page;
            axios
                .get("api/items", {
                    params: this.params,
                })
                .then((res) => {
                    this.itemData = res.data;
                    this.total_page = res.data.meta.total;
                    this.current_page = res.data.meta.current_page;
                    this.per_page = res.data.meta.per_page;
                    this.from_page = res.data.meta.from;
                    this.to_page = res.data.meta.to;
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        PageChange: function (e) {
            this.params.per_page = e.target.value;
            this.funGetData();
        },

        Filters(e) {
            this.params.querys = e.target.value;
            this.funGetData();
        },

        itemImport() {
            $('#itemImport').modal('show');
        },
        onImport(value) {
            this.funGetData();
            $('#itemImport').modal('hide');
        }
    },
};
</script>