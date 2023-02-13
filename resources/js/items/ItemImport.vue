<template>
    <div class="modal fade" id="itemImport" aria-hidden="true" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <form method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Import</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 p-2">
                                <input type="file" ref="fileupload" class="form-control" @change="onChanageImage" :class="{ 'is-invalid': fileValue }">
                            </div>
                            <div class="col-sm-12 p-2">
                                <p>Download Template Import: <a href="files/Item Import.xlsx" class="button"><span class="fas fa-file-excel"></span> Download</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><img src="/icons/b_drop.png" alt="" /> {{ $t('app.close') }}</button>
                        <button type="button" @click="excelUpload" :disabled="isLoading" class="btn btn-success"><img :src="isLoading ? ' /icons/loading.gif ' : ''" alt /> {{ $t('app.submit') }}</button>
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
                file: '',
                fileValue: false,
            }
        },
        methods:{
            onChanageImage(event){
                this.file = event.target.files[0]
            },
            excelUpload(){
                this.isLoading= true;
                this.fileValue = false;
                let form = new FormData();
                form.append('file',this.file);
                axios.post("api/items-import",form).then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.$emit('onImport',true);
                    } else {
                        $('#itemImport').modal('hide');
                        this.$refs.fileupload.value=null;
                        this.$toast.warning(res.data.message);
                    }
                    this.file  ='';
                    this.isLoading= false;
                }).catch((error) => {
                    this.isLoading= false;
                    if(error.response.data.errors.file){
                        this.fileValue = true;
                    }else{
                        $('#itemImport').modal('hide');
                        this.$refs.fileupload.value=null;
                        this.file  ='';
                        this.$toast.warning(error.response.data.errors.toString());
                    }
                });
            }

        }
    };
</script>