<script setup>



import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";

const props = defineProps({
    category: {
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
    name: props.category.name,
    type: props.category.type,
    amount: props.category.amount
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
    () => props.category,
    (newCategory) => {
        form.name = newCategory.name || "";
        form.type = newCategory.type || "";
        form.amount = newCategory.amount || "";

        if (props.method) {
            form._method = props.method;
        }
    },
    { deep: true, immediate: true }
);





</script>

<template>
    <form @submit.prevent="submitForm">

        <!-- Branch Name -->
        <div>
            <label class="block font-semibold text-gray-700">Category <span class="text-red-500">*</span></label>
            <input
                type="text"
                v-model="form.name"
                placeholder="Enter Category eg.Gaji"
                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"
                required
            />
        </div>

        <!-- Submit Button -->
        <div class="text-right mt-4">
            <button
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition ease-in-out"
            >
                Submit Category
            </button>
        </div>

    </form>

</template>

<style scoped>

</style>
