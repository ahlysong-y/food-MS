<template>
    <div
        class="fixed inset-0 flex items-center justify-center bg-gray-900 z-50 overflow-y-auto py-10"
    >
        <div
            class="bg-white p-10 rounded-2xl shadow-2xl w-[450px] transform transition-all"
        >
            <div class="text-center mb-8">
                <div
                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <i
                        class="fa-solid fa-user-plus text-3xl text-green-600"
                    ></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Register</h1>
                <p class="text-gray-500 mt-2">
                    Fill in the information below to register a new account.
                </p>
            </div>

            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Username</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Username"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Email"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Password</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Password"
                        required
                        minlength="6"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1"
                        >Role</label
                    >
                    <select
                        v-model="form.role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white"
                    >
                        <option value="Admin">Admin</option>
                        <option value="Waiter">Waiter</option>
                        <option value="Cook">Cook</option>
                        <option value="Cashier">Cashier</option>
                        <option value="Cleaner">Cleaner</option>
                    </select>
                </div>

                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition shadow-md mt-6 flex justify-center items-center"
                >
                    <i
                        v-if="isLoading"
                        class="fa-solid fa-spinner fa-spin mr-2"
                    ></i>
                    {{ isLoading ? "Creating account..." : "Register" }}
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <router-link
                        to="/login"
                        class="text-blue-600 font-bold hover:underline"
                        >Login</router-link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../axios";

const router = useRouter();
const isLoading = ref(false);

const form = ref({
    name: "",
    email: "",
    password: "",
    role: "Waiter", // កំណត់លំនាំដើម
});

const handleRegister = async () => {
    isLoading.value = true;
    try {
        const response = await api.post("/register", form.value);

        // ពេលចុះឈ្មោះជោគជ័យ យើងរក្សាទុក Token និងអោយចូលប្រព័ន្ធតែម្តង
        localStorage.setItem("token", response.data.token);
        localStorage.setItem("userName", response.data.user.name);
        localStorage.setItem("userRole", response.data.user.role);

        alert("✅ " + response.data.message);

        router.push("/pos").then(() => {
            window.location.reload();
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            alert("❌ អុីមែលនេះមានគេប្រើរួចហើយ ឬទិន្នន័យមិនត្រឹមត្រូវ!");
        } else {
            alert("❌ មានបញ្ហាក្នុងការភ្ជាប់ទៅកាន់ Server!");
        }
    } finally {
        isLoading.value = false;
    }
};
</script>
