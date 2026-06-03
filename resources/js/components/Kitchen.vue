<template>
    <div class="h-full flex flex-col gap-6">
        <div
            class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center"
        >
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fa-solid fa-fire-burner text-orange-500 mr-2"></i>
                    Kitchen Orders
                </h2>
                <p class="text-gray-500 mt-1">
                    បញ្ជីមុខម្ហូបដែលត្រូវចម្អិនជូនភ្ញៀវ
                </p>
            </div>
            <div
                class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg font-bold"
            >
                កំពុងរង់ចាំ៖ {{ pendingOrders.length }} វិក្កយបត្រ
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div
                v-if="pendingOrders.length === 0"
                class="h-full flex flex-col items-center justify-center text-gray-400"
            >
                <i class="fa-solid fa-mug-hot text-6xl mb-4 text-gray-300"></i>
                <p class="text-xl font-bold">មិនមានការកុម្ម៉ង់ទេពេលនេះ</p>
                <p>អ្នកអាចសម្រាកសិនបាន! 😊</p>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="order in pendingOrders"
                    :key="order.id"
                    class="bg-white rounded-xl shadow-sm border-l-4 border-orange-500 overflow-hidden flex flex-col"
                >
                    <div
                        class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center"
                    >
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fa-solid fa-chair mr-1"></i> តុលេខ
                            {{ order.table_number }}
                        </h3>
                        <span
                            class="text-sm font-bold text-gray-500 bg-white px-2 py-1 rounded shadow-sm"
                        >
                            <i
                                class="fa-regular fa-clock text-orange-400 mr-1"
                            ></i>
                            {{ order.time }}
                        </span>
                    </div>

                    <div class="p-4 flex-1">
                        <ul class="space-y-3">
                            <li
                                v-for="(item, index) in order.items"
                                :key="index"
                                class="flex justify-between items-start border-b border-gray-50 pb-2"
                            >
                                <div>
                                    <span
                                        class="font-bold text-gray-700 text-lg"
                                        >{{ item.name }}</span
                                    >
                                    <p
                                        v-if="item.note"
                                        class="text-sm text-red-500"
                                    >
                                        <i
                                            class="fa-solid fa-triangle-exclamation mr-1"
                                        ></i
                                        >ចំណាំ: {{ item.note }}
                                    </p>
                                </div>
                                <span
                                    class="font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg text-lg"
                                    >x{{ item.quantity }}</span
                                >
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <button
                            @click="markAsDone(order.id)"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition flex justify-center items-center shadow-md"
                        >
                            <i
                                class="fa-solid fa-check-circle mr-2 text-xl"
                            ></i>
                            ធ្វើម្ហូបរួចរាល់
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Swal from "sweetalert2"; // បន្ថែមការ Import SweetAlert2 ឱ្យត្រូវនឹង Template

// គំរូទិន្នន័យសម្រាប់ការកុម្ម៉ង់នៅក្នុងផ្ទះបាយ (Pending Orders)
const pendingOrders = ref([
    {
        id: 101,
        table_number: "T-01",
        time: "10 នាទីមុន",
        items: [
            { name: "បាយឆាសាច់គោ", quantity: 2, note: "មិនយកសាច់ខ្លាញ់" },
            { name: "ស៊ុបតុមយាំ", quantity: 1, note: "" },
        ],
    },
    {
        id: 102,
        table_number: "T-03",
        time: "5 នាទីមុន",
        items: [
            { name: "មីឆាគ្រឿងសមុទ្រ", quantity: 3, note: "ធ្វើរាងហិរបន្តិច" },
            { name: "កូកាកូឡា", quantity: 3, note: "" },
        ],
    },
]);

// មុខងារពេលចុចប៊ូតុង "ធ្វើម្ហូបរួចរាល់"
const markAsDone = async (orderId) => {
    // បង្ហាញផ្ទាំង SweetAlert2 សួរបញ្ជាក់ចុងភៅ
    const result = await Swal.fire({
        title: "ម្ហូបធ្វើរួចរាល់?",
        text: "តើម្ហូបនេះបានចម្អិនរួចរាល់សម្រាប់លើកជូនភ្ញៀវហើយមែនទេ?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#10b981", // ពណ៌បៃតងបែបផ្ទះបាយ
        cancelButtonColor: "#6b7280", // ពណ៌ប្រផេះ
        confirmButtonText: "បាទ/ចាស រួចរាល់",
        cancelButtonText: "មិនទាន់ទេ",
    });

    // បើចុចយល់ព្រម
    if (result.isConfirmed) {
        // លុបការកុម្ម៉ង់នោះចេញពីអេក្រង់បង្ហាញ
        pendingOrders.value = pendingOrders.value.filter(
            (order) => order.id !== orderId,
        );

        // 📝 ថ្ងៃក្រោយបងអាចបាញ់ API ទៅប្រាប់ Waiter នៅត្រង់នេះ (ឧ. await api.post(...))

        // លោតផ្ទាំងប្រាប់ថាបានជូនដំណឹងជោគជ័យ
        Swal.fire({
            icon: "success",
            title: "រួចរាល់!",
            text: "បានជូនដំណឹងទៅអ្នករត់តុរួចរាល់។",
            showConfirmButton: false,
            timer: 1500, // បិទផ្ទាំងទៅវិញស្វ័យប្រវត្តក្នុងរយៈពេល ១.៥ វិនាទី
        });
    }
};
</script>
