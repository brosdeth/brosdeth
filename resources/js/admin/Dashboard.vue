<template>
    <div class="dashboard">
        <div class="container-fluid px-0">
            <div class="loading" v-show="isLoading">
                <img src="/icons/loading.gif"/> {{ $t('app.loading') }}
            </div>
            <div class="row  mb-2  px-2"> 
                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12  mb-2 px-2">
                    <div class="som_card d-flex p-2 align-items-center rounded_8">
                        <div class="px-3 text-center ">
                            <div class="icon bg_green">
                                <i class="fa-solid fa-usd text-white"></i>
                            </div>
                        </div>
                        <div class="w-75 d-fl align-content-center">
                            <h4 class="mb-2 text_green"> {{ total_all.total_sale | currency }} </h4>
                            <p class="text-secondary">
                               {{$t("app.total_sale")}}  
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12  mb-2 px-2">
                    <div class="som_card d-flex p-2 align-items-center rounded_8">
                        <div class="px-3 text-center">
                            <div class="icon bg_greenlow">
                                <i class="fa-solid fa-th-large text-white font-weight-bolder"></i>
                            </div>
                        </div>
                        <div class="w-75 d-fl align-content-center">
                            <h4 class="mb-2 text_greenlow"> {{ total_all.total_purchase | currency }} </h4>
                            <p class="text-secondary">
                                {{$t("app.total_purchase")}}  
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 mb-2 px-2">
                    <div class="som_card d-flex p-2 align-items-center rounded_8">
                        <div class="px-3 text-center">
                            <div class="icon bg_bluegrey">
                                <i class="fa-solid fa-fire text-white"></i>
                            </div>
                        </div>
                        <div class="w-75 d-fl align-content-center">
                            <h4 class="mb-2 text_bluegrey"> {{ total_all.total_paid | currency }} </h4>
                            <p class="text-secondary">
                                {{$t("app.total_paid")}}  
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 mb-2 px-2">
                    <div class="som_card d-flex p-2 align-items-center rounded_8">
                        <div class="px-3 text-center">
                            <div class="icon bg_blown">
                                <i class="fa-solid fa-table text-white"></i>
                            </div>
                        </div>
                        <div class="w-75 d-fl align-content-center">
                            <h4 class="mb-2 text_blown"> {{ total_all.total_unpaid | currency }} </h4>
                            <p class="text-secondary">
                                  {{$t("app.total_unpaid")}}  
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row table_list px-2">
                 <div class="col-xl-8 col-lg-12  col-12  mb-3 px-2">
                    <div class="rounded_8 bg-white p-2 card fa-border border-0">
                        <div class="card-body">
                            <area-chart :download="true" empty="No data" loading="Loading..." :data="fetchSaleMonthly" :refresh="1000" prefix="$"  thousands="," :title="$t('app.monthly_sale')"></area-chart>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 col-12  mb-3 px-2">
                    <div class="rounded_8 bg-white p-2 card fa-border border-0">
                        <div class="card-body">
                            <pie-chart :donut="true" loading="Loading..." empty="No data" :data="total_all.methods" prefix="$" thousands="," :title="$t('app.receive_money')"></pie-chart>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-12  mb-3 px-2">
                    <div class="rounded_8 bg-white p-2 card fa-border  border-0">
                        <div class="card-body">
                            <bar-chart loading="Loading..." empty="No data" :data="fetchSaleByCategory" :title="$t('app.sale_by_category')" :refresh="1000"  thousands=","></bar-chart>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-12  mb-3 px-2">
                    <div class="rounded_8 bg-white p-2 card fa-border  border-0">
                        <div class="card-body">
                            <column-chart :stacked="true" empty="No data" :data="fetchSaleYearly" :title="$t('app.Yearly_sale')" :refresh="1000" prefix="$"  thousands=","></column-chart>
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
            total_all:'',
        }
    },
    computed: {

    },
    mounted() {
        this.getDeta();
    },
    methods: {
        getDeta() {
            axios.get('/api/all-total').then(function (res) {
                if (res.data == 'User have not permission for this page access.') {
                    this.$router.push({ name: 'noPermission' });
                }
                this.total_all = res.data;
            }.bind(this));
        },
        fetchSaleMonthly(success, fail) {
            axios.get("api/get-data-sale-by-mothly").then((res) => {
                success(res.data);
            });
        },
        fetchSaleYearly(success, fail) {
            axios.get("api/get-data-sale-by-yearly").then((res) => {
                success(res.data);
            });
        },
        fetchSaleByCategory(success, fail) {
            axios.get("api/get-data-sale-by-category").then((res) => {
                success(res.data);
            });
        },
    }

}
</script>

<style scoped lang="scss">
.dashboad {
    position: fixed;
    height: 100vh;
    width: 100%;
    padding: 150px 30px;
    background: #f5f5f5;
    overflow: auto;
    color: #2d995b;

    .d-top {
        .box {
            height: 130px;
            width: 100%;
            background: #fff;

            .icon {
                width: 80px;
                height: 80px;
                margin-top: 24px;
                margin-left: 24px;
                border-radius: 50%;
                line-height: 80px;
                text-align: center;
                font-size: 34px;
                border-width: 3px !important;
            }

            .desc {
                width: calc(100% - 80px);
                padding: 40px 30px;
            }
        }
    }

    .d-center {
        .box {
            // height: 500px;
            width: 100%;
            background: #fff;
            padding: 15px;
        }
    }

    table {
        thead {
            white-space: nowrap;
        }

        .table-scroll {
            max-height: 150px;
            overflow: auto;
            scrollbar-width: none;

            &::-webkit-scrollbar {
                display: none;
            }
        }
    }
}
</style>





