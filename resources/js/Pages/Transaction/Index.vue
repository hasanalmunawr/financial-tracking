<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import ContainerContent from "@/Components/Common/ContainerContent.vue";
import {useModal} from "@/Composable/useModal";
import Modal from "@/Components/Common/Modal.vue";
import TransactionCreate from "@/Components/Transaction/TransactionCreate.vue";
import {onMounted, ref, watch} from "vue";

const {isOpen, showModal, hideModal, modalTitle} = useModal();

const transactions = ref([]);
const selectedMonth = ref(new Date().toISOString().slice(0, 7)); // format YYYY-MM

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
    const [year, month] = selectedMonth.value.split('-');

    axios.get(route('transaction.get-my-Transaction'), {
        params: {
            year,
            month
        }
    }).then(response => {
            transactions.value = response.data.data;
        })
        .catch(error => {
            console.error(error);
        });
}

// Reload chart data when month changes
watch(selectedMonth, loadTransactions);

// Load initial chart
loadTransactions();
</script>

<template>

    <Head title="Transaction"/>


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Transaction</h2>
        </template>


        <ContainerContent>

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <!-- Left: Bulan Picker -->
                    <div class="w-full md:w-auto">
                        <label for="month-picker" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Bulan
                        </label>
                        <input
                            id="month-picker"
                            type="month"
                            class="mb-4 border border-gray-300 dark:border-gray-600
         bg-white dark:bg-gray-800
         text-black dark:text-white
         placeholder-gray-500 dark:placeholder-gray-400
         rounded px-3 py-2 w-full max-w-xs"
                            v-model="selectedMonth"
                        />
                    </div>

                    <!-- Middle: Judul -->
                    <h2 class="text-lg font-semibold text-gray-800 text-center md:text-left">
                        Transaction History
                    </h2>

                    <!-- Right: Tombol -->
                    <button
                        @click="newTransaction"
                        type="button"
                        class="whitespace-nowrap text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow"
                    >
                        New Transaction
                    </button>
                </div>

                <!-- Bisa letakkan chart atau tabel di bawah ini -->
                <!-- <ChartComponent /> -->


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
                                <span
                                    :class="{
                                  'text-green-700': item.type === 'income',
                                  'text-red-700': item.type === 'expense',
                                  'text-blue-700': item.type === 'mutasi'
                                }"
                                >
                               {{ item.type }}
                            </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                :class="{
                                  'text-green-700': item.type === 'income',
                                  'text-red-700': item.type === 'expense',
                                  'text-blue-700': item.type === 'mutasi'
                                }"
                                class="text-lg font-semibold"
                            >
                                <span v-if="item.type === 'income'">+ {{ formatRupiah(item.amount) }}</span>
                                <span v-else-if="item.type === 'expense'">− {{ formatRupiah(item.amount) }}</span>
                                <span v-else-if="item.type === 'mutasi'">→ {{ formatRupiah(item.amount) }}</span>
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
