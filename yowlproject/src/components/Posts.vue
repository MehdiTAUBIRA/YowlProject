<template>
  <div>
    <head>
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
      />
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet"
      />
    </head>
    <header class="header">
      <div style="display: grid">
        <div>
          <img src="../assets/logo.png" alt="logo" class="logo" />
          <div class="search-container">
            <input
              type="text"
              placeholder="Search.."
              v-model="search"
              class="search"
              @keyup.enter="searchText"
            />
            <button class="search-button" @click="searchText">
              <i class="fa fa-search"></i>
            </button>
          </div>
          <div class="user-buttons" v-if="userId === ''">
            <button class="btn default buttonAdd" @click="addPost">
              <span class="spanAdd">ADD NEW POST</span>
            </button>
            <button class="btn default" @click="goToLogin">Login</button>
            <button class="btn default" @click="goToRegister">Register</button>
          </div>
          <div class="user-buttons" v-if="userId !== ''">
            <button class="btn default buttonAdd" @click="addPost">
              <span class="spanAdd">ADD NEW POST</span>
            </button>
            <button class="btn default" @click="logout">Logout</button>
          </div>
        </div>
        <div class="navigation">
          <nav>
            <router-link to="/">All</router-link>
            <router-link to="/sport">Sport</router-link>
            <router-link to="/films">Films</router-link>
            <router-link to="/mangas">Mangas</router-link>
            <router-link to="/actualite">Actualité</router-link>
            <router-link to="/mode">Mode</router-link>
            <router-link to="/voyages">Voyages</router-link>
          </nav>
        </div>
      </div>
    </header>
    <div class="modal-backdrop" v-if="viewPostPopup">
      <div class="view-container">
        <header class="modal-header">
          <button type="button" class="btn-close" @click="close">x</button>
        </header>
        <b class="bclass">{{ postToView.title }}</b
        ><br />
        <div
          v-if="postToView.picture !== null && postToView.picture !== undefined"
        >
          <img
            :src="getImgUrl(postToView.picture)"
            v-bind:alt="postToView.picture"
            class="image2"
          />
        </div>
        <br />
        <div class="desc">
          {{ postToView.description }}
        </div>

        <div class="buttons">
          <button
            v-bind:class="getLikeClass(postToView)"
            @click="like(postToView)"
          >
            &#128077;&#127995; {{ postToView.nb_likes }}
          </button>
          <button
            v-bind:class="getDislikeClass(postToView)"
            @click="dislike(postToView)"
          >
            &#128078;&#127995; {{ postToView.nb_dislikes }}
          </button>
          <p class="nb-comment" @click="showCommentsFonc">
            {{ postToView.comments.length }} commentaire(s)
          </p>
        </div>
        <input
          style="padding: 10px"
          placeholder="Ajoutez un commentaire ..."
          v-model="commentaire"
          class="new-comment"
          @keyup.enter="createComment(postToView)"
        />
        <div v-if="showComments">
          <span style="width: 500px">
            <div v-for="(comment, index) in postToView.comments" :key="index">
              <p class="existing-comment">{{ comment.body }}</p>
              <p class="date">
                <b class="nameComment">{{ comment.username }}</b> -
                {{ formatDate(comment.created_at) }}
              </p>
            </div>
          </span>
        </div>
      </div>
    </div>
    <div class="modal-backdrop" v-if="addPopup" id="uploadApp">
      <form method="POST" class="add-popup">
        <header class="modal-header">
          <button type="button" class="btn-close" @click="close">x</button>
        </header>
        <div class="add">
          <div class="feuille">
            <input
              type="text"
              id="title"
              name="title"
              class="title"
              placeholder="Title..."
              v-model="newTitle"
            /><br />
            <select v-model="newCategory">
              <option value="">--Category--</option>
              <option value="sport">Sport</option>
              <option value="films">Films</option>
              <option value="mangas">Mangas</option>
              <option value="actualite">Actualité</option>
              <option value="mode">Mode</option>
              <option value="voyages">Voyages</option></select
            ><br />
            <textarea
              name="text"
              id="contenu"
              cols="70"
              rows="10"
              class="contenu"
              placeholder="Description..."
              v-model="newDescription"
            ></textarea
            ><br />
            <input type="file" @change="onFileChanged" />
            <br />
            <div class="bout">
              <button type="button" class="sauver" @click="save">
                <span>Save</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div style="padding-top: 12rem">
      <div>
        <Swiper
          :slides-per-view="4"
          :space-between="50"
          :modules="[Navigation, Pagination, A11y]"
          navigation
          :pagination="{ clickable: true }"
          grab-cursor
        >
          <SwiperSlide
            v-for="(post, index) in plusRecents"
            :key="index"
            @click="viewPost(post)"
            class="slide"
          >
            <div v-if="post.picture !== null && post.picture !== undefined">
              <img
                :src="getImgUrl(post.picture)"
                v-bind:alt="post.picture"
                class="image"
              />
            </div>
            <div
              v-if="post.picture === null || post.picture === undefined"
              class="description-container"
            >
              <p class="description">{{ post.description }}</p>
            </div>
            <div class="posttitle">{{ post.title }}</div>
          </SwiperSlide>
        </Swiper>
      </div>
      <div class="right-container slide">
        <h1>Most liked:</h1>
        <br />
        <br />
        <br />
        <b>{{ plusLike.title }}</b>
        <div
          class="divImg"
          v-if="plusLike.picture !== undefined && plusLike.picture !== null"
          @click="viewPost(plusLike)"
        >
          <img
            :src="getImgUrl(plusLike.picture)"
            v-bind:alt="plusLike.picture"
            class="imageMost"
          />
        </div>

        <div class="description">
          <p @click="viewPost(plusLike)">{{ plusLike.description }}</p>
        </div>
        <div class="buttons">
          <button v-bind:class="getLikeClass(plusLike)" @click="like(plusLike)">
            &#128077;&#127995; {{ plusLike.nb_likes }}
          </button>
          <button
            v-bind:class="getDislikeClass(plusLike)"
            @click="dislike(plusLike)"
          >
            &#128078;&#127995; {{ plusLike.nb_dislikes }}
          </button>
        </div>
      </div>
      <br /><br />

      <div style="width: 75%" class="grid">
        <div class="container">
          <div
            v-for="(post, index) in posts"
            :key="index"
            v-bind:id="post.id"
            class="divPost"
          >
            <div>
              <button
                v-if="userId !== '' && post.user_id === parseInt(userId)"
                @click="deletePost(post)"
                class="buttonDelete"
              >
                &#10060;
              </button>
              <div class="b">
                <b
                  ><b class="bname">{{ post.username }}:</b> {{ post.title }}</b
                >
              </div>

              <div v-if="post.picture !== null && post.picture !== undefined">
                <img
                  :src="getImgUrl(post.picture)"
                  v-bind:alt="post.picture"
                  class="image2 slide"
                  @click="viewPost(post)"
                />
              </div>
              <div class="descrip slide" @click="viewPost(post)">
                <p>{{ post.description }}</p>
              </div>

              <div class="buttons">
                <button v-bind:class="getLikeClass(post)" @click="like(post)">
                  &#128077;&#127995; {{ post.nb_likes }}
                </button>
                <button
                  v-bind:class="getDislikeClass(post)"
                  @click="dislike(post)"
                >
                  &#128078;&#127995; {{ post.nb_dislikes }}
                </button>
                <p class="nb-comment" @click="showCommentsFoncPopup(post)">
                  {{ post.comments.length }} commentaire(s)
                </p>
              </div>
              <div class="votess">
                Nombre de votes : {{ post.nb_votes }} <br />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Navigation, Pagination, A11y } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";

