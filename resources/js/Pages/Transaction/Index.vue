<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<template>

    <Head title="Transaction"/>


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction</h2>
        </template>


    </AuthenticatedLayout>
</template>

<style scoped>

</style>
