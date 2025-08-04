import { defineStore } from "pinia";

export const useReceipStore = defineStore("receipt", {
    state: () => ({
        cart: [],
        cash: null,
    }),

    actions: {},
});
