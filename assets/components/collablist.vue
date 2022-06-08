<template>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">FirstName</th>
        <th scope="col">LastName</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in this.collabList" :key='user.id'>
        <th scope="row">{{ user.id }}</th>
        <td>{{ user.firstName }}</td>
        <td>{{ user.lastName }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.status }}</td>
        <td>Action</td>
      </tr>
    </tbody>
  </table>
</template>

<script type="application/javascript">
import axios from 'axios';

export default {
    name: "collablist",
    data () {
        return {
          collabList: null,
        }
    },
    methods: {
      loadCollabList(){
        axios.get("/api/collab_list").then(response => {
          let data = response.data;
          this.collabList = data;
          console.log(this.collabList);
        })      
      },
    },
  mounted() {
    const requestList = new Promise((successCallback, failureCallback)  => {
      this.loadCollabList();
      if (this.collabList != null){
        successCallback()
      }else {
        failureCallback()
      }
    })
    requestList.then(() => {
      console.log(this.collabList);
    }).catch(() => {
      console.log(this.collabList);
    })
  }
};
</script>

<style lang="scss" scoped>

</style>