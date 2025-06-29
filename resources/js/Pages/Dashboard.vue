<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import ContainerContent from "@/Components/Common/ContainerContent.vue";

import DashboardMonthlyIncomeExpense from "@/Components/Dashboard/DashboardMonthlyIncomeExpense.vue";
import DashboardExpenseCategory from "@/Components/Dashboard/DashboardExpenseCategory.vue";


console.log(route('dashboard.get-expense-categories'))
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Dashboard</h2>
        </template>

        <ContainerContent>
            <DashboardMonthlyIncomeExpense/>
        </ContainerContent>
        <ContainerContent>
            <DashboardExpenseCategory/>
        </ContainerContent>

    </AuthenticatedLayout>
</template>
