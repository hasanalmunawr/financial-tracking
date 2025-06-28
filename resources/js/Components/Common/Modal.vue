<script setup>
import { defineProps, defineEmits, computed } from "vue";

const props = defineProps({
    title: { type: String, required: true },
    size: { type: String, default: "max-w-4xl" }, // Default ukuran modal
    isOpen: { type: Boolean, required: true }
});

const emit = defineEmits(["close"]);

const closeModal = () => {
    emit("close");
};

// Mengatur ukuran modal berdasarkan props
const modalSize = computed(() => ({
    "max-w-sm": props.size === "small",
    "max-w-lg": props.size === "large",
    "max-w-xl": props.size === "extra-large",
    "max-w-4xl": props.size === "max-w-4xl",
    "max-w-5xl": props.size === "max-w-5xl",
    "max-w-7xl": props.size === "max-w-7xl",
}));

</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div
            :class="[
        'rounded-lg shadow-lg w-full max-h-[90vh] overflow-y-auto p-6 transition-colors duration-300 border',
        'bg-white text-black border-gray-300',
        'dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700',
        modalSize
      ]"
        >
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold">{{ title }}</h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-red-500 text-2xl dark:text-gray-400 dark:hover:text-red-500"
                >
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="py-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
