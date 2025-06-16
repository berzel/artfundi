import {ref} from "vue";

export function useModal<T>() {
    const show = ref(false);
    const selected = ref<T | null>(null);

    const open = (item: T) => {
        selected.value = { ...item };
        show.value = true;
    };

    const close = () => {
        show.value = false;
        selected.value = null;
    };

    return { show, selected, open, close };
}