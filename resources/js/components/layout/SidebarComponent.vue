<template>
  <div class="sidebar">
    <header>
      <a href="/"><img src="/logo.png" alt="logo" class="logo" /></a>
    </header>
    <router-link class="btn" :to="{ name: 'hub' }">Hub</router-link>
    <router-link class="btn" :to="{ name: 'cards' }">Cards</router-link>
    <router-link class="btn" :to="{ name: 'profile' }">Profile</router-link>
    <div class="btn" @click="logout()">logout</div>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      this.$store.dispatch("logout");
    },
  },
};
</script>
<style scoped>
.sidebar {
  display: flex;
  flex-direction: column;
  background-color: var(--background-secondary);
}
header {
  text-align: center;
  background-color: var(--nav-background);
  color: var(--nav-color);
  padding: 10px;
}
.sidebar header .logo {
  height: 30px;
  filter: invert(1);
}
.content {
  width: 80%;
  flex: 0 0 auto !important;
}
.btn {
  border-radius: 0;
}
.btn.router-link-active {
  background-color: var(--color-footer);
  color: var(--background-secondary);
}

@media (max-width: 800px) {
  .sidebar {
    flex-direction: row;
    width: 100%;
    align-items: center;
  }
}

@media (max-width: 500px) {
  .hub .sidebar {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
