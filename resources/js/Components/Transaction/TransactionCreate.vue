<script setup>

import TransactionForm from "@/Components/Transaction/TransactionForm.vue";
import {reactive} from "vue";


const transaction = reactive({
    name: null,
    type: null
})

const emit = defineEmits(['success'])
</script>

<template>
    <TransactionForm
        :action="route('transaction.store')"
        :transaction="transaction"
        @success="emit('success')"
    />
</template>

<style scoped>

</style>
