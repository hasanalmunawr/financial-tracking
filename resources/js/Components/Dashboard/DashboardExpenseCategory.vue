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
    <div class="w-full mx-auto p-4 bg-white shadow rounded mt-6">
        <label for="month-picker" class="block mb-2 text-sm font-medium text-gray-700">Pilih Bulan</label>
        <input
            id="month-picker"
            type="month"
            class="mb-4 border rounded px-3 py-2 w-full max-w-xs"
            v-model="selectedMonth"
        />
        <v-chart class="w-full h-96" :option="chartExpenseCategory" autoresize />

        <div class="overflow-x-auto bg-white p-4 rounded shadow mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pengeluaran per Kategori</h3>

            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Kategori</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Total Pengeluaran</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Jumlah Transaksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                <tr v-for="exs in expense" :key="exs.category" class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700">{{ exs.category }}</td>
                    <td class="px-4 py-2 text-gray-700">Rp {{ parseFloat(exs.category_total).toLocaleString('id-ID') }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ exs.total_transactions }}</td>
                </tr>
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                <tr>
                    <td class="px-4 py-2 text-gray-800">Total</td>
                    <td class="px-4 py-2 text-gray-800">
                        Rp {{
                            expense.reduce((sum, exs) => sum + parseFloat(exs.category_total), 0)
                                .toLocaleString('id-ID')
                        }}
                    </td>
                    <td class="px-4 py-2 text-gray-800">
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
