import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

import AppComponent from './layouts/AppComponent'

import HubComponent from './pages/Hub'
import ProfileComponent from './pages/Profile'
import LoginComponent from './pages/Auth/Login.vue'
import NewCardComponent from './pages/CreateCard'
import CardComponent from './pages/Card'
import RegisterComponent from './pages/Auth/Register.vue'
import TransactionComponent from './pages/CreateTransaction'
import CardsPageComponent from './pages/Cards';
Vue.use(Router)


const router = new Router({
  mode: 'history',

  routes: [

    {
      path: '/app/',
      // name: 'application',
      // redirect: '/app/hub',
      component: AppComponent,

      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: '',
          component: HubComponent,
          name: 'hub'
        },
        {
          path: 'profile',
          name: 'profile',
          component: ProfileComponent,
        },
        {
          path: 'cards',
          component: CardsPageComponent,
          name: 'cards',
          meta: {
            previosPageName: 'hub'
          }
        },
        {
          path: 'card/:id(\\d+)',
          component: CardComponent,
          name: 'card',
          meta: {
            previosPageName: 'cards'
          }
        },
        {
          path: 'card/transaction',
          component: TransactionComponent,
          name: 'transaction',
          meta: {
            previosPageName: 'cards'
          }
        },
        {
          path: 'card/new',
          component: NewCardComponent,
          name: 'new card',
          meta: {
            previosPageName: 'cards'
          }
        }
      ]

    },
    {
      path: '/app/login/',
      name: 'auth.login',
      component: LoginComponent,
    },
    {
      path: '/app/forgot',
      name: 'auth.forgot',
      component: LoginComponent,
    },
    {
      path: '/app/register',
      name: 'auth.register',
      component: RegisterComponent,
    }
  ],
});

router.beforeEach((to, from, next) => {
  console.log("Navigation to " + to.name + " (route: " + to.path + " )");
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    console.log("Navigation rejected (User is not authenticated)");
    router.push({ name: 'auth.login' })
  } else {
    next()
  }
})

export default router