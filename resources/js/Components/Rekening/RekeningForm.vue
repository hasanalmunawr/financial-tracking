<script setup>

import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";

const props = defineProps({
    rekening: {
        type: Object,
        required: true
    },
    method: String,
    action: {
        type: String,
        required: true
    }
})

const formData = {
    name: props.rekening.name,
    type: props.rekening.type,
    balance: props.rekening.balance,
}

if (props.method) {
    formData._method = props.method
}

const form = useForm(formData)
const emit = defineEmits(['success'])


const submitForm = () => {
    form.post(props.action, {
        onSuccess: () => {
            form.reset()
            emit('success')
        },
        onError: (error) => {
            console.error(error)
        }
    })
}


// Watch perubahan props.question untuk reset form setiap kali modal dibuka
watch(
    () => props.rekening,
    (newRekening) => {
        form.name = newRekening.name || "";
        form.type = newRekening.type || "";
        form.balance = newRekening.balance || "";

        if (props.method) {
            form._method = props.method;
        }
    },
    { deep: true, immediate: true }
);




</script>

<template>
    <form @submit.prevent="submitForm">
        <!-- Transaction Date -->
        <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700">Rekening Name</label>
            <input type="text" v-model="form.name" required
                   placeholder="Enter Rekening Name eg. BNI, Danamon..."
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"/>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Type -->
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Rekening Type</label>
                <input type="text" v-model="form.type" required
                       placeholder="Enter Rekening Category eg. Primary, Investment..."
                       class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"/>
            </div>

            <!-- Nominal -->
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Rekening Nominal (default 0)</label>
                <input type="number" v-model.number="form.amount" required
                       class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"/>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-right mt-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Submit
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
