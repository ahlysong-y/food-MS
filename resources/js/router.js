import { createRouter, createWebHistory } from "vue-router";

// ទាញយក Components
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Pos from "./components/Pos.vue";
import Kitchen from "./components/Kitchen.vue";
import Billing from "./components/Billing.vue";
import Inventory from "./components/Inventory.vue";
import TasksPage from "./components/Tasks.vue";
import DashboardPage from "./components/Dashboard.vue"; // បានបន្ថែមបន្ទាត់នេះ

// (បានលុបអថេរ const Dashboard កំពុងសាងសង់ចោល)

const routes = [
    { path: "/login", component: Login },
    { path: "/register", component: Register },
    { path: "/", redirect: "/pos" },
    {
        path: "/dashboard",
        component: DashboardPage,
        meta: { requiresAuth: true },
    }, // បានប្តូរទៅ DashboardPage
    { path: "/pos", component: Pos, meta: { requiresAuth: true } },
    { path: "/kitchen", component: Kitchen, meta: { requiresAuth: true } },
    { path: "/billing", component: Billing, meta: { requiresAuth: true } },
    { path: "/tasks", component: TasksPage, meta: { requiresAuth: true } },
    { path: "/inventory", component: Inventory, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ប្រព័ន្ធត្រួតពិនិត្យមុនពេលចូលទំព័រនីមួយៗ (Navigation Guard ជំនាន់ថ្មី)
router.beforeEach((to, from) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth && !token) {
        // បើទំព័រនោះត្រូវការ Login តែគាត់អត់មាន Token ទេ រុញទៅទំព័រ Login
        return "/login";
    } else if ((to.path === "/login" || to.path === "/register") && token) {
        // បើគាត់ Login រួចហើយ តែចង់ទៅទំព័រ Login ឬ Register ម្តងទៀត អោយរត់ទៅ POS វិញ
        return "/pos";
    }

    // បើគ្មានបញ្ហាទេ អោយទៅមុខធម្មតា (មិនបាច់ return អ្វីទេ)
});

export default router;
