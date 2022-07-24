<template>
  <header class="header">
    <img src="../assets/logo.png" alt="logo" class="logo" />
    <div class="user-buttons" v-if= "userId !== ''">
      <button class="btn default buttonAdd" @click="homePage">
        <span class="spanAdd">Back to home page</span>
      </button>
      <button class="btn default" @click="logout">Logout</button>
    </div>
  </header>
  <div id="app">
    <div class="container">
      <div class="chart-container">
        <div class="pie">
          <div style="display: flex"><PieChart :pieData="pieData" /></div>
          <div style="margin-top: 100px"><h3>Utilisateurs par genre</h3></div>
        </div>

        <div class="planet">
          <canvas id="planet-chart"></canvas>
          <div style="margin-top: 33px"><h3>Nombre de posts par mois</h3></div>
        </div>
      </div>
      <div>
        <h1 class="gestion">Gestion des utilisateurs:</h1>
        <table>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Action</th>
          </tr>
          <tr v-for="(user, index) in allUsers" :key="index">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.gender }}</td>
            <td>
              <button @click="deleteUser(user)" class="buttonDelete">
                &#10060;
              </button>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import PieChart from "../components/PieChart";
import Chart from "chart.js";
import moment from "moment";
export default {
  data: () => {
    return {
      pieData: [],
      url: "http://localhost:8000",
      postsPerMonths: null,
      allUsers: [],
    };
  },
  methods: {
    deleteUser(user) {
      let yes = confirm("Confirm delete?");
      if (yes) {
        axios.delete(this.url + "/api/users/" + user.id).then((response) => {
          this.allUsers = this.allUsers.filter((u) => u.id !== user.id);
        });
      }
    },
    logout() {
      localStorage.removeItem("user");
      this.$router.push("/");
    },
    homePage() {
      this.$router.push("/");
    },
  },
  components: {
    PieChart,
  },
  mounted() {
    if (JSON.parse(localStorage.user).email !== "admin@gmail.com") {
      this.$router.push("/");
    } else {
      axios.get(this.url + "/api/users").then((response) => {
        this.allUsers = response.data.filter(
          (user) => user.email !== "admin@gmail.com"
        );
        let female = response.data.filter((user) => user.gender === "female");
        let male = response.data.filter((user) => user.gender === "male");
        let others = response.data.filter((user) => user.gender === "others");
        this.pieData = [
          {
            color: "#0B6487",
            value: parseFloat(
              ((female.length * 100) / response.data.length).toFixed(2)
            ),
          },
          {
            color: "#9D1F37",
            value: parseFloat(
              ((male.length * 100) / response.data.length).toFixed(2)
            ),
          },
          {
            color: "#F6931C",
            value: parseFloat(
              ((others.length * 100) / response.data.length).toFixed(2)
            ),
          },
        ];
      });

      axios.get(this.url + "/api/posts").then((response) => {
        let nbParMois = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        response.data.forEach((post) => {
          let date = moment(String(post.created_at)).format("DD/MM/YYYY hh:mm");
          let mois = parseInt(date.substring(3, 5));
          nbParMois[mois - 1] += 1;
        });
        this.postsPerMonths = {
          type: "line",
          data: {
            labels: [
              "Janvier",
              "Février",
              "Mars",
              "Avril",
              "Mai",
              "Juin",
              "Juillet",
              "Aout",
              "Septembre",
              "Octobre",
              "Novembre",
              "Décembre",
            ],
            datasets: [
              {
                label: "Nombres de posts par mois",
                data: nbParMois,
                backgroundColor: "rgba(71, 183,132,.5)",
                borderColor: "#47b784",
                borderWidth: 3,
              },
            ],
          },
          options: {
            responsive: true,
            lineTension: 1,
            scales: {
              yAxes: [
                {
                  ticks: {
                    beginAtZero: true,
                    padding: 25,
                  },
                },
              ],
            },
          },
        };
        const ctx = document.getElementById("planet-chart");
        new Chart(ctx, this.postsPerMonths);
      });
    }
  },
};
</script>

<style lang="scss" scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  font-size: 1rem;
  font-family: Tahoma;
  background-color: #333;
  color: #fefefe;
}
#app {
  padding-top: 10rem;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
  width: 100%;
}

.header {
  height: 6rem;
  background-color: rgb(151, 173, 186);
  position: fixed;
  width: 100%;
  z-index: 100;
}

.user-buttons {
  float: right;
  margin-top: 1.9rem;
}

.btn {
  border: 2px solid;
  background-color: rgb(151, 173, 186);
  color: dimgrey;
  padding: 10px 19px;
  font-size: 11px;
  font-weight: bold;
  cursor: pointer;
  margin-right: 1rem;
  border-radius: 40px;
}

.default {
  border-color: #e7e7e7;
  color: black;
}

.default:hover {
  background: #e7e7e7;
}

.buttonAdd {
  border: none;
  text-align: center;
  cursor: pointer;
  text-transform: uppercase;
  outline: none;
  overflow: hidden;
  position: relative;
  color: #eeeeee;
  font-weight: 600;
  font-size: 13px;
  background-color: black;
  border-radius: 200px;
}
.buttonAdd span {
  position: relative;
  z-index: 1;
}
.buttonAdd:after {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  height: 470%;
  width: 188%;
  background: linear-gradient(110deg, darkcyan, transparent);
  -webkit-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-transform: translateX(-100%) translateY(-25%) rotate(45deg);
  transform: translateX(-100%) translateY(-25%) rotate(45deg);
}
.buttonAdd:hover:after {
  -webkit-transform: translateX(-9%) translateY(-25%) rotate(45deg);
  transform: translateX(-9%) translateY(-25%) rotate(45deg);
}
.spanAdd {
  font-family: serif;
}

.logo {
  float: left;
  width: 10rem;
  margin-left: 2rem;
}

table {
  font-family: Georgia, serif;
  border: 6px solid #24943a;
  background-color: #d4eed1;
  width: 70%;
  margin: auto;
  text-align: center;
  margin-bottom: 20px;
}
table td,
table th {
  border: 1px solid #24943a;
  padding: 3px 2px;
}
table tbody td {
  font-size: 13px;
}
table thead {
  background: #24943a;
  background: -moz-linear-gradient(top, #5baf6b 0%, #3a9e4d 66%, #24943a 100%);
  background: -webkit-linear-gradient(
    top,
    #5baf6b 0%,
    #3a9e4d 66%,
    #24943a 100%
  );
  background: linear-gradient(to bottom, #5baf6b 0%, #3a9e4d 66%, #24943a 100%);
  border-bottom: 0px solid #444444;
}
table.greenTable thead th {
  font-size: 19px;
  font-weight: bold;
  color: #f0f0f0;
  text-align: left;
  border-left: 2px solid #24943a;
}
table.greenTable thead th:first-child {
  border-left: none;
}

table.greenTable tfoot td {
  font-size: 13px;
}
table.greenTable tfoot .links {
  text-align: right;
}
table.greenTable tfoot .links a {
  display: inline-block;
  background: #ffffff;
  color: #24943a;
  padding: 2px 8px;
  border-radius: 5px;
}

.buttonDelete {
  background: none;
  border: none;
  padding: 0px;
  font: inherit;
  cursor: pointer;
  outline: inherit;
}

.gestion {
  margin-bottom: 20px;
  text-align: left;
  margin-left: 14rem;
}

.container {
  display: grid;
  width: 100%;
}
.chart-container {
  float: left;
  width: 100%;
}
.pie {
  float: left;
  width: 50%;
}
.planet {
  width: 50%;
  height: 530px;
  float: left;
}
</style>