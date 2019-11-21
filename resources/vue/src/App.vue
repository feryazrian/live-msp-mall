<style>
html{
  box-sizing:border-box;
  text-rendering:optimizeLegibility;
  scroll-behavior:smooth;
  -ms-touch-action:none;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  -webkit-text-size-adjust:100%;
  -webkit-tap-highlight-color:rgba(0,0,0,0)
}

*,::before,::after{ box-sizing:inherit }

body,ul{ margin:0 }

html.frozen{
  overflow:hidden
}
html > body::after{
  z-index:2;
  top:0;
  width:100%;
  height:100%;
  opacity:0;
  content:'';
  pointer-events:none;
  position:fixed;
  transition:inherit;
  background-color:rgba(0,0,0,.77)
}
html > body.white::after{
  background-color:#fff
}

html > body.frozen::after{ opacity:1 }
html.frozen > body::after{
  pointer-events:all
}
/*
html.blur body{ position:fixed }
html.blur body::after{
  top:0;
  right:0;
  width:100%;
  height:100%;
  content:'';
  display:block;
  position:fixed;
  background-color:rgb(0,0,0,.8)
}
*/
::selection{ background-color:rgb(255,224,130,.75) }

img{ display:block }

ul{
  padding:0;
  list-style-type:none
}

a{
  color:inherit;
  text-decoration:none;
  background-color:transparent;
  -webkit-text-decoration-skip:objects
}
a:active,
a:hover{ outline-width:0 }

 button
,input[type=button]
,input[type=submit]
,input[type=reset]{ cursor:pointer }

 button::-moz-focus-inner
,[type=button]::-moz-focus-inner
,[type=submit]::-moz-focus-inner
,[type=reset]::-moz-focus-inner{
  border:0;
  padding:0
}

#app{
  font:400 normal normal 16px/normal Roboto,Helvetica,Arial,sans-serif;
  color:rgba(37,37,37,.87);
  position:relative;
  /*text-shadow:0 0 3px rgba(67,67,67,.21),1px 0 1px rgba(67,67,67,.17),0 1px 1px rgba(67,67,67,.33);*/
  /*background-color:#fafafa*/
}

/*Breaker of Chrome*/
#app input[type=text],#app input[type=password]{
  background-color:#fff!important
}

#app input,#app button,#app select,#app textarea{
  font:inherit;
  color:inherit;
  outline:none;
  text-shadow:inherit;
  border-style:solid
}

 #app button
,#app input[type=button]
,#app input[type=submit]
,#app input[type=reset]{
  /*text-shadow:0 0 3px rgba(0,0,0,.19),1px 0 1px rgba(0,0,0,.17),0 1px 1px rgba(0,0,0,.23)*/
  text-shadow:0 0 3px rgba(67,67,67,.23),1px 1px 2px rgba(67,67,67,.17)
}
#app button.themeBasis[disabled]{
  cursor:not-allowed;
  background-color:#e6e6e6
}

#app input.themeBasis[disabled]{
  cursor:not-allowed
}

#app form.hidden,#app form.hidden > input[name]{
  display:none
}

 #app .themeBasis-bgColor
,#app .themeBasis-bgColorLowest::before
,#app .themeBasis-bgColorHigher::after
/*,#app .themeBasis button
,#app .themeBasis input[type=button]
,#app .themeBasis input[type=submit]
,#app .themeBasis input[type=reset]*/
,#app button.themeBasis
,#app input[type=button].themeBasis
,#app input[type=submit].themeBasis
,#app input[type=reset].themeBasis{
  background-color:#ffc107
  /*background-color:#ffb300*/
}

 #app .themeBasis-color
,#app .themeBasis-colorLowest::before
,#app .themeBasis-colorHigher::after{
  color:#ffc107
}

