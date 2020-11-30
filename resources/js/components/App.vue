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
      console.log("Interception");
      return new Promise(function (resolve, reject) {
        if (err.response) {
          if (err.response.status === 401) {
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
