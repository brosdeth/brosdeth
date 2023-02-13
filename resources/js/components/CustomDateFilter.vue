<template>
    <div class="modal fade" id="modalFilter" aria-hidden="true" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ $t('app.filter') }}</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 form-group report-list" v-for="(value,index) in dateFilters.group" :key="index">
                            <label>{{value.title_km}}</label>
                            <p v-for="(val, idx) in value.keywords" :key="'keyword'+idx" @click="chooseDate(val)">{{val.title_km}}</p>
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
                dateFilters: [],
            }
        },
        mounted(){
            this.getFilterDate()
        },
        methods:{
            getFilterDate(){
                axios.get("api/get-keyword-date").then((res) => {
                    this.dateFilters = res.data;
                });
            },
            chooseDate(data){
                this.$emit('chooseDate',data);
                $('#modalFilter').modal('hide');
            }
        }
    };
</script>
<style scoped>
 .report-list label{
    padding: 10px;
    width: 100%;
    font-weight: bold;
    text-align: center;
    border-radius: 1px;
    background: #f5f5f5;
  }
  .report-list p{
    padding: 10px;
    cursor: pointer;
    text-align: center;
  }
   .report-list p:hover{
    color: #0056b3;
    background: #f5f5f5;
    border-radius: 1px;
  }
</style>