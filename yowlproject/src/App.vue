<template>

  <router-view/>
   
</template>


<script>


export default {
    methods: {
        goToLogin(){
            window.location = '/login';
        }
    },
}
</script>

<style >
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 18px;
      font-family: monospace;
    font-size: 16px;
}

nav a {
 
  color: darkslategray;
      margin-left: 4rem;
    margin-right: 4rem;
}

nav a.router-link-exact-active {
  color: cadetblue;
   font-weight: bold;
}

nav a:hover{
   color: cadetblue;
   font-weight: bold;
}

.navigation{
    height: 55px;
    background: linear-gradient(180deg, rgb(150,175,185),lightgray, white);
}
</style>

<style >
    *{
        margin: 0;
        padding: 0;
    }
</style>
<style scoped>

header{
    height: 7rem;
    background-color: rgb(151,173,186);
    position: fixed;
    width: 100%;
    z-index: 100;
}

.logo{
    float: left;
    width: 12rem;
    margin-left: 2rem;
}
.search{
    width: 35rem;
    height: 2rem;
    border: none;
    padding: 3px;
    margin-top: 2rem;
}

.search-button{
    height: 2.4rem;
    width: 3rem;
    border: none;
}

.search-container{
        margin-left: 15rem;
        float: left;
}

.user-buttons{
    float:right;
    margin-top: 1.9rem;
}

.default {
  border-color: #e7e7e7;
  color: black;
}

.default:hover {
  background: #e7e7e7;
}
.btn {
    border: 2px solid;
    background-color: rgb(151,173,186);
    color: dimgrey;
    padding: 10px 19px;
    font-size: 16px;
    cursor: pointer;
    margin-right: 1rem;
    border-radius: 40px;

}

</style>