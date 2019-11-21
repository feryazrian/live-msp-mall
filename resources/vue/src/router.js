import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import AccountLogger from './views/AccountLogger.vue'
import ItemDetail from './views/ItemDetail.vue'
import PhoneAccountLogger from './components/phone/AccountLogger'

Vue.use(Router)

export default new Router({
  mode:'history',
  base:process.env.BASE_URL,
  routes:[
     { path:'/', name:'home', component:Home
      ,children:[
         { props:{ action:1 }, component:AccountLogger, path:'login', name:'login' }
        ,{ props:{ action:0 }, component:AccountLogger, path:'register', name:'register' }
      ]
    }
    //,{ path:'/sign', component:AccountLogger
    //  ,children:[
    //     { path:'in', props:{ action:1 } }
    //    ,{ path:'up', props:{ action:0 } }
    //  ]
    //}
    
    //,{ path:'/login'
    //  ,name:'login'
    //  ,props:{ action:1 }
    //  ,component:AccountLogger
    //  //,beforeEnter(into, from, next) {
    //  //  document.body.classList.add('frozen')
    //  //  next()
    //  //}
    //}
    //,{
    //   path:'/register'
    //  ,name:'register'
    //  ,props:{ action:0 }
    //  ,component:AccountLogger
    //}

    ,{ path:'/product/:path', name:'product', component:ItemDetail, props:true }
    //,{ path:'/about', name:'about'
    //  // route level code-splitting
    //  // this generates a separate chunk (about.[hash].js) for this route
    //  // which is lazy-loaded when the route is visited.
    //  ,component:() => import(/* webpackChunkName: "about" */ './views/About.vue')
    //}
  ]
})