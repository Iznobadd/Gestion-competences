<template>
  <div class="container-fluid">
    <div class="d-flex mt-5 justify-content-center align-items-center">
      <div>
        <svg height="250" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div>
        <div><h2>{{ firstName }} {{ lastName }}</h2></div>
        <div><h3>{{ email }}</h3></div>
        <div>Collaborator : {{ (isCollab) ? 'Oui' : 'Non' }}</div>
        <div>Commercial : {{ (isCommercial) ? 'Oui' : 'Non' }}</div>
        <div>Admin : {{ (isAdmin) ? 'Oui' : 'Non' }}</div>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-6 d-flex flex-column align-items-center">
        <h4>My Skill(s)</h4>
        <div v-for="skill in this.skillsUser" :key="skill.id">
          {{ skill.name }}
        </div>
      </div>
      <div class="col-6 d-flex flex-column align-items-center">
        <h4>My Mission(s)</h4>
        <div v-for="mission in this.missionsUser" :key="mission.id">
          {{ mission.jobName }} <br/>
          {{ mission.description }}
        </div>
      </div>
    </div>
  </div>
</template>

<script type="application/javascript">
export default {
    props: ['firstName', 'lastName','email','isAdmin','isCollab','isCommercial', 'skillsUser', 'missionsUser'],
    name: "profile",
    data () {
        return {
          test: 'hi'
        }
    },
    methods: {
      
    }
};
</script>

<style lang="scss" scoped>

</style>