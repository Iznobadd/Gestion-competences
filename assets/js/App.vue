<template>
  <navbar />
  <h1>Hello ! {{ test }}</h1>
</template>

<script>
import navbar from '../components/navbar.vue'

export default {
  components: { 
    navbar 
  },
  data () {
    return {
      test : "Izno",
    }
  },
}
</script>

<style>
*{
  margin: 0;
  padding: 0;
}
</style>