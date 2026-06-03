<template>
    <div class="h-full flex flex-col gap-6">
        <div
            class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center"
        >
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fa-solid fa-boxes-stacked text-blue-500 mr-2"></i>
                    Inventory
                </h2>
                <p class="text-gray-500 mt-1">
                    ពិនិត្យមើល និងបញ្ជូលស្តុកប្រចាំសាខា
                </p>
            </div>
            <button
                @click="showAddForm = !showAddForm"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-bold transition flex items-center shadow-md"
            >
                <i class="fa-solid fa-plus mr-2"></i>
                {{ showAddForm ? "បិទទម្រង់" : "បញ្ចូលស្តុកថ្មី" }}
            </button>
        </div>

        <div class="flex gap-6 flex-1 overflow-hidden">
            <div
                class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden"
            >
                <div
                    class="p-4 border-b border-gray-100 bg-gray-50 font-bold text-gray-700 grid grid-cols-5 text-center"
                >
                    <div class="text-left">
                        <i class="fa-solid fa-tag mr-1"></i> ឈ្មោះគ្រឿងផ្សំ
                    </div>
                    <div>
                        <i class="fa-solid fa-scale-balanced mr-1"></i>
                        ចំនួនស្តុក
                    </div>
                    <div>
                        <i class="fa-solid fa-ruler mr-1"></i> ខ្នាតរង្វាស់
                    </div>
                    <div>
                        <i class="fa-solid fa-circle-info mr-1"></i> ស្ថានភាព
                    </div>
                    <div><i class="fa-solid fa-gear mr-1"></i> សកម្មភាព</div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    <div
                        v-for="item in inventoryItems"
                        :key="item.id"
                        class="grid grid-cols-5 text-center items-center p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition"
                    >
                        <div class="text-left font-bold text-gray-800">
                            {{ item.name }}
                        </div>
                        <div class="font-bold text-xl text-blue-600">
                            {{ item.quantity.toFixed(2) }}
                        </div>
                        <div class="text-gray-500">{{ item.unit }}</div>
                        <div>
                            <span
                                v-if="item.quantity > 5"
                                class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-bold"
                                >ស្តុកគ្រប់គ្រាន់</span
                            >
                            <span
                                v-else
                                class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-bold animate-pulse"
                                >ជិតអស់ហើយ</span
                            >
                        </div>
                        <div>
                            <button
                                @click="deleteProduct(item.id)"
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition"
                                title="លុបគ្រឿងផ្សំ"
                            >
                                <i class="fa-solid fa-trash-can text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="showAddForm"
                class="w-80 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition-all"
            >
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-5">
                    <i class="fa-solid fa-cart-plus text-green-500 mr-2"></i>
                    ទម្រង់បញ្ចូលស្តុក
                </h3>

                <div class="space-y-5 flex-1">
                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >ជ្រើសរើសគ្រឿងផ្សំ៖</label
                        >
                        <select
                            v-model="form.id"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700"
                        >
                            <option :value="null" disabled>
                                -- សូមជ្រើសរើស --
                            </option>
                            <option
                                v-for="item in inventoryItems"
                                :key="item.id"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >数量ដែលត្រូវបន្ថែម៖</label
                        >
                        <input
                            type="number"
                            v-model="form.qty"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700"
                            placeholder="ឧ. 5.5"
                        />
                    </div>
                </div>

                <button
                    @click="saveStock"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition shadow-md mt-4 flex justify-center items-center"
                >
                    <i class="fa-solid fa-save mr-2"></i> រក្សាទុកទិន្នន័យ
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Swal from "sweetalert2";

const showAddForm = ref(false);

// ទិន្នន័យសាកល្បង (ថ្ងៃក្រោយទាញពី API: /api/inventory)
const inventoryItems = ref([
    { id: 1, name: "សាច់គោ", quantity: 15.5, unit: "Kg" },
    { id: 2, name: "អង្ករ", quantity: 4.0, unit: "Kg" },
    { id: 3, name: "ប្រេងឆា", quantity: 20, unit: "Liter" },
    { id: 4, name: "បន្លែចម្រុះ", quantity: 2.0, unit: "Kg" },
]);

const form = ref({ id: null, qty: "" });

// មុខងាររក្សាទុកស្តុកថ្មី
const saveStock = () => {
    if (!form.value.id || !form.value.qty) {
        Swal.fire({
            icon: "warning",
            title: "ព័ត៌មានមិនគ្រប់គ្រាន់!",
            text: "សូមបំពេញព័ត៌មានអោយបានគ្រប់គ្រាន់!",
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#3b82f6",
        });
        return;
    }

    const item = inventoryItems.value.find((i) => i.id === form.value.id);
    if (item) {
        item.quantity += parseFloat(form.value.qty); // បូកចំនួនស្តុកថ្មីចូលស្តុកចាស់

        Swal.fire({
            icon: "success",
            title: "បន្ថែមស្តុកជោគជ័យ!",
            text: `បានបន្ថែមចំនួន ${form.value.qty} ${item.unit} ទៅក្នុងស្តុក [${item.name}] រួចរាល់!`,
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#10b981",
        });

        // លុបទិន្នន័យក្នុងប្រអប់ចោលវិញក្រោយ Save រួច
        form.value.id = null;
        form.value.qty = "";
        showAddForm.value = false;
    }
};

// មុខងារលុបទំនិញចេញពីស្តុក (ពេលនេះដំណើរការជាមួយប៊ូតុងក្នុង HTML ហើយ)
const deleteProduct = async (productId) => {
    const result = await Swal.fire({
        title: "លុបទំនិញនេះចេញពីស្តុក?",
        text: "ទិន្នន័យទំនិញនេះនឹងត្រូវបាត់បង់ទាំងស្រុងពីប្រព័ន្ធ!",
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: "#dc2626", // ពណ៌ក្រហមដាច់ខាត
        cancelButtonColor: "#6b7280",
        confirmButtonText: "បាទ/ចាស លុបចេញ",
        cancelButtonText: "បោះបង់",
    });

    if (result.isConfirmed) {
        // លុបទំនិញចេញពីបញ្ជីបង្ហាញនៅលើអេក្រង់
        inventoryItems.value = inventoryItems.value.filter(
            (item) => item.id !== productId,
        );

        Swal.fire({
            icon: "success",
            title: "បានលុប!",
            text: "ទំនិញត្រូវបានលុបចេញពីស្តុកជោគជ័យ។",
            showConfirmButton: false,
            timer: 1500,
        });
    }
};
</script>
