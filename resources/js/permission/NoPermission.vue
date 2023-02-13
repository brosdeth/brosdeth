<template>
    <div class="container-fluid text-center">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <h1 class="text-center">You do not have permission to access!</h1>
        <br>
        <a class="text-center" @click="$router.go(-1)">
            <button type="button" :title="$t('app.edit')" @click="funEdit(user)" class="btn btn-outline-dark btn-md">
                << Back
            </button>
        </a>
        
    </div>
</template>