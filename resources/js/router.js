import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

import HubComponent from './components/HubComponent'
import ProfileComponent from './components/ProfileComponent'
import LoginComponent from './components/LoginComponent'
import BankingComponent from './components/hub/MainComponent'
import NewCardComponent from './components/hub/NewCardComponent'
import CardComponent from './components/hub/CardComponent'
import EmptyRouterComponent from './components/EmptyRouterComponent'
import RegisterComponent from './components/RegisterComponent'
import TransactionComponent from './components/TransactionComponent'

Vue.use(Router)


const router = new Router({
  mode: 'history',

  routes: [

    {
      path: '/hub',
      name: 'main',
      redirect: '/hub/banking',
      component: HubComponent,

      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: 'banking',
          component: BankingComponent,
          name: 'banking'
        },
        {
          path: 'profile',
          name: 'profile',
          component: ProfileComponent,
        },
        {
          path: 'card/:id(\\d+)',
          component: CardComponent,
          name: 'card',
          meta: {
            previosPageName: 'banking'
          }
        },
        {
          path: 'card/transaction',
          component: TransactionComponent,
          name: 'transaction',
          meta: {
            previosPageName: 'banking'
          }
        },
        {
          path: 'card/new',
          component: NewCardComponent,
          name: 'new card',
          meta: {
            previosPageName: 'banking'
          }
        }
      ]

    },
    {
      path: '/auth/login/',
      name: 'auth.login',
      component: LoginComponent,
    },
    {
      path: '/auth/forgot',
      name: 'auth.forgot',
      component: LoginComponent,
    },
    {
      path: '/auth/register',
      name: 'auth.register',
      component: RegisterComponent,
    }
  ],
});

router.beforeEach((to, from, next) => {
  console.log("Navigation guard called to " + to.name);
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    console.log("Navigation guard rejected going to login");
    // next('/login')
    router.push({ name: 'auth.login' })
  } else {
    next()
  }
})

export default router