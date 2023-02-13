<template>
    <div class="loading" v-show="data">
        <img src="/icons/loading.gif"/> {{ $t('app.loading') }}
    </div>
</template>
<script>
export default {
    props:['data']
}
</script>