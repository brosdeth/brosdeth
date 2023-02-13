<template>
    <div class="new_item">
        <div class="container-fluid px-0">
            <div class="loading" v-show="isLoading"><img src="/icons/loading.gif" /> {{ $t('app.loading') }}</div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card border-0 bg-transparent">
                        <div class="card-header border-0 bg-transparent px-0 py-0 mb_20">
                            <div class="mb_20">
                                <a class="page-title text-nowrap">
                                    <h4 class="text-koulen">
                                        <router-link :to="{ name: 'Item' }">{{ $t('app.items') }}</router-link>
                                        <span class="px-1">/</span>
                                        <span>{{ $t('app.show') }}</span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                        <div class="card-body border-0 bg-white">
                            <div class="menu px-2">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="bars nav-item nav-link border-0 active" id="tab1" data-toggle="tab"
                                            href="#nav-tab1" role="tab" aria-controls="nav-tab1" aria-selected="true">{{
                                                    $t('app.general_information')
                                            }}</a>

                                        <a class="bars nav-item nav-link border-0" id="tab2" data-toggle="tab"
                                            href="#nav-tab2" role="tab" aria-controls="nav-tab2"
                                            aria-selected="false">{{ $t('app.stock_summary') }}</a>
                                        <a v-show="itemShow.expire_date != ''" class="bars nav-item nav-link border-0"
                                            id="tab3" data-toggle="tab" href="#nav-tab3" role="tab"
                                            aria-controls="nav-tab3" aria-selected="false">{{
                                                    $t('app.expired_date')
                                            }}</a>
                                        <a class="bars nav-item nav-link border-0" id="tab4" data-toggle="tab"
                                            href="#nav-tab4" role="tab" aria-controls="nav-tab4"
                                            aria-selected="false">{{ $t('app.description') }}</a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-tab1" role="tabpanel"
                                        aria-labelledby="tab1">
                                        <div class="row px-3 pt-2">
                                            <div class="col-sm-2">
                                                <div class="form-group ">
                                                    <img :src="itemShow.image" />
                                                </div>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col px-0 border-bottom align-top">
                                                        <table class="w-100">
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{ $t('app.item_code') }}
                                                                </td>
                                                                <th class="col px-0 text-left pl-5">
                                                                    {{ itemShow.item_code }}</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="py-2 text-nowrap">{{ $t('app.item_name') }}
                                                                </td>
                                                                <th class="col px-0 text-left pl-5">
                                                                    <p>{{ itemShow.item_name }}</p>
                                                                    <span v-show="itemShow.unit_name != ''"
                                                                        class="bg-info text-white rounded px-1"><small>{{
                                                                                itemShow.unit_name
                                                                        }}</small></span>
                                                                </th>
                                                            </tr>
                                                            <!-- <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">ចំនួនស្តុក</td>
                                                                <th class="col px-0 text-left pl-5">2-999</th>
                                                            </tr> -->
                                                            <tr class="border-bottom">
                                                                <td colspan="2" class="py-2 text-nowrap"> <input
                                                                        type="checkbox"
                                                                        :checked="itemShow.is_expired == 2" disabled> {{
                                                                                $t('app.has_expires')
                                                                        }}</td>

                                                            </tr>
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{ $t('app.price') }}</td>
                                                                <th class="col px-0 text-left pl-5">{{ itemShow.price |
                                                                        currency
                                                                }}
                                                                </th>
                                                            </tr>
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{ $t('app.cost') }}</td>
                                                                <th class="col px-0 text-left pl-5">{{ itemShow.cost |
                                                                        currency
                                                                }}
                                                                </th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <table class="w-100">
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{ $t('app.product_type') }}
                                                                </td>
                                                                <th class="col px-0 text-left pl-5">
                                                                    {{ itemShow.category.cat_name }}</th>
                                                            </tr>
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{ $t('app.barcode') }}</td>
                                                                <th class="col px-0 text-left pl-5">{{ itemShow.barcode
                                                                }}
                                                                </th>
                                                            </tr>
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">{{
                                                                        $t('app.date_creation')
                                                                }}</td>
                                                                <th class="col px-0 text-left pl-5">
                                                                    {{ itemShow.created_at }}</th>
                                                            </tr>
                                                            <tr class="border-bottom">
                                                                <td class="py-2 text-nowrap">Summary</td>
                                                                <th class="col px-0 text-left pl-5"></th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-tab2" role="tabpanel" aria-labelledby="tab2">
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr class="border-bottom border-top">
                                                            <th>{{ $t('app.row') }}</th>
                                                            <th>{{ $t('app.barcode') }}</th>
                                                            <th>{{ $t('app.item_code') }}</th>
                                                            <th>{{ $t('app.unit_name') }}</th>
                                                            <th>{{ $t('app.attribute') }}</th>
                                                            <th>{{ $t('app.stock_qty') }}</th>
                                                            <th>{{ $t('app.cost') }}</th>
                                                            <th>{{ $t('app.price') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(item, index) in itemShow.stock" :key="index">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ item.barcode }}</td>
                                                            <td>{{ item.code }}</td>
                                                            <td>{{ item.unit_name }}</td>
                                                            <td>
                                                                <item-attrs :attrs="item.attrs"></item-attrs>
                                                            </td>
                                                            <td>{{ item.balance_stock }}</td>
                                                            <td>{{ item.cost }}</td>
                                                            <td>{{ item.price }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-tab3" role="tabpanel" aria-labelledby="tab3">
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr class="border-bottom border-top">
                                                            <th>{{ $t('app.row') }}</th>
                                                            <th>{{ $t('app.unit_name') }}</th>
                                                            <th>Batch Code</th>
                                                            <th>{{ $t('app.expired_date') }}</th>
                                                            <th>{{ $t('app.attribute') }}</th>
                                                            <th>{{ $t('app.purchase_qty') }}</th>
                                                            <th>Balance Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(items, index) in itemShow.expire_date" :key="index">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ items.unit_name }}</td>
                                                            <td>{{ items.batch_code }}</td>
                                                            <td>{{ items.expired_date }}</td>
                                                            <td>
                                                                <item-attrs :attrs="items.attrs"></item-attrs>
                                                            </td>
                                                            <td>{{ items.purchasing_qty }}</td>
                                                            <td>{{ items.balance_stock }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-tab4" role="tabpanel" aria-labelledby="tab4">
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <textarea class="form-control mt-1" name="" cols="30" rows="10"
                                                    readonly v-model="itemShow.description"></textarea>
                                            </div>
                                        </div>
                                    </div>
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
import ItemAttrs from '../components/ItemAttrs.vue'
export default {
    components: {
        ItemAttrs
    },
    data() {
        return {
            itemShow: {},

        }
    },
    created() {
        this.getShowDetail();
        this.fshowfun();
    },
    methods: {

        getShowDetail() {
            axios.post('api/items-show', { 'id': this.$route.params.id, 'is_details': 1 })
                .then((res) => {
                    this.itemShow = res.data.data
                }).catch((error) => {
                    console.log(error);
                });
        }

    }
}
</script>