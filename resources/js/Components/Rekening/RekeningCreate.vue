<script setup>

import RekeningForm from "@/Components/Rekening/RekeningForm.vue";
import {reactive} from "vue";
import CategoryForm from "@/Components/Category/CategoryForm.vue";

const rekening = reactive({
    name: null,
    type: null,
    balance: 0,
})

const emit = defineEmits(['success'])

</script>

<template>
<RekeningForm
    :action="route('rekening.store')"
    :rekening="rekening"
    @success="emit('success')"
/>

</template>

<style scoped>

</style>
