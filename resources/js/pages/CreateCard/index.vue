<template>
  <div class="plate-wrapper">
    <div class="plate border">
      <span class="h2">Personalize your new card</span>
      <h6 class="text-muted">page description</h6>
    </div>
    <div class="plate d-flex">
      <card-plate-component
        v-bind:card="card"
        v-bind:link="false"
      ></card-plate-component>
      <form class="flex-grow-1 p-1" v-on:submit.prevent="createCard">
        <div class="form-group">
          <label for="cardName">Card name</label>
          <input
            required
            class="form-control"
            type="text"
            name="name"
            id="cardName"
            placeholder="text on your card"
            v-model="card.name"
          />
          <small class="text-muted">Used to mark your cards</small>
        </div>
        <div class="form-group">
          <label for="cardType">Type of card</label>
          <select
            name="type"
            id="cardType"
            class="custom-select"
            v-model="card.type"
          >
            <option value="DEBIT">Debit card</option>
            <option value="CREDIT">Credit card</option>
          </select>
        </div>
        <div class="form-group">
          <label for="cardCurrency">Currency</label>
          <select
            name="currency"
            id="cardCurrency"
            class="custom-select"
            v-model="card.currency"
          >
            <option value="USD">United States Dollar</option>
            <option value="EUR" disabled>Euro</option>
            <option value="UAH" disabled>Ukranian hryvnia</option>
          </select>
        </div>
        <button
          type="Submit"
          class="btn btn-block btn-primary form-control mt-5"
        >
          Create
        </button>
      </form>
    </div>
    <hr />
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      card: {
        id: 0,
        name: this.fullName,
        number: "1234567890123456",
        currency: "USD",
        type: "DEBIT",
      },
    };
  },
  methods: {
    createCard() {
      this.$store.dispatch("createCard", this.card);
    },
  },
  mounted() {
    this.card.name = this.fullName;
  },
  computed: {
    ...mapGetters(["fullName"]),
  },
};
</script>
<style scoped>
label {
  margin-left: 5px;
}
</style>