import "swiper/css";
import "swiper/css/bundle";
</script>

<script>
import axios from "axios";
import moment from "moment";

export default {
  name: "Posts",
  props: {
    category: String,
  },
  components: {
    Swiper,
    SwiperSlide,
    Navigation,
    Pagination,
    A11y,
  },
  data() {
    return {
      url: "http://localhost:8000/",
      userId: "",
      posts: [],
      plusLike: {},
      plusRecents: [],
      addPopup: false,
      newTitle: "",
      newDescription: "",
      newCategory: "",
      newFile: "",
      search: "",
      allPosts: [],
      viewPostPopup: false,
      postToView: null,
      allComments: [],
      allUsers: [],
      currentComments: [],
      showComments: false,
      commentaire: "",
    };
  },
  methods: {
    viewPost(post) {
      this.viewPostPopup = true;
      this.postToView = post;
    },
    close() {
      this.addPopup = false;
      this.viewPostPopup = false;
      this.showComments = false;
    },
    goToLogin() {
      window.location = "/login";
    },
    goToRegister() {
      window.location = "/register";
    },
    logout() {
      localStorage.removeItem("user");
      this.$router.go();
    },
    getImgUrl(pet) {
      var images = require.context(
        "../../../yowl-app/storage/app/public",
        false,
        /\.png$/
      );
      return images("./" + pet);
    },
    searchText() {
      if (this.search === "") {
        this.$router.go();
      } else
        this.posts = this.allPosts.filter((post) =>
          post.title.includes(this.search)
        );
    },
    like(post) {
      if (this.userId === "") {
        this.$router.push("/login");
        return;
      }
      if (post.like_users?.includes("," + this.userId + ",")) {
        post.like_users = post.like_users?.replace("," + this.userId + ",", "");
        post.nb_likes -= 1;
      } else {
        post.like_users = post.like_users + "," + this.userId + ",";
        post.nb_likes += 1;
        if (post.dislike_users?.includes("," + this.userId + ",", "")) {
          post.dislike_users = post.dislike_users?.replace(
            "," + this.userId + ",",
            ""
          );
          post.nb_dislikes -= 1;
        }
      }
      post.nb_votes = post.nb_likes + post.nb_dislikes;
      axios({
        method: "put",
        url: this.url + "/api/posts/" + post.id,
        data: post,
        auth: {
          username: "mehdi",
          password: "academy",
        },
      })
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
    dislike(post) {
      if (this.userId === "") {
        this.$router.push("/login");
        return;
      }
      if (post.dislike_users?.includes("," + this.userId + ",")) {
        post.dislike_users = post.dislike_users?.replace(
          "," + this.userId + ",",
          ""
        );
        post.nb_dislikes -= 1;
      } else {
        post.dislike_users = post.dislike_users + "," + this.userId + ",";
        post.nb_dislikes += 1;
        if (post.like_users?.includes("," + this.userId + ",", "")) {
          post.like_users = post.like_users?.replace(
            "," + this.userId + ",",
            ""
          );
          post.nb_likes -= 1;
        }
      }
      post.nb_votes = post.nb_likes + post.nb_dislikes;
      axios({
        method: "put",
        url: this.url + "/api/posts/" + post.id,
        data: post,
        auth: {
          username: "mehdi",
          password: "academy",
        },
      })
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
    getLikeClass(post) {
      return {
        "button liked":
          this.userId !== "" &&
          post.like_users?.includes("," + this.userId + ","),
        button:
          this.userId === "" ||
          !post.like_users?.includes("," + this.userId + ","),
      };
    },
    getDislikeClass(post) {
      return {
        "button disliked":
          this.userId !== "" &&
          post.dislike_users?.includes("," + this.userId + ","),
        button:
          this.userId === "" ||
          !post.dislike_users?.includes("," + this.userId + ","),
      };
    },
    deletePost(post) {
      let self = this;
      axios.delete(this.url + "/api/posts/" + post.id).then((response) => {
        self.$router.go();
      });
    },
    addPost() {
      if (this.userId === "") {
        this.$router.push("/login");
        return;
      }
      this.addPopup = true;
    },
    save() {
      let self = this;
      var newPost = new FormData();
      newPost.set("title", this.newTitle);
      newPost.set("description", this.newDescription);
      newPost.set("category", this.newCategory);
      newPost.set("nb_likes", 0);
      newPost.set("nb_dislikes", 0);
      newPost.set("like_users", "");
      newPost.set("dislike_users", "");
      newPost.set("nb_votes", 0);
      newPost.set("user_id", this.userId);
      newPost.append("picture", this.newFile);
      console.log("@@ " + JSON.stringify(this.newFile));
      axios({
        method: "post",
        url: this.url + "/api/posts",
        data: newPost,
        headers: {
          "Content-Type": "multipart/form-data",
        },
        auth: {
          username: "mehdi",
          password: "academy",
        },
      })
        .then(function (response) {
          self.addPopup = false;
          self.$router.go();
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
    onFileChanged(event) {
      console.log(event.target.files);
      this.newFile = event.target.files[0];
    },

    showCommentsFonc() {
      this.showComments = !this.showComments;
    },
    showCommentsFoncPopup(post) {
      this.showComments = true;
      this.viewPost(post);
    },

    formatDate(value) {
      if (value) {
        return moment(String(value)).format("DD/MM/YYYY hh:mm");
      }
    },

    createComment(post) {
      let self = this;
      var newComment = new FormData();
      newComment.set("body", this.commentaire);
      newComment.set("post_id", post.id);
      newComment.set("user_id", this.userId);
      axios({
        method: "post",
        url: self.url + "/api/comments",
        data: newComment,
        auth: {
          username: "mehdi",
          password: "academy",
        },
      })
        .then(function (response) {
          post.comments.push(response.data);
          self.commentaire = "";
          self.showComments = true;
        })
        .catch(function (error) {
          console.log(error.response.data);
        });
    },
  },

  mounted() {
    this.userId = localStorage.user ? JSON.parse(localStorage.user).id : "";
    axios.get(this.url + "/api/users").then((response) => {
      response.data.forEach((user) => {
        this.allUsers.push(user);
      });
      axios.get(this.url + "/api/comments").then((response) => {
        response.data.forEach((element) => {
          element.user = this.allUsers.filter(
            (user) => user.id === element.user_id
          )[0];
          element.username = element.user?.name;
          this.allComments.push(element);
        });
        axios.get(this.url + "/api/posts").then((response) => {
          console.log(response);
          if (this.category === "All") {
            response.data
              .sort(function (a, b) {
                return b.nb_votes - a.nb_votes;
              })
              .forEach((element) => {
                element.comments = this.allComments.filter(
                  (comment) => comment.post_id === element.id
                );
                element.user = this.allUsers.filter(
                  (user) => user.id === element.user_id
                )[0];
                element.username = element.user?.name;
                this.posts.push(element);
                this.allPosts.push(element);
              });
          } else {
            response.data
              .filter((post) => post.category === this.category)
              .sort(function (a, b) {
                return b.nb_votes - a.nb_votes;
              })
              .forEach((element) => {
                element.comments = this.allComments.filter(
                  (comment) => comment.post_id === element.id
                );
                element.user = this.allUsers.filter(
                  (user) => user.id === element.user_id
                )[0];
                element.username = element.user?.name;
                element.comments = this.allComments.filter(
                  (comment) => comment.post_id === element.id
                );
                this.posts.push(element);
                this.allPosts.push(element);
              });
          }
          let all = [];
          if (this.category === "All") {
            all = [...response.data];
          } else {
            all = response.data.filter(
              (post) => post.category === this.category
            );
          }
          this.plusLike = all.sort(function (a, b) {
            return b.nb_likes - a.nb_likes;
          })[0];

          this.plusRecents = all
            .sort(function (a, b) {
              return Date.parse(b.created_at) - Date.parse(a.created_at);
            })
            .slice(0, 6);
        });
      });
    });
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Amita");
@import url("https://fonts.googleapis.com/css?family=Autour+One");
@import url("https://fonts.googleapis.com/css?family=Rock+Salt");
@import url("https://fonts.googleapis.com/css?family=Wellfleet");
@import url('https://fonts.googleapis.com/css?family=Jacques+Francois');
.image {
  width: 17rem;
  height: 14rem;
  -webkit-box-shadow: 1px 10px 16px 2px rgba(79, 73, 79, 0.72);
  box-shadow: 1px 10px 16px 2px rgba(79, 73, 79, 0.72);
  border-radius: 75px;
}
.posttitle {
  padding: 0.7rem;
  font-family: Wellfleet, cursive;
  font-weight: bold;
  text-transform: capitalize;
  text-align: center;
  font-size: 17px;
  background: #000000;
  background: -webkit-linear-gradient(
    to bottom right,
    #000000 0%,
    #b0d4c6 100%
  );
  background: -moz-linear-gradient(to bottom right, #000000 0%, #b0d4c6 100%);
  background: linear-gradient(to bottom right, #000000 0%, #b0d4c6 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  max-width: 335px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.bname {
  color: black;
  font-family: "Jacques Francois", serif;
}

.bclass {
  text-align: -webkit-left;
  padding-left: 6rem;
  font-family: Wellfleet, cursive;
}

.votess {
  font-family: "Rock Salt", cursive;
  font-weight: bold;
  text-transform: capitalize;
  text-align: center;
  font-size: 19px;
  color: black;

  text-shadow: rgba(79, 73, 79, 0.55) -4px 3px 6px;
  border-bottom: dotted grey;
  border-radius: 200px;
}

.image2 {
  height: 17rem;
  -webkit-box-shadow: 1px 10px 23px 9px rgba(55, 68, 79, 0.67);
  box-shadow: 1px 10px 23px 9px rgba(55, 68, 79, 0.67);
  border-radius: 45px;
  width: 35rem;
}

.b {
  padding: 1rem;
  padding-left: 42px;
  font-family: Amita, cursive;
  font-weight: bold;
  text-transform: capitalize;
  text-align: left;
  font-size: 26px;
  color: rgba(14, 103, 102, 0.81);

  text-shadow: rgba(79, 73, 79, 0.55) -4px 3px 6px;
  border-top: dotted grey;
  border-radius: 200px;
}

.description {
  text-align: justify;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 8;
  -webkit-box-orient: vertical;
  padding-top: 35px;
  padding-left: 25px;
  padding-right: 30px;
}
.description-container {
  width: 17rem;
  height: 14rem;
  margin-left: 10%;
  background: linear-gradient(200deg, darkcyan, black);
  color: wheat;
  border: none;

  -webkit-box-shadow: 1px 10px 16px 2px rgba(79, 73, 79, 0.72);
  box-shadow: 1px 10px 16px 2px rgba(79, 73, 79, 0.72);
  border-radius: 75px;
}

.right-container {
  width: 25%;
  float: right;
}

.container {
  display: grid;
  grid-template-columns: 1fr;
  gap: 4em 0px;
  grid-template-areas:
    "."
    "."
    ".";
  width: 80%;
  margin: auto;
}

.button {
  all: unset;
  font-family: "Open Sans";
  font-size: 16px;
  font-weight: 400;
  display: inline-block;
  border-radius: 0.25em;
  text-shadow: -1px -1px 0px rgba(0, 0, 0, 0.4);
  cursor: pointer;
}

.liked {
  background-color: lightblue;
}

.disliked {
  background-color: red;
}

.grid {
  background: linear-gradient(90deg, darkcyan, white);
}

.modal-backdrop {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 500;
}

.modal {
  background: #ffffff;
  box-shadow: 2px 2px 20px 1px;
  overflow-x: auto;
  display: flex;
  flex-direction: column;
}
.add-popup {
  width: 40%;
  background-color: lightgrey;
}
.modal-header,
.modal-footer {
  padding: 15px;
  display: flex;
}

.modal-header {
  position: relative;
  color: #4aae9b;
  justify-content: space-between;
}

.modal-footer {
  border-top: 1px solid #eeeeee;
  flex-direction: column;
  justify-content: flex-end;
}

.modal-body {
  position: relative;
  padding: 20px 10px;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  font-family: "Autour One", cursive;
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

nav a:hover {
  color: cadetblue;
  font-weight: bold;
}

.navigation {
  height: 55px;
  background: linear-gradient(180deg, rgb(150, 175, 185), lightgray, white);
  margin-top: -1rem;
}
</style>

<style>
* {
  margin: 0;
  padding: 0;
}
</style>
<style scoped>
.header {
  height: 6rem;
  background-color: rgb(151, 173, 186);
  position: fixed;
  width: 100%;
  z-index: 100;
}

h1 {
  padding-right: 7rem;
  margin-top: 9rem;
}

.logo {
  float: left;
  width: 12rem;
  margin-left: 2rem;
}
.search {
  width: 35rem;
  height: 2rem;
  border: none;
  padding: 3px;
  margin-top: 2rem;
}

.search-button {
  height: 2.4rem;
  width: 3rem;
  border: none;
  cursor: pointer;
}

.search-container {
  margin-left: 15rem;
  float: left;
}

.user-buttons {
  float: right;
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
  background-color: rgb(151, 173, 186);
  color: dimgrey;
  padding: 10px 19px;
  font-size: 16px;
  cursor: pointer;
  margin-right: 1rem;
  border-radius: 40px;
}

.containerButton {
  margin-left: 2.3%;
  float: left;
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
  font-size: 15px;
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
  width: 140%;
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
.divImg {
  margin-top: 1.5rem;
}
.imageMost {
  height: 18rem;
  width: 23rem;
  -webkit-box-shadow: 2px 6px 22px 7px #5d6b68;
  box-shadow: 2px 6px 22px 7px #5d6b68;
  border-radius: 16%;
}
.buttonDelete {
  background: none;

  border: none;
  padding: 0px;
  font: inherit;
  cursor: pointer;
  outline: inherit;
  float: right;
  margin: 15px;
}
.buttons {
  display: flex;
  justify-content: left;
  margin-top: 1rem;
}

.feuille {
  display: grid;
  padding: 20px 10%;
}

.btn-close {
  border: none;
  font-size: 22px;
  cursor: pointer;
  font-weight: 900;
  color: red;
  background: transparent;
}
.slide {
  cursor: pointer;
}

.view-container {
  display: grid;
  background-color: lightgray;
  width: 60%;
  margin: auto;
  padding: 20px 5%;
  min-height: 300px;
  max-height: 500px;
  overflow-y: scroll;
}

.descrip {
  text-align: justify;
  margin-top: 2rem;
  font-family: "Autour One", cursive;
}

.nb-comment {
  font-weight: bold;
  text-decoration: underline;
  font-size: 12px;
  font-family: "Autour One";
  margin-left: 10px;
  margin-top: 5px;
  cursor: pointer;
}
.existing-comment {
  width: 100%;
  padding: 10px 10px 10px 10px;
  border: 1px solid rgba(60, 60, 60, 0.2);
  border-radius: 5px;
  margin-top: 15px;
  background-color: darkgrey;
  border: none;
  text-align: left;
  font-size: 13px;
  font-family: "Poppins";
}

.new-comment {
  width: 100%;
  padding: 10px 10px 10px 10px;
  border: 1px solid rgba(60, 60, 60, 0.2);
  border-radius: 5px;
  margin-top: 15px;
}

.date {
  float: right;
  font-size: 11px;
  margin: 2px;
}
</style>