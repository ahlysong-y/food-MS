<template>
    <div class="flex h-full gap-6">
        <div class="flex-1 flex flex-col gap-6">
            <div
                class="bg-white p-5 rounded-xl shadow-sm border border-gray-100"
            >
                <h2 class="text-lg font-bold text-gray-800 mb-3">
                    <i class="fa-solid fa-chair mr-2"></i>ជ្រើសរើសតុញ៉ាំអាហារ
                </h2>
                <div class="flex gap-3 overflow-x-auto pb-2">
                    <button
                        v-for="table in tables"
                        :key="table.id"
                        @click="selectedTable = table.id"
                        :class="[
                            'px-6 py-3 rounded-lg font-bold transition min-w-[100px]',
                            selectedTable === table.id
                                ? 'bg-blue-600 text-white shadow-md'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200',
                        ]"
                    >
                        {{ table.name }}
                    </button>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex-1 overflow-y-auto"
            >
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fa-solid fa-burger mr-2"></i>មុខម្ហូបប្រចាំហាង
                </h2>
                <div
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="menu in menus"
                        :key="menu.id"
                        @click="addToCart(menu)"
                        class="border rounded-lg p-4 cursor-pointer hover:shadow-lg hover:border-blue-300 transition text-center bg-gray-50"
                    >
                        <div
                            class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl"
                        >
                            🍲
                        </div>
                        <h3 class="font-bold text-gray-700">{{ menu.name }}</h3>
                        <p class="text-green-600 font-bold mt-1">
                            ${{ menu.price.toFixed(2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="w-96 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col"
        >
            <div class="p-5 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fa-solid fa-receipt mr-2"></i>បញ្ជីកុម្ម៉ង់
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    តុដែលជ្រើសរើស៖
                    <span class="font-bold text-blue-600">{{
                        selectedTable ? "តុលេខ " + selectedTable : "មិនទាន់រើស"
                    }}</span>
                </p>
            </div>

            <div class="flex-1 p-5 overflow-y-auto space-y-3">
                <div
                    v-if="cart.length === 0"
                    class="text-center text-gray-400 mt-10"
                >
                    <i class="fa-solid fa-cart-arrow-down text-4xl mb-3"></i>
                    <p>មិនទាន់មានមុខម្ហូបទេ</p>
                </div>

                <div
                    v-for="(item, index) in cart"
                    :key="index"
                    class="flex justify-between items-center border-b pb-2"
                >
                    <div>
                        <p class="font-bold text-gray-700">{{ item.name }}</p>
                        <p class="text-sm text-gray-500">
                            ${{ item.price.toFixed(2) }} x {{ item.quantity }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="decreaseQty(index)"
                            class="w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 font-bold"
                        >
                            -
                        </button>
                        <span class="font-bold w-6 text-center">{{
                            item.quantity
                        }}</span>
                        <button
                            @click="increaseQty(index)"
                            class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 font-bold"
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <div class="flex justify-between text-lg mb-4">
                    <span class="font-bold text-gray-600">សរុប (Total):</span>
                    <span class="font-bold text-red-600"
                        >${{ totalAmount.toFixed(2) }}</span
                    >
                </div>
                <button
                    @click="submitOrder"
                    :disabled="cart.length === 0 || !selectedTable"
                    :class="[
                        'w-full py-3 rounded-lg font-bold text-white text-lg transition shadow-md flex justify-center items-center',
                        cart.length === 0 || !selectedTable
                            ? 'bg-gray-400 cursor-not-allowed'
                            : 'bg-green-500 hover:bg-green-600',
                    ]"
                >
                    <i class="fa-solid fa-paper-plane mr-2"></i> បញ្ជូនទៅផ្ទះបាយ
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import api from "../axios"; // ហៅ Axios ដែលយើងបានរៀបចំមកប្រើ

// ... (ទិន្នន័យ tables និង menus ទុកនៅដដែលសិន)...
const tables = ref([
    { id: 1, name: "T-01" },
    { id: 2, name: "T-02" },
]);

const menus = ref([
    { id: 1, name: "បាយឆាសាច់គោ", price: 2.5 },
    { id: 2, name: "ស៊ុបតុមយាំ", price: 5.5 },
]);

const selectedTable = ref(null);
const cart = ref([]);

const totalAmount = computed(() => {
    return cart.value.reduce(
        (total, item) => total + item.price * item.quantity,
        0,
    );
});

const addToCart = (menu) => {
    const existingItem = cart.value.find((item) => item.id === menu.id);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.value.push({ ...menu, quantity: 1 });
    }
};

const increaseQty = (index) => cart.value[index].quantity++;
const decreaseQty = (index) => {
    if (cart.value[index].quantity > 1) {
        cart.value[index].quantity--;
    } else {
        cart.value.splice(index, 1);
    }
};

// កែប្រែមុខងារបញ្ជូនការកុម្ម៉ង់ដោយបាញ់ API ទៅកាន់ Laravel ពិតប្រាកដ
const submitOrder = async () => {
    if (!selectedTable.value) return alert("សូមជ្រើសរើសតុជាមុនសិន!");

    try {
        // រៀបចំទម្រង់ទិន្នន័យអោយត្រូវនឹងតម្រូវការ Backend
        const orderData = {
            dining_table_id: selectedTable.value,
            items: cart.value.map((item) => ({
                menu_id: item.id,
                quantity: item.quantity,
            })),
        };

        // បាញ់ POST Request ទៅកាន់ API របស់ Laravel (routes/api.php)
        const response = await api.post("/orders/place", orderData);

        // ប្រសិនបើជោគជ័យ
        alert("✅ " + response.data.message);

        // ជម្រះទិន្នន័យចោលវិញក្រោយបញ្ជូនរួច
        cart.value = [];
        selectedTable.value = null;
    } catch (error) {
        // ប្រសិនបើមានបញ្ហា (ឧទាហរណ៍៖ អត់ទាន់ Login ឬ Network Error)
        if (error.response) {
            alert("❌ បរាជ័យ៖ " + error.response.data.message);
        } else {
            alert("❌ មានបញ្ហាក្នុងការភ្ជាប់ទៅកាន់ម៉ាស៊ីនមេ (Server)!");
        }
    }
};
</script>
