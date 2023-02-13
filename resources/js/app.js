require('./bootstrap');
window.Vue = require('vue');
window.Fire = new Vue;
import Vue from 'vue'
import App from './App.vue';
import {routes} from './routes';
import VueRouter from 'vue-router';
import Form from 'vform'
import VueSweetalert2 from 'vue-sweetalert2'
import vSelect from "vue-select";
import Vue2Filters from 'vue2-filters'
import VueI18n from 'vue-i18n';
import VueHtmlToPaper from 'vue-html-to-paper'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Permissions from './permission/Permissions';
import Chartkick from 'vue-chartkick'
import Highcharts from 'highcharts'
import InputTag from 'vue-input-tag'
Vue.use(Chartkick.use(Highcharts))
Chartkick.options = {
  colors: ['#1ab394', '#23c6c8', '#1c84c6','#df8e15'],
  library: {
    animation: { easing: 'easeOutQuart' },
    yAxis: {
      labels: {
        format: '${value}'
      }
    },
    tooltip: {
      pointFormat: 'Total Spent: <b>{point.y}</b>',
      xDateFormat: '%B',
      valuePrefix: '$'
    }
  }
}
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'
import VueMobileDetection from "vue-mobile-detection";
Vue.use(VueMobileDetection);
const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    '/css/boostrap.css',
    '/css/style-print.css',
  ]
}
Vue.use(VueHtmlToPaper,options);

Vue.use(VueI18n);
Vue.use(Vue2Filters)
Vue.component("v-select", vSelect);
Vue.component('input-tag', InputTag)
Vue.use(VueSweetalert2);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.directive('tooltip', VTooltip)
Vue.directive('close-popover', VClosePopover)
Vue.component('v-popover', VPopover)
window.Form = Form;
window.Fire = new Vue;



import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
Vue.use(Toast, {
  timeout: 2000,
});


Vue.use(DatePicker);
import moment from 'moment'
Vue.config.productionTip = false
Vue.filter('onlyDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD-MMM-YYYY')
  }
});
Vue.filter('dateTime', function(value) {
  if (value) {
    return moment(String(value)).format('DD-MMM-YYYY   hh:mm A')
  }
});
Vue.filter('Time', function(value) {
  if (value) {
    return moment(String(value)).format('hh:mm A')
  }
});
Vue.mixin({
  data(){
    return{
      isLoading:false,
      noPermission:'User have not permission for this page access.',
      // Pagination
      pageSizes:[100,1000,2500,5000,'ALL'],
      from_page: 0,
      to_page: 0,
      total_page:0,
      current_page:0,
      default_page: 100,

      header_data:{
        title:'',
        from_date:'',
        to_date:'',
        invoice_number:'',
      },
    }
  },
  methods:{
    showPrintModal() {
        window.$('#show-print').modal()
    },
  }
})



Vue.mixin(Permissions);

Vue.use(VueRouter);
const router = new VueRouter({
    routes,
});
const i18n = new VueI18n({
  locale: localStorage.getItem("lang") || "km",
  messages: {
    en: require("./assets/i18n/en"),
    km: require("./assets/i18n/km")
  }
});
router.afterEach((to, from) => {
    Vue.nextTick( () => {
      document.title = to.meta.title ? to.meta.title : 'POS -SB KTV';
    });
})

new Vue({
    el: '#app',
    router,
    i18n,
    render: h => h(App),
});
