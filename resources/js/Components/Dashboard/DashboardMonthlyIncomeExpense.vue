<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Chart config
const chartMonthlyTransaction = ref({
    title: {
        text: 'Monthly Income & Expense',
        left: 'center'
    },
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    legend: {
        data: ['Income', 'Expense'],
        top: 'bottom'
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '10%',
        containLabel: true
    },
    xAxis: {
        type: 'category',
        data: [] // Will be filled with month labels like Jan, Feb
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name: 'Income',
            type: 'bar',
            data: [],
            itemStyle: { color: '#22c55e' } // Tailwind green-500
        },
        {
            name: 'Expense',
            type: 'bar',
            data: [],
            itemStyle: { color: '#ef4444' } // Tailwind red-500
        },

    ]
})

// Load data
const loadMonthlyTransaction = () => {
    axios.get(route('dashboard.get-monthly-income-expense'))
        .then(response => {
            const data = response.data.data || []

            // Prepare month labels
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

            // Group data by month
            const grouped = {}

            data.forEach(item => {
                const monthLabel = monthNames[item.month - 1]

                if (!grouped[monthLabel]) {
                    grouped[monthLabel] = { income: 0, expense: 0 }
                }

                grouped[monthLabel][item.type] = parseFloat(item.total_amount)
            })

            const labels = Object.keys(grouped)
            const incomeData = labels.map(label => grouped[label].income || 0)
            const expenseData = labels.map(label => grouped[label].expense || 0)

            // Update chart data
            chartMonthlyTransaction.value.xAxis.data = labels
            chartMonthlyTransaction.value.series[0].data = incomeData
            chartMonthlyTransaction.value.series[1].data = expenseData
        })
        .catch(error => {
            console.error('Failed to load monthly transaction:', error)
        })
}

// Run on mount
onMounted(() => {
    loadMonthlyTransaction()
})
</script>

<template>
    <div class="w-full mx-auto p-4 shadow rounded">
        <v-chart class="w-full h-96" :option="chartMonthlyTransaction" autoresize/>
    </div>
</template>

<style scoped>

</style>
