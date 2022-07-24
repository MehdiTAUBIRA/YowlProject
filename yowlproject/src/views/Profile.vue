<template>
  <div class="card">
    <h1 class="card__title">My loundge</h1>
    <p class="card__subtitle">My information...</p>
    <p> {{name}} {{email}}</p>
   <!-- <img :src="user.photo"/>-->
    <div class="form-row">
      <button @click="logout()" class="button">
        Logout
      </button>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Profile',
  data() {
    return {
      name: "",
      email: []
    };
  },
  mounted: function () {
   // console.log(this.$store.state.user);

   this.name = JSON.parse(localStorage.user).name;
   this.email = JSON.parse(localStorage.user).email;
    if (localStorage.user === null) {
      this.$router.push('/');
      return ;
    }
    this.$store.dispatch('getUserInfos');
  },
  computed: {
    ...mapState({
      user: 'userInfos',
    })
  },
  methods: {
    logout: function () {
      this.$store.commit('logout');
      this.$router.push('/');
    }
  }
}
</script>

<style scoped>
</style>
