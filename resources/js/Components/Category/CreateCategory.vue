<script setup>

import {reactive} from "vue";
import CategoryForm from "@/Components/Category/CategoryForm.vue";

const category = reactive({
    name: null,
   type: null
})

const emit = defineEmits(['success'])

</script>

<template>
    <category-form
        :action="route('dashboard')"
        :category="category"
        @success="emit('success')"
    />
</template>

<style scoped>

</style>
