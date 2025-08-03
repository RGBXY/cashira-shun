import { defineStore } from "pinia";

export const useReceipStore = defineStore("receipt", {
    state: () => ({
        cart: [],
        cash: 0,
    }),

    actions: {},
});
