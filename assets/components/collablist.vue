<template>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">FirstName</th>
        <th scope="col">LastName</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <th scope="col">Show</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in this.collabList" :key='user.id'>
        <th scope="row">{{ user.id }}</th>
        <td>{{ user.firstName }}</td>
        <td>{{ user.lastName }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.status }}</td>
        <td>
          <button class="btn btn-secondary" style="line-height: 1;" @click="emitId(user.id)">
            <svg height="15" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
            </svg>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script type="application/javascript">
import axios from 'axios';

export default {
    emits: ["giveId"],
    name: "collablist",
    data () {
        return {
          collabList: [],
          selectedItemId: 5,
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
      emitId(target){
        this.selectedItemId=target
        console.log(this.selectedItemId)
        this.$emit('giveId', this.selectedItemId)
      }
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