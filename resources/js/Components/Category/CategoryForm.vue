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
    type: props.category.type
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

        if (props.method) {
            form._method = props.method;
        }
    },
    { deep: true, immediate: true }
);





</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Branch Name -->
            <div>
                <label class="block font-semibold text-gray-700">Category Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    v-model="form.name"
                    placeholder="Enter Branch Name"
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500  transition ease-in-out"
                    required
                />
            </div>

            <!-- Branch Type -->
            <div>
                <label class="block font-semibold text-gray-700">Category Type <span class="text-red-500">*</span></label>
                <select name="" v-model="form.type" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500  transition ease-in-out">
                    <option value="" selected disabled>Select Type</option>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                </select>
            </div>
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
