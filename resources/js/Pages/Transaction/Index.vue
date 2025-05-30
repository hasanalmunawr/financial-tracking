<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import ContainerContent from "@/Components/Common/ContainerContent.vue";
import {useModal} from "@/Composable/useModal";
import Modal from "@/Components/Common/Modal.vue";
import TransactionCreate from "@/Components/Transaction/TransactionCreate.vue";
import {onMounted, ref} from "vue";

const {isOpen, showModal, hideModal, modalTitle} = useModal();

const transactions = ref([]);

const newTransaction = () => {
    modalTitle.value = "New Transaction"
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
        // hour: '2-digit',
        // minute: '2-digit',
        // hour12: false,
    });
}

const loadTransactions = () => {
    axios.get(route('transaction.get-my-Transaction'))
        .then(response => {
            transactions.value = response.data.data;
        })
        .catch(error => {
            console.error(error);
        });
}

onMounted(() => {
    loadTransactions();
})

</script>

<template>

    <Head title="Transaction"/>


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction</h2>
        </template>


        <ContainerContent>

            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Transaction History</h2>
                <button @click="newTransaction" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    New Transaction
                </button>
            </div>


                <ul class="divide-y divide-gray-200">
                    <li
                        v-for="(item, index) in transactions"
                        :key="index"
                        class="py-4 p-5 flex justify-between items-center shadow-lg"
                    >
                        <div>
                            <p class="text-sm text-gray-600">{{ formatDate(item.date) }} - {{ item.rekening_name }}</p>
                            <p class="font-medium text-gray-900">{{ item.description }}</p>
                            <p class="text-sm text-gray-500">Kategori: {{ item.category_name }} | Jenis:
                                <span :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'">
                               {{ item.type }}
                            </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                :class="item.type === 'income' ? 'text-green-700' : 'text-red-700'"
                                class="text-lg font-semibold"
                            >
                               {{ item.type === 'income' ? '+' : '-'}} {{ formatRupiah(item.amount) }}
                            </p>
                        </div>
                    </li>
                </ul>



<!--            <div class="relative overflow-x-auto">-->
<!--                <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">-->
<!--                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">-->
<!--                    <tr>-->
<!--                        <th scope="col" class="px-6 py-3">-->
<!--                            Category Name-->
<!--                        </th>-->
<!--                        <th scope="col" class="px-6 py-3">-->
<!--                            Category Type-->
<!--                        </th>-->
<!--                        <th scope="col" class="px-6 py-3">-->
<!--                            Created Date-->
<!--                        </th>-->
<!--                        <th scope="col" class="px-6 py-3">-->
<!--                            Action-->
<!--                        </th>-->

<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr v-for="category in categories" :key="category.id" class="bg-white border-b border-gray-200">-->
<!--                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">-->
<!--                            {{ category.name }}-->
<!--                        </th>-->
<!--                        <td class="px-6 py-4">-->
<!--                            {{ category.type }}-->
<!--                        </td>-->
<!--                        <td class="px-6 py-4">-->
<!--                            {{ category.created_at }}-->
<!--                        </td>-->
<!--                        <td class="px-6 py-4">-->
<!--                            <button>Edit</button>-->
<!--                        </td>-->

<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->

        </ContainerContent>

    </AuthenticatedLayout>

    <Modal :title="modalTitle" :is-open="isOpen" @close="hideModal">
        <TransactionCreate @success="hideModal"/>
    </Modal>
</template>

<style scoped>

</style>
