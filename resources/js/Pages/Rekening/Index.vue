<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import ContainerContent from "@/Components/Common/ContainerContent.vue";
import EyeOff from "vue-material-design-icons/EyeOff.vue";
import Eye from "vue-material-design-icons/Eye.vue";
import {ref} from "vue";
import RekeningCreate from "@/Components/Rekening/RekeningCreate.vue";
import Modal from "@/Components/Common/Modal.vue";
import {useModal} from "@/Composable/useModal";

const props = defineProps({
    rekenings: {
        type: Array,
        required: true
    }
})

const {isOpen, showModal, hideModal, modalTitle} = useModal();


// Map visibilitas berdasarkan index
const showNominalMap = ref({});

// Inisialisasi semua `false`
props.rekenings.forEach((_, index) => {
    showNominalMap.value[index] = false;
});

// Fungsi toggle
const toggleNominal = (index) => {
    showNominalMap.value[index] = !showNominalMap.value[index];
};

const formatRupiah = (value) => {
    if (!value) return 'Rp. 0';
    return 'Rp. ' + Number(value).toLocaleString('id-ID');
}

const newRekening = () => {
    modalTitle.value = "New Rekening"
    isOpen.value = true
}
</script>

<template>
    <Head title="Rekening"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Rekening</h2>
        </template>

        <ContainerContent>

            <div class="flex justify-end mb-4">
                <button @click="newRekening" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    New Rekening
                </button>
            </div>


            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-300">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Saldo</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(rekening, index) in rekenings"
                        :key="rekening.id"
                        class="border-b border-gray-200 dark:border-gray-700"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ index + 1 }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ rekening.name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ rekening.type }}
                        </td>
                        <td class="relative px-6 py-4">
                            {{ showNominalMap[index] ? formatRupiah(rekening.balance) : '**********' }}
                            <span
                                @click="toggleNominal(index)"
                                class="absolute right-3 top-3 h-5 w-5 cursor-pointer text-blue-500"
                            >
          <Eye v-if="!showNominalMap[index]" />
          <EyeOff v-else />
        </span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-blue-500 hover:underline">Edit</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </ContainerContent>
    </AuthenticatedLayout>

    <Modal :title="modalTitle" :is-open="isOpen" @close="hideModal">
        <RekeningCreate @success="hideModal"/>
    </Modal>
</template>

<style scoped>

</style>
