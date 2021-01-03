<template>
  <header class="row align-items-center m-0">
    <div
      class="back text-capitalize col d-flex align-items-center"
      v-if="backEnabled()"
      v-on:click="back()"
    >
      <!-- <img
            class="icon light"
            src="https://img.icons8.com/ios/50/000000/back--v1.png"
          /> -->
      <svg
        width="1.2em"
        height="1.2em"
        viewBox="0 0 16 16"
        class="bi bi-chevron-left"
        fill="currentColor"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          stroke="currentColor"
          stroke-width="1px"
          d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"
        />
      </svg>
      <div class="header-text">
        {{ this.$route.meta.previosPageName }}
      </div>
    </div>
    <div v-else class="col"></div>
    <div class="col text-center">
      {{ this.$route.name }}
    </div>
    <div class="col"></div>
  </header>
</template>
<script>
export default {
  methods: {
    back() {
      this.$router.push({ name: this.$route.meta.previosPageName });
    },
    backEnabled() {
      return this.$route.meta.previosPageName !== undefined;
    },
  },
};
</script>
<style scoped>
header {
  position: fixed;

  height: 50px;
  width: inherit;
  background-color: var(--nav-background);
  color: var(--nav-color);
  display: flex;
  align-items: center;

  font-size: 1.3rem;
  text-transform: capitalize;
}

.header-text,
.content > header {
  font-size: 1.3rem;
  text-transform: capitalize;
}

header > .back {
  color: var(--btn-primary);
  cursor: pointer;
}
</style>