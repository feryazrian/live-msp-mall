import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'
import Page from '@/components/Page'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
   faStar
  ,faSearch
  ,faChevronRight
  ,faChevronDown
  ,faListUl
  ,faBars
  ,faSignInAlt
  ,faHeart as fasHeart
  ,faShoppingCart
  ,faLayerGroup
  ,faUserCog
  ,faAd
  ,faEnvelope
  ,faMobile
  ,faFileInvoice
  ,faWallet
  ,faUser
  ,faCog
  ,faCogs
  ,faUserCheck
  ,faHeadset
  ,faQuestionCircle
  ,faBlog
  ,faTimes
  ,faPlus
  ,faMapMarkerAlt } from '@fortawesome/free-solid-svg-icons'
import {
   faFacebookSquare
  ,faFacebook
  ,faInstagram
  ,faYoutube
  ,faGoogle } from '@fortawesome/free-brands-svg-icons'
import {  faHeart as farHeart } from '@fortawesome/free-regular-svg-icons'

library.add(
   faStar
  ,faSearch
  ,faFacebookSquare
  ,faFacebook
  ,faInstagram
  ,faYoutube
  ,faGoogle
  ,faChevronRight
  ,faChevronDown
  ,faListUl
  ,faMapMarkerAlt
  ,fasHeart
  ,farHeart
  ,faBars
  ,faSignInAlt
  ,faShoppingCart
  ,faLayerGroup
  ,faUserCog
  ,faAd
  ,faEnvelope
  ,faMobile
  ,faFileInvoice
  ,faWallet
  ,faUser
  ,faUserCheck
  ,faCog
  ,faCogs
  ,faHeadset
  ,faQuestionCircle
  ,faBlog
  ,faTimes
  ,faPlus
)

Vue.component('page', Page)
Vue.component('font-awesome', FontAwesomeIcon)

Vue.config.productionTip = false

vmVue = new Vue({
  el:'#vueApp'
  ,store
  ,router
  ,data:() => ({ attrs:{ } })
  ,render(build) { return build(App, { props:{ attrs:this.attrs } }) }
  ,beforeMount() {
    Array.from(this.$el.attributes).map(e => Vue.set(this.attrs, e.name, e.value))
  }
})
//new Vue({
//  store,
//  router,
//  render:h => h(App)
//}).$mount('#vueApp')