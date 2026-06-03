<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fa-solid fa-broom text-blue-600 mr-2"></i>
                កិច្ចការអនាម័យប្រចាំថ្ងៃ
            </h2>
            <button
                @click="fetchTasks"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition"
            >
                <i
                    class="fa-solid fa-rotate-right"
                    :class="{ 'fa-spin': isLoading }"
                ></i>
                ផ្ទុកឡើងវិញ
            </button>
        </div>

        <div v-if="isLoading" class="text-center py-10">
            <i class="fa-solid fa-spinner fa-spin text-4xl text-blue-500"></i>
            <p class="text-gray-500 mt-3">កំពុងទាញយកទិន្នន័យ...</p>
        </div>

        <div
            v-else-if="tasks.length === 0"
            class="bg-green-50 border border-green-200 rounded-xl p-10 text-center"
        >
            <div
                class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <i class="fa-solid fa-check text-4xl text-green-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">អស្ចារ្យណាស់!</h3>
            <p class="text-gray-600 mt-2">
                មិនមានកិច្ចការអនាម័យដែលត្រូវធ្វើនៅពេលនេះទេ។
            </p>
        </div>

        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="task in tasks"
                :key="task.id"
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition"
            >
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full"
                        >
                            {{ task.task_type || "ទូទៅ" }}
                        </span>
                        <span class="text-xs text-gray-500"
                            ><i class="fa-regular fa-clock"></i>
                            {{ formatDate(task.created_at) }}</span
                        >
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        {{ task.description }}
                    </h3>

                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="fa-solid fa-map-pin mr-2 text-gray-400"></i>
                        តំបន់៖
                        <span class="font-semibold ml-1">{{
                            task.area || "មិនបញ្ជាក់"
                        }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                    <button
                        @click="completeTask(task.id)"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition flex justify-center items-center"
                    >
                        <i class="fa-solid fa-check mr-2"></i> ធីកថាធ្វើរួចរាល់
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import api from "../axios";
// បន្ថែម SweetAlert2 នៅទីនេះ
import Swal from "sweetalert2";

const tasks = ref([]);
const isLoading = ref(true);

// មុខងារទាញយកទិន្នន័យពី API
const fetchTasks = async () => {
    isLoading.value = true;
    try {
        const response = await api.get("/tasks/pending");
        tasks.value = response.data.tasks || [];
    } catch (error) {
        console.error("Error fetching tasks:", error);
        // បង្កើតទិន្នន័យសាកល្បង (Mock Data) ក្នុងករណី API មិនទាន់ដំណើរការ
        tasks.value = [
            {
                id: 1,
                description: "ជូតសម្អាតតុលេខ ០១ ដល់ ០៥",
                area: "សាលខាងមុខ",
                task_type: "អនាម័យតុ",
                created_at: new Date().toISOString(),
            },
            {
                id: 2,
                description: "លាងសម្អាតបន្ទប់ទឹក",
                area: "បន្ទប់ទឹក",
                task_type: "អនាម័យទូទៅ",
                created_at: new Date().toISOString(),
            },
            {
                id: 3,
                description: "បោសសំរាមក្នុងផ្ទះបាយ",
                area: "ផ្ទះបាយ",
                task_type: "អនាម័យផ្ទះបាយ",
                created_at: new Date().toISOString(),
            },
        ];
    } finally {
        isLoading.value = false;
    }
};

// មុខងារពេលចុចប៊ូតុង "ធ្វើរួចរាល់"
const completeTask = async (id) => {
    // ប្រើ SweetAlert2 ជំនួស confirm()
    const result = await Swal.fire({
        title: "តើអ្នកប្រាកដទេ?",
        text: "តើអ្នកពិតជាបានបញ្ចប់កិច្ចការនេះហើយមែនទេ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6", // ពណ៌ខៀវ Tailwind
        cancelButtonColor: "#ef4444", // ពណ៌ក្រហម Tailwind
        confirmButtonText: "បាទ/ចាស ខ្ញុំប្រាកដ!",
        cancelButtonText: "បោះបង់",
    });

    // បើចុច "បោះបង់" ឱ្យឈប់ដំណើរការ
    if (!result.isConfirmed) return;

    try {
        // ព្យាយាមបាញ់ API ប្រាប់ Backend ថាធ្វើរួចហើយ
        await api.post(`/tasks/${id}/complete`);
        // លុបកិច្ចការនោះចេញពីអេក្រង់
        tasks.value = tasks.value.filter((task) => task.id !== id);

        // ប្រើ SweetAlert2 ជំនួស alert() ពេលជោគជ័យ
        Swal.fire({
            icon: "success",
            title: "ជោគជ័យ!",
            text: "កិច្ចការត្រូវបានបញ្ចប់។",
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#3b82f6",
        });
    } catch (error) {
        // ក្នុងករណីសាកល្បង (Mock Data) យើងគ្រាន់តែលុបវាចេញពីអេក្រង់
        tasks.value = tasks.value.filter((task) => task.id !== id);

        // លោតប្រាប់ជោគជ័យដូចគ្នាសម្រាប់ Mock Data
        Swal.fire({
            icon: "success",
            title: "ជោគជ័យ!",
            text: "កិច្ចការត្រូវបានបញ្ចប់ (Mock Data)។",
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#3b82f6",
        });
    }
};

// មុខងារបំប្លែងថ្ងៃខែ
const formatDate = (dateString) => {
    const options = { hour: "2-digit", minute: "2-digit" };
    return new Date(dateString).toLocaleTimeString("km-KH", options);
};

// ហៅទិន្នន័យមកភ្លាមៗពេលបើកទំព័រនេះ
onMounted(() => {
    fetchTasks();
});
</script>
