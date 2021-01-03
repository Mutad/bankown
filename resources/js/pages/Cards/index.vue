<template>
  <div class="d-flex flex-column justify-content-start">
    <div
      v-for="card in cards"
      v-bind:key="card.id"
      class="d-flex align-items-start plate"
    >
      <router-link
        :to="{ name: 'card', params: { id: card.id } }"
        class="btn p-0"
      >
        <card-plate-component :card="card" />
      </router-link>
      <div class="plate margin">
        Balance: {{ card.balance }} <b>{{ card.currency }}</b> <br />
        Type: <b>{{ card.type }}</b>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
// import CardPlateComponent from "../../components/ui/CardPlateComponent";
export default {
  // components: { CardPlateComponent },
  computed: {
    ...mapGetters(["cards"]),
  },
};
</script>

<style>
</style>