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
      <tr v-for="user in this.collabList" :key='user.email'>
        <th scope="row">{{ user.id }}</th>
        <td>{{ user.firstName }}</td>
        <td>{{ user.lastName }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.status }}</td>
        <td>
          <button class="btn btn-secondary" style="line-height: 1;" @click="emitId(user.email)" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg height="15" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
            </svg>
          </button>
        </td>
      </tr>
    </tbody>
  </table>

<!-- Modal -->
<div class="modal fade modal-dialog modal-dialog-centered modal-dialog-scrollable" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{ selectedData.firstName }} {{ selectedData.lastName }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h2 class="m-2">User informations</h2>
        <div class="m-2">
          FirstName : {{ selectedData.firstName }}  <br/>
          LastName : {{ selectedData.lastName }} <br/>
          Email : {{ selectedData.email }}  <br/>
          Available : {{ selectedData.status }} <br/>
        </div>

        <div v-if="this.selectedDataSkills.length>=1">
          <h2 class="m-2">User Skill(s)</h2>
          <div v-for="skill in this.selectedDataSkills" :key="skill.id" class="m-2">
            {{ skill.name }}
          </div>
        </div>

        <div v-if="this.selectedDataMission.length>=1">
          <h2 class="m-2">User Mission(s)</h2>
          <div v-for="mission in this.selectedDataMission" :key="mission.id" class="m-2">
            {{ mission.jobName }} : <br/>
            {{ mission.description }} <br/>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Send Email</button>
      </div>
    </div>
  </div>
</div>

</template>

<script type="application/javascript">
import axios from 'axios';

export default {
    emits: ["giveId"],
    name: "collablist",
    data () {
        return {
          collabList: [],
          selectedItemId: null,
          selectedData: [],
          selectedDataSkills: [],
          selectedDataMission: [],
          selectedDataExp: [],
        }
    },
    methods: {
      loadCollabList(){
        axios.get("/api/collab_list").then(response => {
          let data = response.data;
          this.collabList = data;
          // console.log(this.collabList);
          // console.log(this.collabList[0].lastName)
        })      
      },
      emitId(target){
        this.selectedItemId=target
        // console.log(this.selectedItemId)
        this.$emit('giveId', this.selectedItemId)
        this.generateSelectedData()
      },
      generateSelectedData(){
        // console.log(this.selectedItemId)
        // console.log(this.selectedData)
        this.collabList.forEach(element => {
          if (element.email==this.selectedItemId){
            this.selectedData=element
            this.selectedDataSkills=element.skills
            this.selectedDataMission=element.mission
          }
        });
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
      // console.log(this.collabList);
    }).catch(() => {
      // console.log(this.collabList);
    })
  }
};
</script>

<style lang="css" scoped>
.modal {
   position: absolute;
   top: 10px;
   right: 100px;
   bottom: 0;
   left: 0;
   z-index: 10040;
   overflow: auto;
   overflow-y: auto;
}
</style>