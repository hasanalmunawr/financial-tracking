import { ref } from "vue";
export function useModal() {
    const isOpen = ref(false);
    const modalTitle = ref("");
    const showModal = (title = "") => {
        modalTitle.value = title;
        isOpen.value = true;
    };
    const hideModal = () => {
        isOpen.value = false;
    };
    return {
        isOpen,
        modalTitle,
        showModal,
        hideModal
    };
}

