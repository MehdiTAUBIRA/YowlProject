<template>
  <div class="card">
    <h1 class="card__title" v-if="mode == 'login'">Login</h1>
    <h1 class="card__title" v-else>Inscription</h1>
    <p class="card__subtitle" v-if="mode == 'login'"> No account yet? <span class="card__action" @click="switchToCreateAccount">Create an account</span></p>
    <p class="card__subtitle" v-else> Already with the community ? <span class="card__action" @click="switchToLogin()">log in</span></p>
    <div class="form-row">
      <input v-model="email" class="form-row__input" type="text" placeholder="Adresse mail"/>
    </div>
    <div class="form-row" v-if="mode == 'create'">
      <select v-model="gender" class="form-row__input">
            <option value="">-Gender-</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="others">Others</option>
      </select>
      <input v-model="name" class="form-row__input" type="text" placeholder="name"/>
      <input v-model="birthdate" class="form-row__input" type="date" placeholder="birthdate"/>
      <input v-model="Country" class="form-row__input" type="text" placeholder="country"/>
      <input v-model="city" class="form-row__input" type="text" placeholder="city"/>


      
    </div>
    <div class="form-row">
      <input v-model="password" class="form-row__input" type="password" placeholder="Mot de passe"/>
    </div>
    <div class="form-row" v-if="mode == 'login' && status == 'error_login'">
      Invalid e-mail or password!
    </div>
    <div class="form-row" v-if="mode == 'create' && status == 'error_create'">
      e-mail already exist!
    </div>
    <div class="form-row">
      <button @click="login()" class="button" :class="{'button--disabled' : !validatedFields}" v-if="mode == 'login'">
        <span v-if="status == 'loading'">Connexion on going...</span>
        <span v-else>Connexion</span>
      </button>
      <button @click="createAccount()" class="button"  :class="{'button--disabled' : !validatedFields}"  v-else>
        <span v-if="status == 'loading'">Creation on going...</span>
        <span v-else>Create an account</span>
      </button>
    </div>
  </div>
</template>

<script>

import { mapState } from 'vuex'
import axios from "axios";
import md5 from "md5";
export default {
  name: 'Login',
  data: function () {
    return {
      url: 'http://localhost:8000',
      mode: 'login',
      email: '',
      name: '',
      password: '',
      gender:'',
      birthdate: null,
      Country:'', 
      city:'',
      status:''
    }
  },
  mounted: function () {
    if (localStorage.user) {
      this.$router.push('/');
      return ;
    }
  },
  computed: {
    validatedFields: function () {
      if (this.mode == 'create') {
        if (this.email !="" && this.name != "" && this.password != "" && this.city !="" && this.birthdate != "" && this.Country != "" && this.gender != "" ) {
          return true;
        } else {
          return false;
        }
      } else {
        if (this.email != "" && this.password != "") {
          return true;
        } else {
          return false;
        }
      }
    },
  },
  methods: {
    switchToCreateAccount: function () {
      this.$router.push('/register');
    },
    switchToLogin: function () {
      this.$router.push('/login');
    },
    login: function () {
      let self = this;
      let formData = new FormData();
      formData.email = this.email;
      formData.password= this.password;
      self.status = '';
      axios({
        method: "get",
        url: this.url + "/api/users/"+this.email,
        auth: {
          username: "root",
          password: "",
        },
      })
        .then(function (response) {
            if(response.data.length === 0 || response.data[0].password !== md5(self.password)){
              self.status = 'error_login';
            }else{
              localStorage.user = JSON.stringify(response.data[0]);
              self.$router.push('/');
              
            }
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
    createAccount: function () {
      const self = this;
      var newUser = new FormData();
      newUser.set("name", this.name);
      newUser.set("gender", this.gender);
      newUser.set("email", this.email);
      newUser.set("password", md5(this.password));
      newUser.set("date_of_birth", this.birthdate);
      newUser.set("country", this.Country);
      newUser.set("town", this.city);
      newUser.set("picture", "pic");
      axios({
        method: "post",
        url: this.url + "/api/users",
        data: newUser,
        auth: {
          username: "root",
          password: "",
        },
      })
        .then(function (response) {
          if(response.data.length === 0){
             
              self.status = 'error_create';
          }else{
            localStorage.user = JSON.stringify(response.data);
             self.$router.go();
          }
          
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
  }
}
</script>

<style scoped>
  .form-row {
    display: flex;
    margin: 16px 0px;
    gap:16px;
    flex-wrap: wrap;
  }
  .form-row__input {
    padding:8px;
    border: none;
    border-radius: 8px;
    background:#f2f2f2;
    font-weight: 500;
    font-size: 16px;
    flex:1;
    min-width: 100px;
    color: black;
  }
  .form-row__input::placeholder {
    color:#aaaaaa;
  }
</style>

<style scoped>

  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;800&display=swap');
* {
  font-family: 'Poppins', sans-serif;
  margin:0;
  padding: 0;
  box-sizing: border-box;
}
#app {
  max-width: 100%;
}
body {
  background-image: white;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding:32px;
}
img {
  max-width: 100%;
  border-radius: 8px;
}
.card {
  max-width: 100%;
  width: 540px;
  background: grey;
  border-radius: 16px;
  padding:32px;
  /* margin-top: 180px;
  margin-left: 30rem; */
  margin-left: auto;
    margin-right: auto;
    margin-top: 10rem;
  
}
.card__title {
  text-align:center;
  font-weight: 800;
}
.card__subtitle {
  text-align: center;
  color:rgb(63, 61, 61);
  font-weight: 500;
}
  .button {
    background: #063f6e;
    color:white;
    border-radius: 8px;
    font-weight: 800;
    font-size: 15px;
    border: none;
    width: 100%;
    padding: 16px;
    transition: .4s background-color;
  }
  .card__action {
    color:#124f81;
    text-decoration: underline;
  }
  .card__action:hover {
    cursor:pointer;
  }
  .button:hover {
    cursor:pointer;
    background: #1976D2;
  }
  .button--disabled {
    background:#cecece;
    color:#ececec
  }
  .button--disabled:hover {
    cursor:not-allowed;
    background:#cecece;
  }


</style>