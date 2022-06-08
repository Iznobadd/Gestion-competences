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
      <tr>
        <th scope="row">1</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td colspan="2">Larry the Bird</td>
        <td>@twitter</td>
      </tr>
    </tbody>
  </table>
</template>

<script type="application/javascript">
import axios from 'axios';

export default {
    props: ['login'],
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
    // watch: {
    //   login (login){
    //   const request = new Promise((successCallback, failureCallback)  => {
    //       this.loadInfoUser();
    //       if (this.email != null){
    //         successCallback()
    //       }else {
    //         failureCallback()
    //       }
    //   })
    //   request.then(() => {
    //     // console.log(this.email);
    //   }).catch(() => {
    //     // console.log(this.email);
    //   })
    // },
  // },
  beforeMount() {
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