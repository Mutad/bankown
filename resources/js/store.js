import { reject } from "lodash";
import Vue from "vue";
import Vuex from "vuex";
import router from "./router";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        status: "",
        cardsStatus: "",
        token: localStorage.getItem("token") || "",
        user: {},
        cards: [],
        errors: [],
    },

    strict: true,

    mutations: {
        auth_request(state) {
            console.log("Auth request");
            state.errors = [];
            state.status = "loading";
        },
        cards_request(state) {
            console.log("Card request");
            state.errors = [];
            state.cardsStatus = "loading";
        },

        auth_success(state, { token, user }) {
            state.status = "success";
            state.token = token;
            state.user = user;
        },
        auth_error(state, errors) {
            state.status = "error";
            state.errors = errors;
        },
        logout(state) {
            state.status = "";
            state.token = "";
        },
        user(state, user) {
            console.log("User loaded " + user.id);
            state.user = user;
            state.status = "success";
        },
        cards(state, cards) {
            console.log("Cards loaded " + cards.length);
            state.cards = cards;
            state.cardsStatus = "success";
        },
        cards_error(state) {
            state.cardsStatus = "error";
        },
        add_card(state, card) {
            state.cards.push(card);
        },
    },

    actions: {
        login({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request");
                axios({
                    url: "/api/auth/login",
                    data: {
                        email: user.email,
                        password: user.password
                    },
                    method: "POST"
                })
                    .then(resp => {
                        const token = resp.data.access_token;
                        const user = resp.data.user;
                        localStorage.setItem("token", token);
                        axios.defaults.headers.common["Authorization"] = token;
                        commit("auth_success", { token, user });
                        resolve(resp);
                        // const token = resp.data.token
                        // const user = resp.data.user
                        // localStorage.setItem('token', token)
                        // axios.defaults.headers.common['Authorization'] = token
                        // commit('auth_success', token, user)
                        // resolve(resp)
                    })
                    .catch(error => {
                        console.log(error);
                        commit("auth_error");
                        localStorage.removeItem("token");
                        reject(error);
                    });
            });
        },

        register({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request");
                axios({ url: "/api/auth/register", data: user, method: "POST" })
                    .then(resp => {
                        const token = resp.data.access_token;
                        const user = resp.data.user;
                        localStorage.setItem("token", token);
                        axios.defaults.headers.common["Authorization"] = token;
                        commit("auth_success", token, user);
                        resolve(resp);
                    })
                    .catch(err => {
                        console.log(err);
                        commit("auth_error", err.response.data.errors);
                        localStorage.removeItem("token");
                        reject(err);
                    });
            });
        },

        logout({ commit }) {
            return new Promise((resolve, reject) => {
                commit("logout");
                localStorage.removeItem("token");
                delete axios.defaults.headers.common["Authorization"];
                router.push({ name: "auth.login" });
                resolve();
            });
        },

        loadUser({ commit }) {
            return new Promise((resolve, reject) => {
                commit("auth_request");
                axios({ url: "/api/auth/user", method: "GET" })
                    .then(resp => {
                        // Simulate loading latency
                        // FIXME: Disable this
                        setTimeout(() => {
                            const user = resp.data;
                            commit("user", user);
                            resolve(resp);
                        }, 100);
                    })
                    .catch(err => {
                        commit("auth_error", err);
                        localStorage.removeItem("token");
                        reject(err);
                    });
            });
        },

        loadCards({ commit }) {
            if (this.state.cardsStatus !== "success")
                return new Promise((resolve, reject) => {
                    commit("cards_request");
                    axios({ url: "/api/card", method: "GET" })
                        .then(resp => {
                            const cards = resp.data;
                            commit("cards", cards);
                            resolve(resp);
                        })
                        .catch(err => {
                            commit("cards_error", err);
                            reject(err);
                        });
                });
        },

        createCard({ commit }, card) {
            return new Promise((resolve, reject) => {
                axios({ url: "/api/card", method: "POST", data: card })
                    .then(resp => {
                        console.log(resp.data);
                        const card = resp.data;

                        commit("add_card", card);
                        router.push({ name: "main" });
                        resolve(resp);
                    })
                    .catch(err => {
                        reject(err);
                    });
            })
        },
    },

    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        loaded: state => {
            return state.status == "success";
        },
        cards: state => {
            return state.cards;
        },
        fullName: state => state.user.first_name + ' ' + state.user.last_name,
        errors: state => state.errors,
    }
});
