import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

import HubComponent from './components/HubComponent'
import AppComponent from './components/AppComponent'
import ProfileComponent from './components/ProfileComponent'
import LoginComponent from './components/LoginComponent'
import NewCardComponent from './components/hub/NewCardComponent'
import CardComponent from './components/hub/CardComponent'
import RegisterComponent from './components/RegisterComponent'
import TransactionComponent from './components/TransactionComponent'
import CardsPageComponent from './components/CardsPageComponent';

Vue.use(Router)


const router = new Router({
  mode: 'history',

  routes: [

    {
      path: '/app',
      name: 'application',
      redirect: '/app/hub',
      component: AppComponent,

      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: 'hub',
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