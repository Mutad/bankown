<template>
  <div v-if="status" class="plate-wrapper">
    <div class="plate margin border">
      <h2>This is your card</h2>
      <h6 class="text-muted">Here you can change some settings.</h6>
    </div>
    <div class="plate-list m-2">
      <card-plate-component
        v-if="status"
        v-bind:card="card()"
      ></card-plate-component>
      <div>
        <p>
          <span class="text-muted">Date of creation</span>
          <br />
          {{ card().created_at }}
        </p>
        <p>
          <span class="text-muted">ID</span>
          <br />
          {{ card().id }}
        </p>
      </div>
    </div>
    <hr />
    <div class="m-4">
      <h2>Transactions</h2>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapState } from "vuex";
export default {
  methods: {
    card() {
      return this.cards.filter((card) => card.id == this.$route.params.id)[0];
    },
  },
  computed: {
    ...mapGetters(["cards"]),
    status() {
      return this.$store.state.cardsStatus === "success";
    },
  },
};
</script>
