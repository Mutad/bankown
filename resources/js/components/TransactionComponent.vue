<template>
  <div class="plate-wrapper">
    <h2 class="plate border">Sent money to</h2>
    <div class="plate border form-group">
      <label for="cardSender"><h3>Select your card to send from</h3></label>
      <select
        name="card"
        id="cardSender"
        class="custom-select"
        v-model="cardSender"
      >
        <option v-for="card in cards" v-bind:key="card.id" :value="card">
          {{ card.name }} | {{ card.balance }} {{ card.currency }}
        </option>
      </select>
    </div>
    <div class="plate border form-group">
      <label for="cardReciever"><h3>Enter reciever card number</h3></label>
      <input
        maxlength="16"
        class="form-control"
        type="text"
        name="cardReciever"
        id="cardReciever"
        v-model="cardReciever"
        v-on:keyup="cardCheck"
      />
    </div>
    <div class="plate border form-group">
      <label for="amount"><h3>Enter amount of money to send</h3></label>
      <input
        type="number"
        name="amount"
        id="amount"
        min="0.00"
        v-model="amount"
        class="form-control"
      />
    </div>
    <div class="plate p-0">
      <button
        class="btn btn-primary btn-block"
        :disabled="!aproved || cardSender == undefined || amount < 0.01"
        v-on:click="send"
      >
        Send
      </button>
    </div>
    <pre
      class="bg-light plate"
      v-if="aproved"
    ><span v-if="cardSender">Sender: {{ fullName }}
Card: {{ cardSender.number }}</span>
Reciever: {{ recieverInfo.owner }}
Card currency: {{ recieverInfo.currency }}
Amount: {{ amount }} {{ recieverInfo.currency }}</pre>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      cardSender: undefined,
      cardReciever: "4111044100000002",
      recieverInfo: null,
      amount: 0,
      aproved: false,
    };
  },
  mounted() {
    this.cardSenderId = this.cards[0];
    this.cardCheck();
  },
  methods: {
    cardCheck() {
      console.log(this.cardReciever.length);
      if (this.cardReciever.length == 16) {
        if (/^\d+$/.test(this.cardReciever)) {
          axios({
            url: "/api/card/info/" + this.cardReciever,
            method: "GET",
          })
            .then((result) => {
              this.recieverInfo = result.data;
              this.aproved = true;
            })
            .catch((err) => {
              console.log(err);
              this.aproved = false;
            });
        } else {
          console.log("error");
        }
      } else {
        this.aproved = false;
      }
    },
    send() {
      if (
        this.aproved &&
        this.cardSender !== undefined &&
        this.amount >= 0.01
      ) {
        axios({
          url: "api/transaction",
          method: "POST",
          data: {
            
          }
        })
      }
    },
  },
  computed: {
    ...mapGetters(["cards", "fullName"]),
  },
};
</script>