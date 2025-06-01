<script setup>
import CheckCircle from "vue-material-design-icons/CheckCircle.vue";
import { watch } from "vue";

const props = defineProps({
    message: String
});

const emit = defineEmits(["clear"]);

const closeToast = () => {
    emit("clear");
};

watch(() => props.message, (newValue) => {
    if (newValue) {
        setTimeout(() => {
            closeToast();
        }, 3000); // Toast hilang otomatis dalam 3 detik
    }
});
</script>

<template>
    <div
        class="fixed bottom-[60px] right-4 transition-transform duration-500"
        :class="{ 'animate-toast-in-right': message }"
        v-show="message"
    >
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-4 rounded-xl shadow-2xl flex items-center space-x-3">
            <CheckCircle class="text-white w-6 h-6" />
            <span class="flex-1 font-medium tracking-wide">{{ message }}</span>
            <button @click="closeToast" class="ml-4 text-white hover:text-gray-200 text-lg leading-none font-bold">&times;</button>
        </div>
    </div>
</template>

<style scoped>
@keyframes toast-in-right {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-toast-in-right {
    animation: toast-in-right 0.4s ease-out;
}
</style>

