<template>
    <div
        class="fixed inset-0 flex items-center justify-center bg-gray-900 z-50"
    >
        <div
            class="bg-white p-10 rounded-2xl shadow-2xl w-96 transform transition-all"
        >
            <div class="text-center mb-8">
                <div
                    class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <i class="fa-solid fa-utensils text-3xl text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Food Pro MS</h1>
                <p class="text-gray-500 mt-2">Please login to continue</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Email</label
                    >
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Email"
                            required
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Password</label
                    >
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Password"
                            required
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-md flex justify-center items-center"
                >
                    <i
                        v-if="isLoading"
                        class="fa-solid fa-spinner fa-spin mr-2"
                    ></i>
                    {{ isLoading ? "Checking..." : "Login" }}
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    You don't have an account?
                    <router-link
                        to="/register"
                        class="text-blue-600 font-bold hover:underline"
                        >Register</router-link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../axios"; // ហៅ axios ដែលយើងបានរៀបចំ

const router = useRouter();
const form = ref({ email: "", password: "" });
const isLoading = ref(false);

const handleLogin = async () => {
    isLoading.value = true;
    try {
        // បាញ់ API ទៅកាន់ Laravel ដើម្បីឆែកមើលគណនី
        const response = await api.post("/login", form.value);

        // បើជោគជ័យ រក្សាទុក Token និងព័ត៌មានបុគ្គលិកក្នុង LocalStorage
        localStorage.setItem("token", response.data.token);
        localStorage.setItem("userName", response.data.user.name);
        localStorage.setItem("userRole", response.data.user.role);

        // បញ្ជូនទៅកាន់ទំព័រ POS រួច Refresh Page មួយដងដើម្បីអោយ Sidebar លោតត្រូវ Role
        router.push("/pos").then(() => {
            window.location.reload();
        });
    } catch (error) {
        alert("Email or password is incorrect!");
    } finally {
        isLoading.value = false;
    }
};
</script>
