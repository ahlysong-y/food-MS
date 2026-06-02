import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router"; // ហៅ router ដែលទើបបង្កើតមកប្រើ

const app = createApp(App);

app.use(router); // ប្រាប់ Vue អោយប្រើប្រាស់ Router នេះ
app.mount("#app");
