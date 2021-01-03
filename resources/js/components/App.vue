<template>
  <router-view></router-view>
</template>

<script>
import store from "../store";
import router from "../router";
export default {
  mounted() {},

  // Check if loged in
  created() {
    this.$http.interceptors.response.use(undefined, function (err) {
      return new Promise(function (resolve, reject) {
        if (err.response) {
          if (err.response.status === 401) {
            console.log("Unauthorized");
            router.push({ name: "auth.login" });
            store.dispatch("logout");
          }
        }
        throw err;
      });
    });
  },
};
</script>
