<script setup>
import {ref, onMounted, watch} from 'vue'
import axios from 'axios'

const expense = ref([])

const chartExpenseCategory = ref({
    title: {
        text: 'Expense by Category',
        left: 'center'
    },
    tooltip: {
        trigger: 'item',
        formatter: function (params) {
            const formattedValue = new Intl.NumberFormat('id-ID').format(params.value)
            return `${params.name}: Rp ${formattedValue} (${params.percent}%)`
        }
    },

    legend: {
        orient: 'vertical',
        left: 'left'
    },
    series: [
        {
            name: 'Expense',
            type: 'pie',
            radius: '60%',
            data: [],
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
})

const selectedMonth = ref(new Date().toISOString().slice(0, 7)); // format YYYY-MM

const loadExpenseByCategory = () => {
    const [year, month] = selectedMonth.value.split('-');
    axios.get(route('dashboard.get-expense-categories'), {
        params: {
            year,
            month
        }
    })
        .then(response => {
            const data = response.data.data || []
            expense.value = response.data.data || []
            const pieData = data.map(item => ({
                name: item.category,
                value: item.category_total
            }))

            chartExpenseCategory.value.series[0].data = pieData
        })
        .catch(error => {
            console.error('Failed to load expense category:', error)
        })
}

// Reload chart data when month changes
watch(selectedMonth, loadExpenseByCategory);

// Load initial chart
loadExpenseByCategory();
</script>

<template>
    <div class="w-full mx-auto p-4  shadow rounded mt-6">
        <label for="month-picker" class="block mb-2 text-sm font-medium">Pilih Bulan</label>
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
        <v-chart class="w-full h-96" :option="chartExpenseCategory" autoresize />

        <div class="overflow-x-auto p-4 rounded shadow mt-6">
            <h3 class="text-lg font-semibold  mb-4">Ringkasan Pengeluaran per Kategori</h3>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300 font-medium">Kategori</th>
                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300 font-medium">Total Pengeluaran</th>
                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300 font-medium">Jumlah Transaksi</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <tr v-for="exs in expense" :key="exs.category" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ exs.category }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                        Rp {{ parseFloat(exs.category_total).toLocaleString('id-ID') }}
                    </td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ exs.total_transactions }}</td>
                </tr>
                </tbody>

                <tfoot class="bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-200">
                <tr>
                    <td class="px-4 py-2">Total</td>
                    <td class="px-4 py-2">
                        Rp {{
                            expense.reduce((sum, exs) => sum + parseFloat(exs.category_total), 0)
                                .toLocaleString('id-ID')
                        }}
                    </td>
                    <td class="px-4 py-2">
                        {{ expense.reduce((sum, exs) => sum + exs.total_transactions, 0) }}
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>

    </div>
</template>

<style scoped>

</style>
