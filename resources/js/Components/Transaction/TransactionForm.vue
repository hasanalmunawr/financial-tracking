<script setup>


import {useForm} from "@inertiajs/vue3";
import {onMounted, ref, watch} from "vue";

const props = defineProps({
    transaction: {
        type: Object,
        required: true
    },
    method: String,
    action: {
        type: String,
        required: true
    }
})

const myRekenings = ref([]);
const myCategories = ref([])

const formData = {
    date: props.transaction.date,
    description: props.transaction.description,
    source: props.transaction.source,
    type: props.transaction.type,
    category: props.transaction.category,
    amount: props.transaction.amount
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

const loadRekening = () => {
    axios.get(route('rekening.get-my-rekening'))
        .then(response => {
            myRekenings.value = response.data.data;
        })
        .catch(error => {
            console.error(error);
        });
}

const loadCategory = () => {
    axios.get(route('category.get-my-Category'))
        .then(response => {
            myCategories.value = response.data.data;
        })
        .catch(error => {
            console.error(error);
        });
}

const formatRupiah = (value) => {
    if (!value) return '0';
    return Number(value).toLocaleString('id-ID');
}

const onInputAmount = (e) => {
    const raw = e.target.value.replace(/\D/g, ''); // Hapus semua non-digit
    form.amount = parseInt(raw || '0');
}


// Watch perubahan props.question untuk reset form setiap kali modal dibuka
watch(
    () => props.transaction,
    (newCategory) => {
        form.date = newCategory.date || "";
        form.description = newCategory.description || "";
        form.category = newCategory.category || "";
        form.source = newCategory.source || "";
        form.type = newCategory.type || "";
        form.amount = newCategory.amount || "";

        if (props.method) {
            form._method = props.method;
        }
    },
    { deep: true, immediate: true }
);

onMounted(() => {
    loadRekening();
    loadCategory();
})

</script>

<template>
    <form @submit.prevent="submitForm">
        <!-- Transaction Date -->
        <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Date</label>
            <input type="date" v-model="form.date" required
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"/>
        </div>

        <!-- Description -->
      <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Description</label>
            <input type="text" v-model="form.description" required
                   placeholder="Enter Description Transaction.."
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300"/>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Source -->
          <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Source</label>
                <select v-model="form.source" required class="w-full border border-gray-300 rounded-md p-2">
                    <option disabled selected value="">Select Source</option>
                    <option v-for="rekening in myRekenings" :key="rekening.id" :value="rekening.id">{{ rekening.name }} - {{ rekening.type }}</option>
                </select>
            </div>

            <!-- Category -->
          <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Category</label>
                <select v-model="form.category" required class="w-full border border-gray-300 rounded-md p-2">
                    <option disabled selected value="">Select Category</option>
                    <option v-for="category in myCategories" :key="category.id" :value="category.id">{{ category.name }}</option>

                    <!-- tambah kategori lainnya -->
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Type -->
          <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Type</label>
                <select v-model="form.type" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300">
                    <option disabled selected value="">Select Type</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                    <option value="mutasi+">Mutasi +</option>
                    <option value="mutasi -">Mutasi -</option>
                </select>
            </div>

            <!-- Nominal -->
          <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Transaction Nominal</label>
              <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp.</span>
                <input type="text"  :value="formatRupiah(form.amount)"
                       @input="onInputAmount" required
                       class="w-full pl-10 border border-gray-300 rounded-md focus:ring focus:ring-blue-300"/>
              </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-right mt-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Submit
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
