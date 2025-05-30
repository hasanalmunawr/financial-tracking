<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import ContainerContent from "@/Components/Common/ContainerContent.vue";
import Modal from "@/Components/Common/Modal.vue";
import CreateCategory from "@/Components/Category/CreateCategory.vue";
import {useModal} from "@/Composable/useModal";

const {isOpen, showModal, hideModal, modalTitle} = useModal();

defineProps({
    categories: {
        type: Array,
        required: true
    }
})

const newCategory = () => {
    modalTitle.value = "New Category"
    isOpen.value = true
}

const formatRupiah = (value) => {
    if (!value) return 'Rp. 0';
    return 'Rp. ' + Number(value).toLocaleString('id-ID');
}

const formatDate = (datetime, locale = 'id-ID') => {
    const date = new Date(datetime);
    return date.toLocaleString(locale, {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    });
}
</script>

<template>
    <Head title="Transaction Category"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction Category</h2>
        </template>

        <ContainerContent>

            <div class="flex justify-end mb-4">
                <button @click="newCategory" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    New Category
                </button>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Created At
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(category, index) in categories" :key="category.id" class="bg-white border-b border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{ index + 1 }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{ category.name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ formatDate(category.created_at) }}
                        </td>
                        <td class="px-6 py-4">
                            <button>Edit</button>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </div>

        </ContainerContent>
    </AuthenticatedLayout>

    <Modal :title="modalTitle" :is-open="isOpen" @close="hideModal">
        <CreateCategory @success="hideModal"/>
    </Modal>
</template>

<style scoped>

</style>
