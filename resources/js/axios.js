import axios from 'axios';

// បង្កើត Instance របស់ Axios
const api = axios.create({
    // ប្រសិនបើអ្នកប្រើ Laragon លីងអាចជា http://food-ms.test/api 
    // ឬតាមលំនាំដើមគឺ http://localhost:8000/api
    baseURL: 'http://localhost:8000/api', 
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// រៀបចំអោយវាភ្ជាប់ Token ដោយស្វ័យប្រវត្តិរាល់ពេលបាញ់ API ម្តងៗ
api.interceptors.request.use(config => {
    // ទាញយក Token ពី LocalStorage (ដែលបានរក្សាទុកពេល Login)
    const token = localStorage.getItem('token');
    
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

export default api;