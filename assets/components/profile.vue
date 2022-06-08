<template>
  <div>Profile of {{ firstName }} {{ lastName }} !</div>
  <div>Email : {{ email }}</div>
  <div>Collaborator : {{ (isCollab) ? 'Oui' : 'Non' }}</div>
  <div>Commercial : {{ (isCommercial) ? 'Oui' : 'Non' }}</div>
  <div>Admin : {{ (isAdmin) ? 'Oui' : 'Non' }}</div>
</template>

<script type="application/javascript">
export default {
    props: ['firstName', 'lastName','email','isAdmin','isCollab','isCommercial'],
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