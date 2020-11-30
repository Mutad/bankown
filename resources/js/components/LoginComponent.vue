<template>
  <div class="container">
    <div class="row">
      <div class="col-lg login">
        <h1 class="align-self-start">Please sign in</h1>
        <form v-on:submit.prevent="onSubmit()">
          <div class="form-group">
            <input
              v-model="email"
              class="form-control"
              type="email"
              name="email"
              id="input-email"
              placeholder="Your email"
            />
            <!-- @if ($errors->has('email'))
                    <div class="error">
                        {{ $errors->first('email') }}
                    </div>
                    @endif -->
          </div>
          <div class="form-group">
            <input
              v-model="password"
              class="form-control"
              type="password"
              name="password"
              id="input-password"
              placeholder="Your password"
            />
            <!-- @if ($errors->has('password'))
                    <div class="error">
                        {{ $errors->first('password') }}
                    </div>
                    @endif -->
          </div>
          <!-- @if ($errors->has('result'))
                <div class="alert alert-danger" role="alert">
                    {{$errors->first('result')}}
                </div>
                @endif -->
          <input
            type="submit"
            class="btn btn-primary btn-block"
            value="Submit"
          />
        </form>
        <div>
          <router-link :to="{ name: 'auth.forgot' }"
            >Forgot your login or password?</router-link
          >
        </div>
        <div>
          <router-link :to="{ name: 'auth.register' }"
            >Dont have an account? Create one now.</router-link
          >
        </div>
      </div>
      <!-- <div class="col-sm">
                @include('modules.features')
            </div> -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },

  methods: {
    onSubmit() {
      let email = this.email;
      let password = this.password;
      this.$store
        .dispatch("login", { email, password })
        .then(() => this.$router.push({ name: "main" }))
        .catch((err) => console.log(err));
    },
  },

  mounted() {
    // axios.get('/api/user')
    // .then(response => {
    //     console.log(response);
    // });
  },
};
</script>
