<template>
    <div class="d_header bg-white">
        <div class="row mx-0 px-2 pyt_5 align-mid align-items-center">
            <div class="mr-auto">
                <button type="button" class="btn btn-md btn-white border-0 btn_navbar_toggle" id="btn_navbar_toggle">
                    <i class="fa-solid fa-bars f_size_20"></i>
                </button>
            </div>
            <ul class="d-flex">
                <li class="nav-item dropdown mr-2 bg-white">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-expanded="false">
                        {{ user.name }}
                    </a>
                    <div class="dropdown-menu shadow dropdown-menu-right border-0 rounded-0 m-0 p-2 cus_arrow_top"
                        aria-labelledby="navbarDropdown">
                        <router-link :to="{ name: 'Profile' }" class="dropdown-item py-3 pl-3 rounded_8"
                            style="padding-right: 140px;">
                            <i class="fa-solid fa-users text-secondary pr-3 p-0"></i>
                            <span>{{ $t('Profile') }}</span>
                        </router-link>
                        <div class="dropdown-divider m-0"></div>
                        <a @click="funLogout()" class="dropdown-item py-3 pl-3 rounded_8 f_size_15"
                            style="padding-right: 140px;">
                            <i class="fa-solid fa-arrow-right-from-bracket text-secondary pr-3"></i>
                            <span>{{ $t('app.logout') }}</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="navbar_left vh-100 bg-white shadow-sm position-fixed z_index_99"
            style="border-right: 0.2px solid #f5f5f5;">
            <router-link :to="{ name: 'Dashboard' }">
                <div class="d-flex align-items-center p-2 logo mt-3">
                    <img src="/images/logo.png" alt="" width="50px" />
                    <p class="h4 col ml-2 mb-0 p-0 all_color">POS</p>
                </div>
            </router-link>
            <div class="accordion pt-4" id="action_collapse">
                <div class="pyt_5" v-if="$can('Dashboard')">
                    <router-link :to="{ name: 'Dashboard' }"
                        class="d-flex justify-content-between h_collapse ml_list position-relative">
                        <div class="d-flex align-items-center h_collapse btn_collapse btn_collapse_white">
                            <i
                                class="cus_icon fa-solid fa-circle-dollar-to-slot fa-lg text-secondary px-4 f_size_20"></i>
                            <span class="nd_none nowrap">{{ $t('app.dashboard') }}</span>
                        </div>
                    </router-link>
                </div>
                <div class="pyt_5" v-if="$can('Item List') || $can('Category List') || $can('Attribute List')">
                    <router-link
                        :to="{ name: $can('Item List') ? 'Item' : ($can('Category List') ? 'Categories' : 'Attribute') }"
                        class="d-flex justify-content-between h_collapse ml_list position-relative" :class="
                            routeName == 'NewItem' ||
                                routeName == 'Item' ||
                                routeName == 'Categories' ||
                                routeName == 'Attribute' ? 'router-link-exact-active' : ''
                        ">
                        <div class="d-flex align-items-center h_collapse btn_collapse btn_collapse_white">
                            <i class="cus_icon fa-solid fa-list-ul fa-lg text-secondary px-4 f_size_20"></i>
                            <span class="nd_none nowrap">{{ $t('app.items') }}</span>
                        </div>
                    </router-link>
                </div>
                <div class="pyt_5 position-relative"
                    v-if="$can('User List') || $can('Expense List') || $can('CashDrawer Report') || $can('Expense Report') || $can('Profit and loss Report')">
                    <div class="d-flex justify-content-between h_collapse ml_list position-relative"
                        data-toggle="collapse" role="button" data-target="#list_9" :class="
                            routeName == 'User' ||
                                routeName == 'UserRole'
                                ? 'router-link-exact-active' : ''
                        ">
                        <div class="d-flex align-items-center h_collapse btn_collapse btn_collapse_white"
                            aria-expanded="true">
                            <i class="cus_icon fa-solid fa-gear fa-lg text-secondary px-4 f_size_20"></i>
                            <span class="nd_none nowrap">{{ $t('app.setting') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right f_size_12 text-secondary px-4 nd_none arrow-right"></i>
                    </div>
                    <div id="list_9" class="cus_collapse collapse" data-parent="#action_collapse">
                        <ul class="list-group">

                            <router-link :to="{ name: 'User' }" class="mtp_1" v-if="$can('User List')">
                                <li class="list-group-item border-0 h_collapse pl_80 text-nowrap" role="button">
                                    {{ $t('app.user') }}
                                </li>
                            </router-link>
                            <router-link :to="{ name: 'UserRole' }" class="mtp_1" v-if="$can('Role List')">
                                <li class="list-group-item border-0 h_collapse pl_80 text-nowrap" role="button">
                                    {{ $t('app.role') }}
                                </li>
                            </router-link>
                        </ul>
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
            user: "",
            hidden: false,
            lang: "km",
            routeName: "",
        };
    },
    created() {
        this.checkRoute();
        this.funUserInfo();

        if (localStorage.lang) {
            this.lang = localStorage.lang;
        }
    },
    watch: {
        $route() {
            this.checkRoute();
            this.funUserInfo();
        },
    },
    methods: {

        checkRoute() {
            this.routeName = this.$route.name;
        },
        funUserInfo() {
            axios.get("api/checkUserValid").then((res) => {
                this.user = res.data.data;
            });
        },
        funLogout() {
            axios.post("/logout").then((response) => {
                this.$router.push("/");
                this.user = "";
                localStorage.setItem("checked", "logout");
            }).catch((error) => {
                location.reload();
            });
        },
    },
};
</script>

<style lang="scss">
.wrapper {
    border: 1px solid #eee;
    background: #f5f5f5;
    border-radius: 5px;
    padding: 10px;
}

.wrapper-search {
    position: absolute;
    width: calc(100% - 18px);
    max-height: 300px;
    overflow: auto;
    z-index: 99;
    border-radius: 0 0 5px 5px;
    border: 1px solid #eee;

    span {
        display: flex;
        padding: 5px 10px;
        cursor: pointer;
        border: none;
        background: #fff;

        &:hover {
            color: #fff !important;
            background: var(--bg-bheader);
        }
    }

    .text-danger {
        cursor: not-allowed !important;
    }
}

.tb-custom {
    position: relative;
    padding: 4px 0 0px;

    &::after {
        content: "";
        position: absolute;
        right: 0;
        left: -4px;
        right: -4px;
        top: -3px;
        bottom: -3px;
        border-top: 1px dotted #eee;
        border-left: 1px dotted #eee;
        border-right: 1px dotted #eee;
    }
}

.tb-custom:nth-child(1) {
    &::after {
        content: "";
        display: none;
    }
}

.lang_active {
    color: #0c9046;
    padding: 3px 8px;
    border-radius: 20px;
    background: #f0f0f0;
}
</style>
