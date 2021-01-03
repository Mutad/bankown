<template>
  <!-- :to="link ? { name: 'card', params: { id: card.id } } : '\b'" -->
  <div class="plate-card btn plate plate-shadow border justify-content-between">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <img src="/logo.png" alt="logo" class="icon" />
        <span class="font-weight-normal">Bankown</span>
      </div>
      <span class="text-muted font-weight-light">{{ card.type }}</span>
    </div>
    <div class="mt-5">{{ cardNumber() }}</div>
    <div class="d-flex flex-column justify-content-between">
      <p class="text-muted">{{ card.name }}</p>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    cardNumber() {
      var arr = this.card.number.match(/.{1,4}/g);
      return arr[0] + " " + arr[1] + " " + arr[2] + " " + arr[3];
    },
  },
  props: {
    card: {
      type: Object,
      default: () => ({
        id: 0,
        type: "placeholder",
        name: "placeholder name",
        number: "0000000000000000",
        created_at: "null",
      }),
    },
  },
};
</script>
<style scoped>
.plate-card {
  display: inline-flex;
  flex-direction: column;
  justify-content: space-between;
}
.icon {
  height: 20px;
}
</style>
