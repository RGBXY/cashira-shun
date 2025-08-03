<template>
    <Drawer>
        <div class="flex items-center justify-center h-screen w-full">
            <form
                @submit.prevent="submit()"
                class="max-w-xl w-full space-y-6 border border-gray-200 p-5 rounded-lg shadow"
            >
                <h1 class="text-2xl">Create New Product</h1>

                <div class="space-y-4">
                    <div>
                        <label class="text-gray-600">Name</label>
                        <Input
                            type="text"
                            class="w-full mt-1"
                            placeholder="Product Name"
                            v-model="form.name"
                            :massage="form.errors.name"
                        />
                    </div>
                    <div>
                        <label class="text-gray-600">Price</label>
                        <Input
                            type="number"
                            class="w-full mt-1"
                            placeholder="Product Price"
                            v-model="form.price"
                            :massage="form.errors.price"
                        />
                    </div>
                    <div>
                        <label class="text-gray-600">Barcode</label>
                        <Input
                            type="text"
                            class="w-full mt-1"
                            placeholder="Product Barcode"
                            v-model="form.barcode"
                            :massage="form.errors.barcode"
                        />
                    </div>
                    <div>
                        <label class="text-gray-600">Satuan Unit</label>
                        <Input
                            type="text"
                            class="w-full mt-1"
                            placeholder="Product Unit"
                            v-model="form.unit"
                            :massage="form.errors.unit"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-2 justify-end">
                    <Button
                        type="button"
                        types="secondary"
                        @click="methodStore.modalFncClose()"
                    >
                        Close
                    </Button>
                    <Button type="submit"> Submit </Button>
                </div>
            </form>
        </div>
    </Drawer>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import Drawer from "../../ui/Drawer.vue";
import Input from "../../ui/Input.vue";
import Button from "../../ui/Button.vue";
import { useMethodStore } from "../../../stores/method";
import { notification } from "../../../core/helper/notification";

const methodStore = useMethodStore();

const form = useForm({
    name: "",
    price: null,
    barcode: "",
    unit: "",
});

const submit = () => {
    form.post("/products", {
        onSuccess: () => {
            form.name = "";
            form.price = "";
            form.barcode = "";
            form.unit = "";

            notification("success", "Product Created Successfully");
        },
    });
};
</script>
