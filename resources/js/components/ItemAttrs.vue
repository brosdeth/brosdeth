<template>
    <span>
        <span v-for="(item, index) in attrs" :key="index">
            <span v-bind:style="{ color: colors[index]}" v-if="attrs.length-1 == index">{{item.value}}</span>
            <span v-bind:style="{ color: colors[index]}" v-else>{{item.value}}-</span>
        </span>
    </span>
</template>
<script>
    export default {
        props: {
            attrs: [],
        },
        data() {
            return {
                colors: ["#1ab394","#ed5565","#f8ac59","#5b59f8","#e059f8","#337ab7"],
            }
        },
    };
</script>
