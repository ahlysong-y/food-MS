<template>
    <div class="flex h-full gap-6">
        <div
            class="w-1/3 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col"
        >
            <div class="p-5 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-bold text-gray-800">
                    <i
                        class="fa-solid fa-file-invoice-dollar text-green-500 mr-2"
                    ></i>
                    រង់ចាំគិតប្រាក់
                </h2>
            </div>

            <div class="flex-1 p-4 overflow-y-auto space-y-3">
                <div
                    v-if="unpaidOrders.length === 0"
                    class="text-center text-gray-400 mt-10"
                >
                    <i class="fa-solid fa-check-double text-4xl mb-3"></i>
                    <p>គ្មានវិក្កយបត្រត្រូវគិតលុយទេ</p>
                </div>

                <div
                    v-for="order in unpaidOrders"
                    :key="order.id"
                    @click="selectOrder(order)"
                    :class="[
                        'p-4 rounded-lg border cursor-pointer transition flex justify-between items-center',
                        selectedOrder?.id === order.id
                            ? 'border-green-500 bg-green-50 shadow-md'
                            : 'border-gray-200 hover:border-green-300 hover:bg-gray-50',
                    ]"
                >
                    <div>
                        <p class="font-bold text-gray-800 text-lg">
                            <i class="fa-solid fa-chair text-gray-400 mr-1"></i>
                            តុលេខ {{ order.table_number }}
                        </p>
                        <p class="text-sm text-gray-500">
                            វិក្កយបត្រ: #INV-{{ order.id }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-500 text-lg">
                            ${{ order.total_amount.toFixed(2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden"
        >
            <div
                v-if="!selectedOrder"
                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-gray-50 z-10"
            >
                <i
                    class="fa-solid fa-hand-pointer text-6xl mb-4 text-gray-300"
                ></i>
                <p class="text-xl font-bold">សូមជ្រើសរើសវិក្កយបត្រនៅខាងឆ្វេង</p>
                <p>ដើម្បីធ្វើការទូទាត់ប្រាក់</p>
            </div>

            <div
                class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center"
            >
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        តុលេខ {{ selectedOrder?.table_number }}
                    </h2>
                    <p class="text-gray-500">
                        លេខកូដ: #INV-{{ selectedOrder?.id }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">សរុបទឹកប្រាក់ (Total)</p>
                    <p class="text-3xl font-bold text-red-600">
                        ${{ selectedOrder?.total_amount.toFixed(2) }}
                    </p>
                </div>
            </div>

            <div class="flex-1 p-6 overflow-y-auto">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">
                    មុខម្ហូបដែលបានកុម្ម៉ង់៖
                </h3>
                <div class="space-y-3">
                    <div
                        v-for="(item, index) in selectedOrder?.items"
                        :key="index"
                        class="flex justify-between items-center text-gray-600"
                    >
                        <p>
                            <span class="font-bold w-6 inline-block"
                                >{{ item.quantity }}x</span
                            >
                            {{ item.name }}
                        </p>
                        <p>${{ (item.price * item.quantity).toFixed(2) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >វិធីសាស្ត្រទូទាត់៖</label
                        >
                        <div class="flex gap-3">
                            <button
                                @click="paymentMethod = 'Cash'"
                                :class="[
                                    'flex-1 py-3 rounded-lg font-bold transition flex justify-center items-center',
                                    paymentMethod === 'Cash'
                                        ? 'bg-blue-600 text-white shadow-md'
                                        : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100',
                                ]"
                            >
                                <i class="fa-solid fa-money-bill-wave mr-2"></i>
                                សាច់ប្រាក់
                            </button>
                            <button
                                @click="paymentMethod = 'ABA_KHQR'"
                                :class="[
                                    'flex-1 py-3 rounded-lg font-bold transition flex justify-center items-center',
                                    paymentMethod === 'ABA_KHQR'
                                        ? 'bg-blue-600 text-white shadow-md'
                                        : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100',
                                ]"
                            >
                                <i class="fa-solid fa-qrcode mr-2"></i> KHQR
                            </button>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >ប្រាក់ទទួលពីអតិថិជន ($)៖</label
                        >
                        <input
                            type="number"
                            v-model="amountReceived"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xl font-bold text-right"
                            placeholder="0.00"
                        />
                    </div>
                </div>

                <div
                    class="mt-6 flex justify-between items-center border-t border-gray-200 pt-6"
                >
                    <div>
                        <p class="text-gray-500 font-bold">
                            ប្រាក់ត្រូវអាប់ (Change):
                        </p>
                        <p
                            :class="[
                                'text-2xl font-bold',
                                changeAmount >= 0
                                    ? 'text-green-600'
                                    : 'text-red-500',
                            ]"
                        >
                            ${{
                                changeAmount >= 0
                                    ? changeAmount.toFixed(2)
                                    : "0.00"
                            }}
                        </p>
                    </div>
                    <button
                        @click="processPayment"
                        :disabled="changeAmount < 0"
                        :class="[
                            'px-8 py-4 rounded-lg font-bold text-white text-lg transition shadow-md flex items-center',
                            changeAmount >= 0
                                ? 'bg-green-500 hover:bg-green-600'
                                : 'bg-gray-400 cursor-not-allowed',
                        ]"
                    >
                        <i class="fa-solid fa-print mr-2"></i> ទូទាត់ & បោះពុម្ព
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

// ទិន្នន័យសាកល្បង (ថ្ងៃក្រោយទាញពី API)
const unpaidOrders = ref([
    {
        id: 101,
        table_number: "T-01",
        total_amount: 10.5,
        items: [
            { name: "បាយឆាសាច់គោ", quantity: 2, price: 2.5 },
            { name: "ស៊ុបតុមយាំ", quantity: 1, price: 5.5 },
        ],
    },
    {
        id: 102,
        table_number: "T-03",
        total_amount: 12.0,
        items: [
            { name: "មីឆាគ្រឿងសមុទ្រ", quantity: 3, price: 3.0 },
            { name: "កូកាកូឡា", quantity: 3, price: 1.0 },
        ],
    },
]);

const selectedOrder = ref(null);
const paymentMethod = ref("Cash");
const amountReceived = ref("");

// ជ្រើសរើសវិក្កយបត្រ
const selectOrder = (order) => {
    selectedOrder.value = order;
    amountReceived.value = order.total_amount; // ដាក់តម្លៃពេញជាលំនាំដើម
    paymentMethod.value = "Cash";
};

// គណនាប្រាក់អាប់ដោយស្វ័យប្រវត្តិ
const changeAmount = computed(() => {
    if (!selectedOrder.value || !amountReceived.value) return -1;
    return parseFloat(amountReceived.value) - selectedOrder.value.total_amount;
});

// ដំណើរការទូទាត់ប្រាក់
const processPayment = () => {
    if (changeAmount.value < 0) {
        return alert("ចំនួនប្រាក់មិនគ្រប់គ្រាន់ទេ!");
    }

    // កន្លែងបាញ់ API (ឧ. axios.post('/api/payments/process', ...))
    console.log("ទូទាត់ជោគជ័យ:", {
        order_id: selectedOrder.value.id,
        amount_paid: parseFloat(amountReceived.value),
        method: paymentMethod.value,
        change: changeAmount.value,
    });

    alert(`ការទូទាត់ជោគជ័យ! ប្រាក់អាប់: $${changeAmount.value.toFixed(2)}`);

    // លុបវិក្កយបត្រនោះចេញពីបញ្ជីខាងឆ្វេង
    unpaidOrders.value = unpaidOrders.value.filter(
        (o) => o.id !== selectedOrder.value.id,
    );
    selectedOrder.value = null; // Reset ផ្ទាំងខាងស្តាំ
    amountReceived.value = "";
};
</script>
