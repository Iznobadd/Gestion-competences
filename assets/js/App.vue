<template>
  <navbar 
  :firstName="firstName"
  :email="email"
  :isAdmin="isAdmin"
  :isCommercial="isCommercial"
  :isCollab="isCollab"
  @clickProfile="toggleProfile()"
  @clickList="toggleList()"
  />
  <profile
  :firstName="firstName"
  :lastName="lastName"
  :email="email"
  :isAdmin="isAdmin"
  :isCommercial="isCommercial"
  :isCollab="isCollab"
  v-if="showProfile"
  />
  <collablist
  v-if='showList'
  @giveId="takeId($event)"
  />
</template>

<script>
import navbar from '../components/navbar.vue';
import collablist from '../components/collablist.vue'
import profile from '../components/profile.vue'
import axios from 'axios';

export default {
  components: { 
    navbar,
    collablist,
    profile
  },
  data () {
    return {
      login: false,
      infoUser : [],
      firstName: null,
      lastName: null,
      email: null,
      status: null,
      isAdmin: null,
      isCollab: null,
      isCommercial: null,
      showProfile: true,
      showList: false,
      // id: null,
    }
  },
  methods:{
    loadInfoUser(){
      axios.get("/api/info_user").then(response => {
        let data = response.data;
        this.infoUser = data;
        this.firstName = this.infoUser.firstName;
        this.lastName = this.infoUser.lastName;
        this.email = this.infoUser.email;
        this.status = this.infoUser.status;
        this.isAdmin = this.infoUser.is_admin;
        this.isCollab = this.infoUser.is_collab;
        this.isCommercial = this.infoUser.is_commercial;
        // console.log(this.infoUser);
      })      
    },
    toggleProfile(){
      this.showProfile = true
      this.showList = false
      // console.log('showProfile',this.showProfile)
    },
    toggleList(){
      this.showList = true
      this.showProfile = false
      // console.log('showList', this.showList)
    },
    takeId(id){
      console.log(id)
    }
  },
  watch: {
    login (login){
      const request = new Promise((successCallback, failureCallback)  => {
          this.loadInfoUser();
          if (this.email != null){
            successCallback()
          }else {
            failureCallback()
          }
      })
      request.then(() => {
        // console.log(this.email);
      }).catch(() => {
        // console.log(this.email);
      })
    },
    // pageProfile (pageProfile){
    //   console.log(this.showProfile)
    // }
  },
  beforeMount(){
    this.login = true;
  },
}
</script>

<style>
*{
  margin: 0;
  padding: 0;
}
</style>