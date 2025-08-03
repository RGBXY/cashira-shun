<template>
    <div class="flex justify-center h-screen py-5">
        <div
            class="max-w-4xl w-full px-8 py-7 h-full flex flex-col gap-8 border border-gray-300 shadow rounded-xl"
        >
            <div class="flex items-center gap-3">
                <Link
                    href="/"
                    class="p-1.5 border border-gray-300 rounded-full text-gray-700 mt-0.5 shadow active:shadow-inner"
                >
                    <PhArrowLeft />
                </Link>
                <h1 class="text-3xl font-semibold">Products</h1>
            </div>

            <div class="flex justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div
                        class="border shadow-[#efeded] shadow border-gray-200 text-gray-600 rounded-lg focus-within:shadow-inner placeholder-gray-500 py-2 px-3 flex items-center gap-3"
                    >
                        <PhMagnifyingGlass />
                        <input
                            type="text"
                            class="outline-none rounded-lg"
                            placeholder="Search"
                            v-model="name"
                            @input="searchProductName()"
                        />
                    </div>
                    <div
                        class="border shadow-[#efeded] shadow border-gray-200 text-gray-600 rounded-lg focus-within:shadow-inner placeholder-gray-500 py-2 px-3 flex items-center gap-3"
                    >
                        <PhBarcode />
                        <input
                            type="text"
                            class="outline-none rounded-lg"
                            placeholder="Barcode"
                            v-model="barcode"
                            @input="searchProductBarcode()"
                        />
                    </div>
                </div>

                <Button @click="methodStore.modalFnc('product-create')">
                    <span>Add Product</span>
                </Button>
            </div>

            <el-table :data="products.data" style="width: 100%">
                <el-table-column fixed prop="name" label="Name" width="150" />
                <el-table-column label="Price" min-width="120">
                    <template #default="price">
                        {{ formatPrice(price.row.price) }}
                    </template>
                </el-table-column>
                <el-table-column prop="unit" label="Unit" width="120" />
                <el-table-column prop="barcode" label="Barcode" width="120" />
                <el-table-column
                    prop="created_at"
                    label="Created At"
                    width="150"
                >
                    <template #default="created_at">
                        {{ formatDate(created_at.row.created_at) }}
                    </template>
                </el-table-column>
                <el-table-column label="Action" width="150">
                    <template #default="action">
                        <div class="flex gap-2">
                            <Button @click="openEditModal(action.row.id)">
                                Edit
                            </Button>

                            <AlertPopover
                                :func="() => destroy(action.row.id)"
                                :id="action.row.id"
                            >
                                <Button
                                    @click="deletePopover(action.row.id)"
                                    types="danger"
                                    >Delete</Button
                                >
                            </AlertPopover>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <Pagination :pagination="products" />

            <Transition name="fade" mode="out-in">
                <CreateModalForm
                    v-if="methodStore.modalId === 'product-create'"
                />
            </Transition>

            <Transition name="fade" mode="out-in">
                <EditModalForm
                    v-if="methodStore.modalId === `product-edit`"
                    :product="selectedProduct"
                />
            </Transition>
        </div>
    </div>
</template>

<script setup>
import { PhArrowLeft, PhBarcode, PhMagnifyingGlass } from "@phosphor-icons/vue";
import { ref, Transition } from "vue";
import Button from "../../components/ui/Button.vue";
import { useMethodStore } from "../../stores/method";
import CreateModalForm from "../../components/features/product/CreateModalForm.vue";
import formatPrice from "../../core/helper/formatPrice";
import formatDate from "../../core/helper/formatDate";
import EditModalForm from "../../components/features/product/EditModalForm.vue";
import axios from "axios";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import AlertPopover from "../../components/ui/AlertPopover.vue";
import { notification } from "../../core/helper/notification";
import Pagination from "../../components/ui/Pagination.vue";

const props = defineProps({
    products: Object,
});

const methodStore = useMethodStore();

const selectedProduct = ref(0);
const name = ref(new URL(document.location).searchParams.get("name") || "");
const barcode = ref(
    new URL(document.location).searchParams.get("barcode") || ""
);

const searchProductName = debounce(() => {
    if (name === "") {
    }

    router.get(
        "/products/searchProductName",
        { name: name.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["products"],
        }
    );
}, 500);

const searchProductBarcode = debounce(() => {
    if (barcode.value === "") {
        searchProductName();
    }

    router.get(
        "/products/searchProductBarcode",
        { barcode: barcode.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["products"],
        }
    );
}, 500);

const openEditModal = async (id) => {
    const res = await axios.get(`/products/${id}`);
    if (res.data.success) {
        selectedProduct.value = res.data.data;
        methodStore.modalFnc("product-edit");
    }
};

const deletePopover = (id) => {
    methodStore.popoverStat = true;
    methodStore.popoverId = id;
};

const destroy = (id) => {
    router.delete(`/products/${id}`, {
        preserveState: false,
        onSuccess: () => {
            notification("success", "Product Deleted Successfully");
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<style lang="scss" scoped></style>
