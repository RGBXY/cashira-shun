import { defineStore } from "pinia";

export const useMethodStore = defineStore("method", {
    state: () => ({
        modalStat: false,
        modalId: null,
        popoverStat: false,
        popoverId: null,
    }),

    actions: {
        modalFnc(id) {
            this.modalStat = true;
            this.modalId = id;
            document.body.style.overflow = "hidden";
        },
        modalFncClose() {
            this.modalStat = false;
            this.modalId = null;
            document.body.style.overflow = "auto";
        },
    },
});
