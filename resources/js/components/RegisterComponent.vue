<template>
  <div class="container register">
    <div class="text-center">
      <h1>Create your account</h1>
      <div>
        <router-link :to="{ name: 'auth.login' }"
          >Already have an account? Find it here.</router-link
        >
      </div>
      <hr />
    </div>
    <form
      v-on:submit.prevent="onSubmit()"
      class="d-flex flex-column align-items-center"
    >
      <!-- {{ --Name-- }} -->
      <div class="wrapper col-lg-8">
        <div class="form-group w-100">
          <input
            type="text"
            placeholder="First name"
            name="first_name"
            v-model="user.first_name"
            class="form-control mr-1"
            required
          />
          <div class="error" v-if="errors.first_name != undefined">
            {{ errors.first_name }}
          </div>
        </div>
        <div class="form-group w-100">
          <input
            type="text"
            placeholder="Last name"
            name="last_name"
            v-model="user.last_name"
            class="form-control ml-1"
            required
          />
          <!-- @if ($errors->has('last_name'))
          <div class="error">
            {{ $errors->first('last_name') }}
          </div>
          @endif -->
        </div>
      </div>
      <!-- {{ --Country-- }} -->
      <div class="form-group col-lg-8 flex-column">
        <country-select-component
          v-bind:country="user.country"
        ></country-select-component>
        <!-- @if ($errors->has('country'))
        <div class="error">
          {{ $errors->first('country') }}
        </div>
        @endif -->
      </div>
      <!-- {{ --Birthday-- }} -->
      <div class="form-group col-lg-8">
        <input
          type="date"
          name="birth_date"
          v-model="user.birth_date"
          placeholder="dd/mm/yyyy"
          class="form-control"
          required
        />
        <!-- @if ($errors->has('birth_date'))
        <div class="error">
          {{ $errors->first('birth_date') }}
        </div>
        @endif -->
      </div>
      <hr />
      <!-- {{ --Login-- }} -->
      <div class="form-group col-lg-8 flex-column">
        <input
          type="email"
          name="email"
          v-model="user.email"
          id="input-email"
          placeholder="name@example.com"
          class="form-control"
          required
        />

        <div class="error" v-if="errors.email">
          {{ errors.email[0] }}
        </div>
        <small id="loginHelp" class="form-text text-muted"
          >This will be your new login.</small
        >
      </div>
      <!-- {{ --Password-- }} -->
      <div class="form-group col-lg-8">
        <input
          type="password"
          name="password"
          v-model="user.password"
          id="input-password"
          placeholder="Password"
          class="form-control"
          required
        />
        <div class="error" v-if="errors.password">
          {{ errors.password[0] }}
        </div>
      </div>
      <div class="form-group col-lg-8">
        <input
          type="password"
          name="password_repeat"
          v-model="user.password_repeat"
          id="input-password-repeat"
          placeholder="Confirm password"
          class="form-control"
          required
        />
        <div class="error" v-if="errors.password_repeat">
          {{ errors.password_repeat[0] }}
        </div>
      </div>
      <hr />

      <div class="col-lg-8">
        <input
          type="submit"
          class="btn btn-primary btn-block form-control"
          value="Continue"
        />
      </div>
    </form>
    <div class="text-center col-sm-4 offset-sm-4 text-muted">
      <small>
        Your data is stored on our server for your security, support and
        comfort.
        <a href="/legal/privacy">See how your data is managed</a>
      </small>
    </div>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      user: {
        first_name: "",
        last_name: "",
        birth_date: "",
        email: "",
        password: "",
        password_repeat: "",
        country: "United States",
      },
    };
  },
  methods: {
    onSubmit() {
      this.$store
        .dispatch("register", this.user)
        .then(() => this.$router.push({ name: "main" }))
        .catch((err) => console.log(err));
    },
  },
  computed: {
    ...mapGetters(["errors"]),
  },
};
</script>