#app .elevation-1{ box-shadow:0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12) }
#app .elevation-2{ box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12) }
#app .elevation-3{ box-shadow:0 3px 3px -2px rgba(0,0,0,.2),0 3px 4px 0 rgba(0,0,0,.14),0 1px 8px 0 rgba(0,0,0,.12) }
#app .elevation-4{ box-shadow:0 2px 4px -1px rgba(0,0,0,.2),0 4px 5px 0 rgba(0,0,0,.14),0 1px 10px 0 rgba(0,0,0,.12) }
#app .elevation-5{ box-shadow:0 3px 5px -1px rgba(0,0,0,.2),0 5px 8px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12) }
#app .elevation-6{ box-shadow:0 3px 5px -1px rgba(0,0,0,.2),0 6px 10px 0 rgba(0,0,0,.14),0 1px 18px 0 rgba(0,0,0,.12) }
#app .elevation-7{ box-shadow:0 4px 5px -2px rgba(0,0,0,.2),0 7px 10px 1px rgba(0,0,0,.14),0 2px 16px 1px rgba(0,0,0,.12) }

#app .elevationHover-1:hover{ box-shadow:0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12) }
#app .elevationHover-2:hover{ box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12) }
#app .elevationHover-3:hover{ box-shadow:0 3px 3px -2px rgba(0,0,0,.2),0 3px 4px 0 rgba(0,0,0,.14),0 1px 8px 0 rgba(0,0,0,.12) }
#app .elevationHover-4:hover{ box-shadow:0 2px 4px -1px rgba(0,0,0,.2),0 4px 5px 0 rgba(0,0,0,.14),0 1px 10px 0 rgba(0,0,0,.12) }
#app .elevationHover-5:hover{ box-shadow:0 3px 5px -1px rgba(0,0,0,.2),0 5px 8px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12) }
#app .elevationHover-6:hover{ box-shadow:0 3px 5px -1px rgba(0,0,0,.2),0 6px 10px 0 rgba(0,0,0,.14),0 1px 18px 0 rgba(0,0,0,.12) }
#app .elevationHover-7:hover{ box-shadow:0 4px 5px -2px rgba(0,0,0,.2),0 7px 10px 1px rgba(0,0,0,.14),0 2px 16px 1px rgba(0,0,0,.12) }

.cntr,.cntrHigher::after,.cntrLowest::before{
  top:50%;
  right:50%;
  position:absolute;
  transform:translate(50%,-50%)
}
.cntrHigher::after,.cntrLowest::before,.popHigher::after,.popLowest::before{
  content:''
}

.ellips{
  overflow:hidden;
  white-space:nowrap;
  text-overflow:ellipsis
}

.popHigher::after,.popLowest::before{
  top:0;
  right:0;
  width:100%;
  height:100%;
  position:absolute;
  border-radius:inherit
}

.pop,.popHover:hover,.popHigher::after,.popLowest::before{
  box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12)
}
/*
#app,button.app__basic_theme{
  color:rgba(67,67,67,.87);
  text-shadow:1px 1px 2px rgba(0,0,0,.27)
}
input.app__basic_theme{
  color:rgba(67,67,67,.93);
  text-shadow:0 1px 1px rgba(0,0,0,.27)
}

input,button{
  font-size:1em;
  font-family:Roboto,sans-serif
}

button.app__basic_theme{
  cursor:pointer;
  padding:10px 17px;
  //min-width:75px;
  border-radius:21px;
  background-color:#ffc107
}

.simplElevation,button.app__basic_theme
,#app .vueperslides__bullet{
  box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12)
}

button.app__basic_theme.disabled{
  color:rgba(67,67,67,.37);
  cursor:not-allowed;
  box-shadow:none;
  text-shadow:none;
  background-color:rgba(0,0,0,.13)
}


.app__basic_theme-bgcolor{
  color:rgba(0,0,0,.75);
  background-color:#ffb300
}
*/

/*
.searchBox{
  position:relative
}

.searchBox > *{
  height:39px;
  display:inline-block;
  vertical-align:middle
}

.searchBox > input{
  width:75%;
  padding:0 15px;
  border-radius:19px 0 0 19px;
  background-color:#fff
}

.searchBox > button{
  width:25%;
  position:relative;
  background-color:#424242;
  border-top-left-radius:0;
  border-bottom-left-radius:0
}

.searchBox > button > :only-child{
  color:#fff;
  font-size:1.25em
}
*/

/*Breaker of Old layout*/
#app header{
  height:auto;
  z-index:0;
  position:initial
}

/* Breaker of Bootstrap*/
#app label{
  font-size:inherit;
  font-weight:inherit;
  line-height:inherit;
  margin-bottom:0
}

