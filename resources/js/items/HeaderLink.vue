<template>
    <div  role="group" aria-label="First group">
        <router-link class="btn bg-white shadow-sm mr-2" v-if="$can('Category List')" :to="{ name: 'Categories'}">{{ $t('app.categories') }}</router-link>
        <router-link class="btn bg-white shadow-sm mr-2" v-if="$can('Attribute List')" :to="{ name: 'Attribute'}">{{ $t('app.attribute') }}</router-link>
        <router-link class="btn bg-white shadow-sm mr-2" v-if="$can('Item List')"  :to="{ name: 'Item'}">{{ $t('app.items') }}</router-link>
    </div>
</template>
