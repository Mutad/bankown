<template>
  <div class="hub">
    <sidebar-component class="sidebar"></sidebar-component>
    <div class="content" v-if="loaded">
      <header-component></header-component>
      <router-view class="route"></router-view>
    </div>
    <div v-else class="content loader">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  mounted() {
    this.$store.dispatch("loadCards");
  },
  computed: {
    ...mapGetters(["loaded"]),
  },
};
</script>
<style scoped>
.hub {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  flex-grow: 1;
  width: 100%;
}
.icon {
  height: 30px;
}
.sidebar {
  padding: 0;
  margin: 0;
  height: 100%;
  width: 160px;
  position: fixed;
  overflow: hidden;
}

.content {
  position: relative;
  width: calc(100% - 160px);
  margin-left: 160px;
}

.content > router-link {
  width: 100%;
  overflow: auto;
}

.route {
  margin-top: 50px;
  padding-top: 10px;
}
.loader {
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 800px) {
  .hub {
    flex-direction: column;
  }
  .sidebar {
    display: none !important;
    position: relative;
    height: auto;
  }
  .hub .content {
    width: 100%;
    margin: 0;
  }
}
</style>
