<template>
  <div class="plate-wrapper">
    <div class="plate border d-flex justify-content-between">
      <div>
        <span class="h2">Bankown Dashboard</span>
        <h6 class="text-muted">Welcome back, {{ user.first_name }}</h6>
      </div>
      <h2 class="text-right">{{ timestamp }}</h2>
    </div>

    <div>
      <h3 class="plate margin">Frequently used:</h3>
      <div class="plate-list">
        <router-link :to="{ name: 'transaction' }" class="plate btn btn-primary"
          >Make transaction</router-link
        >
        <router-link :to="{ name: 'new card' }" class="plate btn btn-primary"
          >Open new card</router-link
        >
      </div>
    </div>
    <div>
      <h3 class="plate margin">My cards:</h3>
      <div class="plate-list mb-3">
        <router-link
          v-for="card in cards"
          v-bind:key="card.id"
          :to="{ name: 'card', params: { id: card.id } }"
          class="btn p-0"
        >
          <card-plate-component v-bind:card="card"></card-plate-component>
        </router-link>
      </div>
    </div>

    <div>
      <h3 class="plate margin">Send money to:</h3>
      <div class="plate-list">
        <contact-plate-component></contact-plate-component>
      </div>
    </div>
    <div>
      <h3 class="plate margin">Transactions</h3>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      timestamp: "",
    };
  },
  created() {
    this.getNow();
    setInterval(this.getNow, 10000);
  },
  methods: {
    getNow: function () {
      const today = new Date();
      const options = {
        weekday: "long",
        year: undefined,
        month: "long",
        day: "numeric",
      };
      this.timestamp = today.toLocaleDateString(undefined, options);
    },
  },
  computed: {
    ...mapState(["user", "status", "cardsStatus", "cards"]),
  },
};
</script>
<style scoped></style>
