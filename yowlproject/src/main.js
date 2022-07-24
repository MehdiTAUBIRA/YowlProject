import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
// import Chartkick from 'vue-chartkick'
// import chart from 'chart.js'
// import vueChartkick from 'vue-chartkick'
// import VueGoogleCharts from 'vue-google-charts'
import './assets/tailwind.css'

// vueChartkick.use(Chartkick.use(chart))
// Vue.use(VueGoogleCharts)

createApp(App).use(store).use(router).mount('#app')
