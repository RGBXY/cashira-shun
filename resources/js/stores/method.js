import { defineStore } from "pinia";

export const useMethodStore = defineStore("method", {
    state: () => ({
        modalStat: false,
        modalId: null,
    }),

    actions: {
        modalFnc(id) {
            this.modalStat = !this.modalStat;
            this.modalId = id;
        },
    },
});