/* Breaker of Bootstrap*/
#app h1,#app h2,#app h3,#app h4,#app h5,#app h6{
  line-height:inherit
}

article.desktopHome .vueperslides__arrow::before{
  top:50%;
  position:absolute;
  right:50%;
  content:'';
  padding:19px;
  transform:translate(50%,-50%);
  transition:.25s;
  border-radius:50%;
  background-color:#fff
}
article.desktopHome .vueperslides__arrow:hover::before{
  box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12)
}

article.desktopHome .vueperslides__arrow{
  opacity:.4;
  text-align:unset
}
article.desktopHome .vueperslides__arrow:hover{
  opacity:1
}

article.desktopHome .vueperslides__arrow.vueperslides__arrow--prev > :only-child{
  transform:rotateZ(180deg)
}

article.desktopHome .vueperslides__arrow.vueperslides__arrow--prev{
  transform:translateX(10px)
}
article.desktopHome .vueperslides__arrow.vueperslides__arrow--prev:hover{
  transform:translateX(10px) scale(1.2)
}
article.desktopHome .vueperslides__arrow.vueperslides__arrow--next{
  transform:translateX(-10px)
}
article.desktopHome .vueperslides__arrow.vueperslides__arrow--next:hover{
  transform:translateX(-10px) scale(1.2)
}


article.desktopHome .vueperslides__arrow > :only-child > :only-child{
  color:#757575;
  font-size:.4em;
  margin-right:-1px
}
</style>

<template>
<!-- <div id=app :class=[{desktop},breakpoint.name]> -->
<div id=app>
  <router-view />
</div>
</template>

<script>
//import { mapMutations } from 'vuex'
//import { CLIENT } from '@/store/mutations'
import '@/assets/css/transition.css'
import { APP as setApp, USR as setUsr, CLIENT as setClient } from '@/store/actions'
import { APP as getApp } from '@/store/getters'
import { detect } from '@/assets/js/detectmobilebrowser'

export default {
   name:'app'
  ,props:{ attrs:Object }
  //,computed:{ ...mapGetters({ breakpoint:BREAKPOINT })
  //  //,desktop() { return this.breakpoint.n > 1 }
  //}
  ,methods:{
    // ...mapMutations([ CLIENT.TYPE ])
    //,
     resize({ currentTarget:e }) {
      this.setResolution(e)
    }
    //,loaded() {
    //  window.removeEventListener('load', this.loaded)
    //  this[SET_DOC_READINESS]()
    //}
    ,setResolution({ innerWidth:w, innerHeight:h }) {
      this.$store.dispatch(setClient.RESOLUTION, { update:this.$nextTick, screen:{ w, h } })
    }
  }
  ,watch:{
     [ '$store.getters.' + getApp.FROZEN ](v) {
      document.documentElement.classList[ v? 'add' : 'remove' ]('frozen')
    }
  }
  ,async mounted() {
    await new Promise(async finish => {
      this.setResolution(window)
      let attrs = this.attrs, usr, err
      try {
        usr = JSON.parse(attrs.usr)
      } catch(e) { usr = undefined }
      try {
        err = JSON.parse(attrs.error)
        let value
        if((value = Object.values(err)).length > 0 && (value = value[0]) instanceof Array && value.length > 0) {
          err = value[0]
        } else
          err = null
      } catch(e) { err = attrs.error }
      await new Promise(finish => {
        let v, store = this.$store
        if(usr)
          store.dispatch(setUsr.ALL, usr)
        if((v = attrs.message))
          store.dispatch(setApp.MESSAGE.INFO, v)
        if((v = attrs.warning))
          store.dispatch(setApp.MESSAGE.WARNING, v)
        store.dispatch(setApp.MESSAGE.ERROR, err)
        finish()
      })
      await new Promise(finish => {
        this.$store.dispatch(setApp.STATE.TOKEN, { type:'CSRF', data:attrs.token })
        finish()
      })
      finish()
    })
    window.addEventListener('resize', this.resize)
    this.$store.dispatch(setApp.CHANGE, 1)
    //window.addEventListener('load', this.loaded)
    //this[CLIENT.TYPE](!detect(navigator.userAgent || navigator.vendor || window.opera))
  }
  ,beforeDestroy() {
    window.removeEventListener('resize', this.resize)
  }
}
</script>