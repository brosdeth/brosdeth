<template>
    <div class="container-fluid px-0">
        <div class="loading" v-show="isLoading">
            <img src="/icons/loading.gif"/> {{ $t('app.loading') }}
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 bg-transparent">
                    <div class="card-header border-0 bg-transparent px-0 py-0 mb_20">
                        <div class="mb_20">
                            <a class="page-title text-nowrap"><h4 class="text-koulen">{{$t('app.attribute')}}</h4></a>
                        </div>
                        <div class="d-flex justify-content-between">
                            <header-link></header-link>
                            <div>
                                <button v-if="$can('Attribute Create')" type="button" class="btn btn-success shadow-sm" data-toggle="modal" @click="funLoadModal">
                                        {{ $t('app.add_new') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-0 bg-white">
                        <div class="col-sm-12 mb-3">
                            <div class="d-flex justify-content-between pt-3">
                                <div class="d-flex">
                                    <span class="pt-1">{{ $t('app.show') }}</span>
                                    <span class="px-2">
                                        <select @change="PageChange($event)" class="form-control form-control-sm shadow-sm">
                                            <option v-for="size in pageSizes" :key="size" :value="size">
                                                {{ size }}
                                            </option>
                                        </select>
                                    </span>
                                    <span class="pt-1">{{ $t('app.entries') }}</span>
                                </div>
                                <div>
                                    <input type="search" v-model="searchTextBox" class="rounded form-control form-control-sm" style="width: 230px" placeholder="Searching..." aria-label="Search">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive table_scroll">
                                <table class="table table-borderless table_fixed">
                                    <thead class="fixed_head">
                                        <tr class="border-top border-bottom">
                                            <th scope="col">{{ $t('app.row') }}</th>
                                            <th scope="col">{{ $t('app.attribute') }}</th>
                                            <th scope="col">{{ $t('app.description') }}</th>
                                            <th scope="col">{{ $t('app.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody :class="filteredEnuiries==''&&isLoading==false?'no-data':''">
                                        <tr v-for="(item,index) in filteredEnuiries" :key="index">
                                            <td>{{index+1}}</td>
                                            <td>{{item.attr_name}}</td>
                                            <td>{{item.description}}</td>
                                            <td>
                                                <button v-if="$can('Attribute Edit')" title="Edit" @click="funLoadModal(item)"  class="btn btn-dark btn-sm s-btn-xs">
                                                        <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                                </button>
                                                <button v-if="$can('Attribute Delete')" type="button" :title="$t('app.delete')" @click="funDelete(item.id)" class="btn btn-danger btn-sm s-btn-xs">
                                                    <i class="fa-solid fa-trash-can fa-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white pb-3">
                        <div class="d-flex justify-content-between">
                            <div class="pt-2">
                                {{ $t('app.showing') }} <strong>{{current_page}}</strong> {{ $t('app.to') }} <strong>{{to_page}}</strong> {{ $t('app.of') }} <strong class="text-primary">{{total_page}}</strong>  {{ $t('app.entries') }}
                            </div>  
                            <div>
                                <pagination :data="datas" @pagination-change-page="funGetData" :limit="5"></pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create and edit  -->
        <form method="post" @submit.prevent="funSubmitData">
            <div data-backdrop="static"
                class="modal fade" id="modalAddNew" aria-hidden="true" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span v-if="isEdit==false">{{$t('app.add_new')}}</span>
                                <span v-else>{{$t('app.edit')}}</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <loading :data="form.busy"></loading>
                            <div class="col-sm-12 form-group">
                                <label for="">{{ $t('app.attribute') }} <sup class="text-danger">( * )</sup></label>
                                <input :placeholder="$t('app.attribute')" type="text" class="form-control" v-model="form.attr_name" :class="{'is-invalid': form.errors.has('attr_name') }"  >
                                <div class="valid text-danger" v-if="form.errors.has('attr_name')" v-html="form.errors.get('attr_name')" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="">{{$t('app.description')}}</label>
                                <textarea :placeholder="$t('app.description')" class="form-control" v-model="form.description"  id="" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                               <img src="/icons/b_drop.png" alt=""> {{ $t('app.close') }}
                            </button>
                            <button type="submit" :disabled="form.busy" class="btn btn-primary">
                                <img :src="form.busy ? ' /icons/loading.gif ' : '/icons/save.png'" alt="">
                                {{$t('app.submit')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
//
import Loading from '../components/loading'
import HeaderLink from './HeaderLink.vue'
export default {
    components:{
        Loading,
        HeaderLink
    },
    data() {
        return {
            action:false,
            isEdit: false,
            onchange_icon: false,
            datas: {data:[]},
            form: new Form({
                id: '',
                attr_name: '',
                description: '',
            }),
            searchTextBox: '',
        }
    },
    created(){
        this.funGetData();
    },
    computed:{
        filteredEnuiries(){
            return this.datas.data.filter(cat =>{
                return  cat.attr_name?.toLowerCase().match(this.searchTextBox.trim().toLowerCase())||
                        cat.description?.toLowerCase().match(this.searchTextBox.trim().toLowerCase())
            })
        },
    },
    methods:{
        funDelete(id){
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
                    this.form.delete("api/attributes/" + id)
                .then((res) => {
                    this.funGetData();
                    this.$toast.success('Deleted successful');
                })
                .catch((error) =>{
                    setTimeout(()=>(this.isLoading=false),200);
                    console.log(error);
                });
                }
            })
        },
        funSubmitData(){
            this.form
            .post("api/attributes")
            .then((res) => {
                this.$toast.success(res.data.message);
                this.funGetData();
                $("#modalAddNew").modal("hide");
            })
            .catch((error) =>{
                console.log(error);
            })
        },
        funLoadModal(data = null){
            this.form.reset();
            this.isEdit = false;
            if(data.id){
                this.isEdit = true;
                this.form.fill(data);
            }
            $("#modalAddNew").modal("show");
        },
        funGetData(page = 1){
            this.isLoading = true;
            axios.get('api/attributes',{
                params: {
                    page_size : this.default_page,
                    page: page
                }
            }).then((res) => {
                if(res.data==this.noPermission){
                    this.$router.push({name:'noPermission'});
                }
                this.datas  = res.data;
                this.total_page  = res.data.total;
                this.current_page  = res.data.current_page;
                this.per_page  = res.data.per_page;
                this.from_page  = res.data.from;
                this.to_page  = res.data.to;
                this.isLoading = false;
            })
            .catch((error) =>{
                console.log(error);
            });
        },
        PageChange: function () {
            this.isLoading = true;
            this.default_page = event.target.value;
            axios.get('/api/attributes',{
                params: {
                    page_size : event.target.value
                }
            }).then(function(res){
                this.isLoading = false;
                this.datas  = res.data;
                this.total_page  = res.data.total;
                this.current_page  = res.data.current_page;
                this.per_page  = res.data.per_page;
                this.from_page  = res.data.from;
                this.to_page  = res.data.to;
            }.bind(this));
        },
    }
  }
</script>