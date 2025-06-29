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
    amount: props.transaction.amount,
    rekening_tujuan: props.transaction.rekening_tujuan,
    rekening_tranfer: props.transaction.rekening_tranfer,
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
        form.rekening_tranfer = newCategory.rekening_tranfer || "";
        form.rekening_tujuan = newCategory.rekening_tujuan || "";

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
    <form @submit.prevent="submitForm" class="text-gray-800 dark:text-gray-100">
        <!-- Tipe Transaksi -->
        <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Jenis Transaksi</label>
            <select v-model="form.type" required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800 focus:ring focus:ring-blue-300">
                <option disabled selected value="">Pilih Jenis</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
                <option value="mutasi">Mutasi Antar Rekening</option>
            </select>
        </div>

        <!-- Tanggal Transaksi -->
        <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Transaksi</label>
            <input type="date" v-model="form.date" required
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800 focus:ring focus:ring-blue-300" />
        </div>

        <!-- Mutasi: Rekening Asal dan Tujuan -->
        <div v-if="form.type === 'mutasi'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Rekening Asal</label>
                <select v-model="form.rekening_tranfer"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800">
                    <option disabled selected value="">Pilih Rekening Asal</option>
                    <option v-for="rekening in myRekenings" :key="rekening.id" :value="rekening.id">
                        {{ rekening.name }} - {{ rekening.type }}
                    </option>
                </select>
            </div>

            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Rekening Tujuan</label>
                <select v-model="form.rekening_tujuan"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800">
                    <option disabled selected value="">Pilih Rekening Tujuan</option>
                    <option v-for="rekening in myRekenings" :key="rekening.id" :value="rekening.id">
                        {{ rekening.name }} - {{ rekening.type }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Non-mutasi: Rekening Asal -->
        <div v-else class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Rekening</label>
            <select v-model="form.source" required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800">
                <option disabled selected value="">Pilih Rekening</option>
                <option v-for="rekening in myRekenings" :key="rekening.id" :value="rekening.id">
                    {{ rekening.name }} - {{ rekening.type }}
                </option>
            </select>
        </div>

        <!-- Jumlah Uang & Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Jumlah -->
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah Uang</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">Rp</span>
                    <input type="text" :value="formatRupiah(form.amount)"
                           @input="onInputAmount" required
                           class="w-full pl-10 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 focus:ring focus:ring-blue-300" />
                </div>
            </div>

            <!-- Kategori -->
            <div class="mb-2">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Kategori</label>
                <select v-model="form.category" required
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800">
                    <option disabled selected value="">Pilih Kategori</option>
                    <option v-for="category in myCategories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-2">
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Deskripsi</label>
            <input type="text" v-model="form.description" required
                   placeholder="Contoh: Bayar listrik, Gaji, Transfer ke rekening BRI"
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-800 focus:ring focus:ring-blue-300" />
        </div>

        <!-- Tombol Submit -->
        <div class="text-right mt-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600">
                Simpan Transaksi
            </button>
        </div>
    </form>

</template>

<style scoped>

</style>
