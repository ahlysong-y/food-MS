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
                    <p class="text-2xl font-bold text-gray-800">$1,250.00</p>
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
                        48
                        <span class="text-xs font-normal text-gray-400"
                            >វិក្កយបត្រ</span
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
                    <p class="text-2xl font-bold text-gray-800">12 / 20</p>
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
                        5
                        <span class="text-xs font-normal text-gray-400"
                            >មុខ</span
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
                    <div class="text-center py-10">
                        <i
                            class="fa-solid fa-file-invoice text-5xl text-gray-200 mb-4"
                        ></i>
                        <p class="text-gray-500">
                            ទិន្នន័យការបញ្ជាទិញនឹងបង្ហាញនៅទីនេះពេលភ្ជាប់ API
                            រួចរាល់។
                        </p>
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
                            class="flex justify-between items-center p-4 hover:bg-gray-50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm mr-3"
                                >
                                    1
                                </div>
                                <span class="font-medium text-gray-800"
                                    >បាយឆាសាច់គោពងទា</span
                                >
                            </div>
                            <span class="text-sm text-gray-500">24 ចាន</span>
                        </li>
                        <li
                            class="flex justify-between items-center p-4 hover:bg-gray-50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm mr-3"
                                >
                                    2
                                </div>
                                <span class="font-medium text-gray-800"
                                    >ស៊ុបតុមយាំគ្រឿងសមុទ្រ</span
                                >
                            </div>
                            <span class="text-sm text-gray-500">18 ឆ្នាំង</span>
                        </li>
                        <li
                            class="flex justify-between items-center p-4 hover:bg-gray-50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm mr-3"
                                >
                                    3
                                </div>
                                <span class="font-medium text-gray-800"
                                    >កូកាកូឡា (កំប៉ុង)</span
                                >
                            </div>
                            <span class="text-sm text-gray-500">35 កំប៉ុង</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// មុខងារបង្ហាញថ្ងៃខែឆ្នាំបច្ចុប្បន្ន
const dateOptions = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
};
const currentDate = ref(new Date().toLocaleDateString("km-KH", dateOptions));

// (ទៅថ្ងៃមុខ យើងនឹងហៅ API ដើម្បីយកតួលេខពិតប្រាកដមកដាក់ជំនួសតួលេខសាកល្បងនេះ)
</script>
