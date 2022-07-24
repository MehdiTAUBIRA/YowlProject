import { createRouter, createWebHistory } from "vue-router";
import Accueil from "../views/Accueil.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Sport from "../views/Sport.vue";
import Films from "../views/Films.vue";
import Mangas from "../views/Mangas.vue";
import Actualite from "../views/Actualite.vue";
import Mode from "../views/Mode.vue";
import Voyages from "../views/Voyages.vue";
import Profile from "../views/Profile.vue";
import adminPosts from "../views/adminPosts.vue";
const routes = [
  {
    path: "/",
    name: "all",
    component: Accueil,
  },
  {
    path: "/login",
    name: "login",
    component: Login,
  },
  {
    path: "/register",
    name: "register",
    component: Register,
  },
  {
    path: "/profile",
    name: "profile",
    component: Profile,
  },
  {
    path: "/sport",
    name: "sport",
    component: Sport,
  },
  {
    path: "/films",
    name: "films",
    component: Films,
  },
  {
    path: "/mangas",
    name: "mangas",
    component: Mangas,
  },
  {
    path: "/actualite",
    name: "actualite",
    component: Actualite,
  },
  {
    path: "/mode",
    name: "mode",
    component: Mode,
  },
  {
    path: "/voyages",
    name: "voyages",
    component: Voyages,
  },
  {
    path: "/adminPosts",
    name: "adminPosts",
    component: adminPosts,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
