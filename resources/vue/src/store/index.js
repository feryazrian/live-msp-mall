import Vue from 'vue'
import Vuex from 'vuex'
import {
  //,SET_AGENT_MODEL
  //,SET_USR
  APP, USR, CLIENT
  //,APP_SET_FROZEN
  //,APP_SET_BUFFER
  //,APP_FREEZE_ROUTER
  //,SET_DOC_READINESS
  ,SET_HEADER_HEIGHT
} from './mutations'
import { APP as setApp, USR as setUsr, CLIENT as setClient } from './actions'
import { XS, SM, MD, LG, XL, APP as getApp, USR as getUsr, BREAKPOINT } from './getters'

Vue.use(Vuex)

//let setResolution = (v, x) => {
//  let assign = (v, { w, h }) => {
//    v.w = w
//    v.h = h
//  }
//  v.first = !v.first
//  assign(v, x)
//  setResolution = (v, x) => {
//    v.first = undefined
//    delete v.first
//    assign(v, x)
//    setResolution = (v, x) => { assign(v, x) }
//  }
//}

export default new Vuex.Store({
   state:{
     client:{
       w:0
      ,h:0
      //,first:false
      //,ready:false
      //,isDesktop:null
    }
    ,usr:{ }
    ,app:{
       state:{ token:'' }
      ,status:0
      ,frozen:null
      ,router:{ frozen:null }
      ,message:{
         info:null
        ,error:null
        ,warning:null
      }
      ,header:{ height:null }
      //,buffer:{ }//All state needed when switching between pages is stored here
    }
    ,layout:{
      headerHeight:null
    }
  }
  ,mutations:{
    //,[SET_AGENT_MODEL](state, isDesktop) {
    //  if(state.agent.isDesktop === null) { state.agent.isDesktop = isDesktop }
    //},
     [ USR.ALL ]:(state, value) => {
      state.usr = value
    }
    ,[ APP.FREEZE ]:(state, value) => {
      state.app.frozen = value
    }
    ,[ CLIENT.RESOLUTION ]:({ client:v }, { w, h }) => {
      v.w = w
      v.h = h
    }
    //,[APP_SET_BUFFER]({ app:o }, { k, v }) {
    //  if(v) o.buffer[k] = v
    //  else
    //    delete o.buffer[k]
    //}
    ,[ APP.CHANGE ]:(state, value) => {
      state.app.status = value
    }
    ,[ APP.ROUTER.FREEZE ]:(state, value) => {
      state.app.router.frozen = value
    }
    //,[SET_DOC_READINESS](state) {
    //  if(state.agent.ready === false) state.agent.ready = !state.agent.ready
    //}
    ,[ APP.HEADER.HEIGHT ]:(state, h) => {
      state.app.header.height = h
    }
    ,[ APP.STATE.TOKEN ]:(state, v) => {
      state.app.state.token = v
    }
    ,[ APP.MESSAGE.INFO ]:(state, v) => {
      state.app.message.info = v
    }
    ,[ APP.MESSAGE.ERROR ]:(state, v) => {
      state.app.message.error = v
    }
    ,[ APP.MESSAGE.WARNING ]:(state, v) => {
      state.app.message.warning = v
    }
    ,[ SET_HEADER_HEIGHT ]:(state, h) => {
      state.layout.headerHeight = h
    }
  }
  ,actions:{
     [ setUsr.ALL ]:({ commit }, v) => {
      commit(USR.ALL, v)
    }
    ,[ setApp.CHANGE ]:({ commit }, v) => {
      commit(APP.CHANGE, v)
    }
    ,[ setApp.FREEZE ]:({ commit }, v) => {
      commit(APP.FREEZE, v)
    }
    ,[ setClient.RESOLUTION ]:({ commit }, { screen, update }) => {
      update(() => {
        commit(CLIENT.RESOLUTION, screen)
      })
    }
    ,[ setApp.ROUTER.FREEZE ]:({ commit }, v) => {
      commit(APP.ROUTER.FREEZE, v)
    }
    ,[ setApp.HEADER.HEIGHT ]:({ commit }, h) => {
      commit(APP.HEADER.HEIGHT, h)
    }
    ,[ setApp.STATE.TOKEN ]:({ commit }, v) => {
      commit(APP.STATE.TOKEN, v)
    }
    ,[ setApp.MESSAGE.INFO ]:({ commit }, v) => {
      commit(APP.MESSAGE.INFO, v)
    }
    ,[ setApp.MESSAGE.ERROR ]:({ commit }, v) => {
      commit(APP.MESSAGE.ERROR, v)
    }
    ,[ setApp.MESSAGE.WARNING ]:({ commit }, v) => {
      commit(APP.MESSAGE.WARNING, v)
    }
    //,setAppBuffer:({ commit }, v) => {
    //  commit(APP_SET_BUFFER, v)
    //}
  }
  ,getters:{
     [ BREAKPOINT ]:({ client:v }) => {
      let w
      if((w = v.w) < 1) return undefined
      if(w < 600) {
        afterResizing.sm()
        alongResizing.xs(w)
        return XS
      }
      if(/*w >= 600 && */w < 960) {
        afterResizing.xs()
        alongResizing.sm(w)
        return SM
      }
      if(/*w >= 960 && */w < 1264) {
        afterResizing.sm()
        return MD
      }
      if(/*w >= 1264 && */w < 1922)//1904
        return LG
      //if(w >= 1922)
      return XL
      //return null
    }
    ,[ getApp.ROUTER.FROZEN ]:state => state.app.router.frozen
    ,[ getApp.HEADER.HEIGHT ]:state => state.app.header.height
    ,[ getApp.FROZEN ]:state => state.app.frozen
    ,[ getApp.STATUS ]:state => state.app.status
    ,[ getUsr.NAME ]:state => state.usr.name
    ,[ getUsr.USERNAME ]:state => state.usr.username
    ,[ getUsr.PROFILE_PICTURE ]:state => state.usr.photo
    ,[ getApp.STATE.TOKEN ]:state => state.app.state.token
    ,[ getUsr.MERCHANT ]:state => state.usr.merchant_id
    ,[ getUsr.IS_ADMIN ]:state => state.usr.role_id
    ,[ getApp.MESSAGE.INFO ]:state => state.app.message.info
    ,[ getApp.MESSAGE.ERROR ]:state => state.app.message.error
    ,[ getApp.MESSAGE.WARNING ]:state => state.app.message.warning
    //,[ getClient.SCREEN_STATUS ]:state => {
    //  let v = state.client
    //  return v.hasOwnProperty('first')? v.first : undefined
    //}
    //,docReady:state => state.agent.ready
  }
})

let alongResizing = {
   xs:v => {
    afterResizing.xs = () => {
      delete XS.width
      afterResizing.xs = () => { }
      alongResizing.xs = alongResizing.$$
      delete alongResizing.$$
    }
    XS.width = v
    alongResizing.$$ = alongResizing.xs
    alongResizing.xs = v => { XS.width = v }
  }
  ,sm:v => {
    afterResizing.sm = () => {
      delete SM.width
      afterResizing.sm = () => { }
      alongResizing.sm = alongResizing.$$
      delete alongResizing.$$
    }
    SM.width = v
    alongResizing.$$ = alongResizing.sm
    alongResizing.sm = v => { SM.width = v }
  }
}
let afterResizing = { xs() { }, sm() { } }