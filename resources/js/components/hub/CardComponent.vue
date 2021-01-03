<template>
  <div v-if="this.card() !== undefined" class="plate-wrapper">
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
    <div class="m-4" v-if="this.transactions">
      <h2>Transactions</h2>
      <div
        class="plate my-2 border"
        v-for="transaction in this.transactions"
        v-bind:key="transaction.id"
      >
        <div class="d-flex justify-content-between">
          <h5>Transfer of personal funds</h5>
          <div class="text-muted">{{ transaction.created_at }}</div>
        </div>
        <span v-if="card().id == transaction.sender_card_id">-</span
        >{{ transaction.amount }} {{ card().currency }}
      </div>
    </div>
  </div>
  <div v-else class="plate-wrapper">
    <div class="plate margin border text-center">
      <h2>Card not found</h2>
      <div>
        Go back to
        <router-link :to="{ name: 'hub' }">Hub</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapState } from "vuex";
export default {
  data() {
    return {
      transactions: undefined,
    };
  },
  methods: {
    card() {
      return this.cards.filter((card) => card.id == this.$route.params.id)[0];
    },
    getTransactions() {
      axios({
        url: "/api/cards/" + this.card().id + "/transactions",
        method: "GET",
      })
        .then((result) => {
          this.transactions = result.data;
          this.transactions.forEach((transaction) => {
            transaction.created_at = new Date(
              transaction.created_at
            ).toLocaleString();
          });
        })
        .catch((err) => {});
    },
  },
  computed: {
    ...mapGetters(["cards"]),
    ...mapState(["user"]),
    status() {
      return this.$store.state.cardsStatus === "success";
    },
  },
  mounted() {
    this.getTransactions();
  },
};
</script>
