<template>
  <div class="m-3">
    <!-- <div class="alert alert-success" role="alert">
            {{ user.first_name }} {{ user.last_name }}
        </div>
        <div class="alert alert-success" role="alert">
            {{ user.country }}
        </div>
        <div class="alert alert-success" role="alert">
            {{ user.birth_date }}
        </div> -->
    <div class="m-4">
      <h2>Hey, {{ user.first_name }}.</h2>
      <h6 class="text-muted">
        This is your profile, you can remove, manage, add, things to display
        here.
      </h6>
    </div>
    <div class="plate border d-flex flex-column">
      <div class="d-flex align-items-center justify-content-between">
        <h2>General</h2>
        <a class="btn btn-secondary" href="#">Edit</a>
      </div>
      <div class="d-flex align-items-center">
        <svg
          width="5em"
          height="5em"
          viewBox="0 0 16 16"
          class="bi bi-person"
          fill="currentColor"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"
          />
        </svg>
        <div class="d-flex flex-column justify-content-center info">
          <h4>{{ user.first_name }} {{ user.last_name }}</h4>
          <p class="text-muted">{{ user.country }}</p>
        </div>
      </div>
      <div class="d-flex flex-column justify-content-center info">
        <small class="text-muted">Email</small>
        <h4>{{ user.email }}</h4>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["user"]),
  },
};
</script>
<style scoped>
.plate > * {
  margin-bottom: 30px;
}
.info > * {
  padding: 0;
  margin: 0;
}
</style>
