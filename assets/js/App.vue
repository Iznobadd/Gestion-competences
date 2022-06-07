<template>
  <navbar 
  :firstName="firstName"
  :email="email"
  :isAdmin="isAdmin"
  />
  <!-- <h1>Hello {{firstName }} !</h1> -->
</template>

<script>
import navbar from '../components/navbar.vue';
import axios from 'axios';

export default {
  components: { 
    navbar 
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
    }
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