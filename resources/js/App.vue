<template>
    <div class="flex h-screen bg-gray-100 font-sans">
        <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-lg">
            <div class="p-6 text-center border-b border-gray-700">
                <h1 class="text-2xl font-bold text-yellow-400">
                    <i class="fa-solid fa-utensils mr-2"></i>Food Pro MS
                </h1>
                <p class="text-sm text-gray-400 mt-1">ISO Standard 2026</p>
            </div>

            <nav
                class="flex-1 px-4 py-6 space-y-2 overflow-y-auto text-gray-300"
            >
                <router-link
                    v-if="userRole === 'Admin'"
                    to="/dashboard"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-chart-pie w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">ផ្ទាំងគ្រប់គ្រងទូទៅ</span>
                </router-link>

                <router-link
                    v-if="['Waiter', 'Admin'].includes(userRole)"
                    to="/pos"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-cash-register w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">បញ្ជាទិញ & កុម្ម៉ង់</span>
                </router-link>

                <router-link
                    v-if="['Cook', 'Admin'].includes(userRole)"
                    to="/kitchen"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-fire-burner w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">ផ្ទះបាយ (Kitchen)</span>
                </router-link>

                <router-link
                    v-if="['Cashier', 'Admin'].includes(userRole)"
                    to="/billing"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-file-invoice-dollar w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">ទូទាត់ប្រាក់ (Billing)</span>
                </router-link>

                <router-link
                    v-if="['Cleaner', 'Admin'].includes(userRole)"
                    to="/tasks"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-broom w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">កិច្ចការអនាម័យ</span>
                </router-link>

                <router-link
                    v-if="['Admin', 'Cook'].includes(userRole)"
                    to="/inventory"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-600 hover:text-white transition group"
                    active-class="bg-blue-600 text-white shadow-md"
                >
                    <i
                        class="fa-solid fa-boxes-stacked w-6 text-lg group-hover:text-white"
                    ></i>
                    <span class="ml-2">គ្រប់គ្រងស្តុក</span>
                </router-link>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <div class="mb-3 text-sm flex items-center">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center mr-3"
                    >
                        <i class="fa-solid fa-user text-gray-300"></i>
                    </div>
                    <div>
                        <p class="font-bold text-green-400">{{ userName }}</p>
                        <p class="text-xs text-gray-400">
                            តួនាទី៖ {{ userRole }}
                        </p>
                    </div>
                </div>
                <button
                    @click="logout"
                    class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition"
                >
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> ចាកចេញ
                </button>
            </div>
        </aside>

        <main class="flex-1 p-6 overflow-hidden bg-gray-100">
            <router-view></router-view>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "./axios"; // ហៅ API ដើម្បីប្រើសម្រាប់ Logout
import Swal from "sweetalert2"; // បន្ថែម SweetAlert2 នៅទីនេះ

const router = useRouter();

// ទាញយកទិន្នន័យពី LocalStorage មកបង្ហាញ
const userName = ref("មិនស្គាល់ឈ្មោះ");
const userRole = ref("");

onMounted(() => {
    userName.value = localStorage.getItem("userName") || "មិនស្គាល់ឈ្មោះ";
    userRole.value = localStorage.getItem("userRole") || "";
});

// មុខងារចាកចេញពីប្រព័ន្ធពិតប្រាកដ
const logout = async () => {
    // ប្រើ SweetAlert2 ជំនួស confirm()
    const result = await Swal.fire({
        title: "ចាកចេញពីប្រព័ន្ធ?",
        text: "តើអ្នកពិតជាចង់ចាកចេញពីគណនីនេះមែនទេ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444", // ពណ៌ក្រហម (ឱ្យត្រូវនឹងប៊ូតុងចាកចេញ)
        cancelButtonColor: "#6b7280", // ពណ៌ប្រផេះ (បោះបង់)
        confirmButtonText: "បាទ/ចាស ចាកចេញ",
        cancelButtonText: "ត្រឡប់ក្រោយ",
    });

    // បើអ្នកប្រើចុច "បាទ/ចាស ចាកចេញ"
    if (result.isConfirmed) {
        try {
            // បាញ់ API ប្រាប់ម៉ាស៊ីនមេអោយលុប Token នេះចោល
            await api.post("/logout");
        } catch (error) {
            console.log(error);
        } finally {
            // ទោះម៉ាស៊ីនមេ Error ក៏ដោយ ក៏ត្រូវតែលុបទិន្នន័យក្នុងកុំព្យូទ័រនេះចោលដែរ
            localStorage.removeItem("token");
            localStorage.removeItem("userName");
            localStorage.removeItem("userRole");

            // រុញត្រលប់ទៅទំព័រ Login វិញ
            router.push("/login");
        }
    }
};
</script>
