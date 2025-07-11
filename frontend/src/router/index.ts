import { createRouter, createWebHistory } from 'vue-router'
import HomePage from "../pages/HomePage.vue";
import ClientsPage from "../pages/ClientsPage.vue";
import ClientDetailPage from "../pages/ClientDetailPage.vue";
import LoginPage from "../pages/LoginPage.vue";
import SalesPage from "../pages/SalesPage.vue";
import InventoryPage from "../pages/InventoryPage.vue";

const routes = [
    { path: '/', component: HomePage },
    { path: '/sales', component: SalesPage },
    { path: '/inventory', component: InventoryPage },
    { path: '/clients', component: ClientsPage },
    { path: '/clients/:id', component: ClientDetailPage, props: true },
    { path: '/login', component: LoginPage },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router