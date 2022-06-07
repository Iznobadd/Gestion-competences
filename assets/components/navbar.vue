<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-primary">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/">Welcome {{ firstName }} !</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item" @click="profileOpen">
              <a class="nav-link" href="#">Profile</a>
            </li>
            <li v-if="isCommercial" class="nav-item" @click="listOpen">
              <a class="nav-link" href="#">List of collaborator</a>
            </li>
            <li v-if="isAdmin" class="nav-item">
              <a class="nav-link" href="/dashboard">Admin panel</a>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-secondary" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
  </div>
</template>

<script type="application/javascript">
export default {
    props: ['firstName','email','isAdmin','isCollab','isCommercial'],
    name: "navbar",
    data () {
        return {
          pageProfil: false,
          pageList: false,
        }
    },
    methods: {
      profileOpen(){
        if (this.pageProfil==false){
          this.pageList=false
          this.pageProfil=true
          console.log('ouverture du profil')
        }
      },
      listOpen(){
        if (this.pageList==false){
          this.pageProfil=false
          this.pageList=true
          console.log('ouverture de la liste')
        }
      },
    }
};
</script>

<style lang="scss" scoped>

</style>