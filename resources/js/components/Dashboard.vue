<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fa-solid fa-chart-line text-blue-600 mr-2"></i>
                ផ្ទាំងគ្រប់គ្រងទូទៅ
            </h2>
            <div
                class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100"
            >
                <i class="fa-regular fa-calendar mr-2"></i> ថ្ងៃនេះ:
                {{ currentDate }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition hover:shadow-md"
            >
                <div
                    class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-2xl mr-4"
                >
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">
                        ចំណូលថ្ងៃនេះ
                    </p>
                    <p class="text-2xl font-bold text-gray-800">
                        ${{ dashboardStats.revenue.toFixed(2) }}
                    </p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition hover:shadow-md"
            >
                <div
                    class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl mr-4"
                >
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">
                        ការកុម្ម៉ង់សរុប
                    </p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ dashboardStats.totalOrders }}
                        <span class="text-xs font-normal text-gray-400">
                            វិក្កយបត្រ</span
                        >
                    </p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition hover:shadow-md"
            >
                <div
                    class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 text-2xl mr-4"
                >
                    <i class="fa-solid fa-chair"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">
                        តុមានភ្ញៀវ
                    </p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ dashboardStats.occupiedTables }} /
                        {{ dashboardStats.totalTables }}
                    </p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition hover:shadow-md"
            >
                <div
                    class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-600 text-2xl mr-4"
                >
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">
                        ស្តុកជិតអស់
                    </p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ dashboardStats.lowStockCount }}
                        <span class="text-xs font-normal text-gray-400">
                            មុខ</span
                        >
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            >
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50"
                >
                    <h3 class="text-lg font-bold text-gray-800">
                        ការបញ្ជាទិញថ្មីៗ (Recent Orders)
                    </h3>
                    <router-link
                        to="/billing"
                        class="text-blue-600 hover:underline text-sm font-medium"
                        >មើលទាំងអស់</router-link
                    >
                </div>
                <div class="p-6">
                    <div
                        v-if="recentOrders.length === 0"
                        class="text-center py-10"
                    >
                        <i
                            class="fa-solid fa-file-invoice text-5xl text-gray-200 mb-4"
                        ></i>
                        <p class="text-gray-500">
                            មិនទាន់មានទិន្នន័យការបញ្ជាទិញនៅឡើយទេ។
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="text-sm font-bold text-gray-500 border-b border-gray-100 pb-2"
                                >
                                    <th class="pb-3">លេខវិក្កយបត្រ</th>
                                    <th class="pb-3">តុលេខ</th>
                                    <th class="pb-3">ទឹកប្រាក់សរុប</th>
                                    <th class="pb-3 text-right">ស្ថានភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr
                                    v-for="order in recentOrders"
                                    :key="order.id"
                                    class="text-sm text-gray-700"
                                >
                                    <td class="py-3 font-medium text-blue-600">
                                        #INV-{{ order.id }}
                                    </td>
                                    <td class="py-3 font-bold">
                                        តុ {{ order.table_number }}
                                    </td>
                                    <td class="py-3 font-bold text-gray-800">
                                        ${{ order.total_amount.toFixed(2) }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <span
                                            :class="[
                                                'px-2.5 py-1 rounded-full text-xs font-bold',
                                                order.statusClass,
                                            ]"
                                        >
                                            {{ order.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            >
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">
                        លក់ដាច់ជាងគេ (Top Items)
                    </h3>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        <li
                            v-for="(item, index) in topItems"
                            :key="item.id"
                            class="flex justify-between items-center p-4 hover:bg-gray-50"
                        >
                            <div class="flex items-center">
                                <div
                                    :class="[
                                        'w-8 h-8 rounded flex items-center justify-center font-bold text-sm mr-3',
                                        item.iconClass,
                                    ]"
                                >
                                    {{ index + 1 }}
                                </div>
                                <span class="font-medium text-gray-800">{{
                                    item.name
                                }}</span>
                            </div>
                            <span
                                class="text-sm text-gray-500 font-bold bg-gray-100 px-2.5 py-1 rounded-lg"
                                >{{ item.sales }}</span
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// ១. មុខងារបង្ហាញថ្ងៃខែឆ្នាំបច្ចុប្បន្នជាភាសាខ្មែរ
const dateOptions = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
};
const currentDate = ref(new Date().toLocaleDateString("km-KH", dateOptions));

// ២. ទិន្នន័យសង្ខេបនៅលើកាតទាំង ៤ (ទាញចេញជា Refs ដើម្បីងាយស្រួលកែប្រែ)
const dashboardStats = ref({
    revenue: 1250.0,
    totalOrders: 48,
    occupiedTables: 12,
    totalTables: 20,
    lowStockCount: 5,
});

// ៣. ទិន្នន័យសាកល្បងសម្រាប់ការបញ្ជាទិញថ្មីៗ (ធ្វើឱ្យ Dashboard មានជីវិតរស់រវើក)
const recentOrders = ref([
    {
        id: 101,
        table_number: "T-01",
        total_amount: 10.5,
        status: "រង់ចាំគិតប្រាក់",
        statusClass: "bg-amber-100 text-amber-700",
    },
    {
        id: 102,
        table_number: "T-03",
        total_amount: 12.0,
        status: "កំពុងធ្វើ",
        statusClass: "bg-blue-100 text-blue-700",
    },
    {
        id: 105,
        table_number: "T-02",
        total_amount: 25.5,
        status: "បានទូទាត់",
        statusClass: "bg-green-100 text-green-700",
    },
]);

// ៤. ទិន្នន័យសាកល្បងសម្រាប់មុខម្ហូបលក់ដាច់ជាងគេ
const topItems = ref([
    {
        id: 1,
        name: "បាយឆាសាច់គោពងទា",
        sales: "24 ចាន",
        iconClass: "bg-orange-100 text-orange-600",
    },
    {
        id: 2,
        name: "ស៊ុបតុមយាំគ្រឿងសមុទ្រ",
        sales: "18 ឆ្នាំង",
        iconClass: "bg-blue-100 text-blue-600",
    },
    {
        id: 3,
        name: "កូកាកូឡា (កំប៉ុង)",
        sales: "35 កំប៉ុង",
        iconClass: "bg-gray-100 text-gray-600",
    },
]);
</script>
