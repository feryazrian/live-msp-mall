<style>
 #app .desktopAccountLoggerIn > header
,#app .desktopAccountLoggerIn > [data-state] > main{
  padding:0 25px;
  position:relative
  /*padding-left:25px;
  padding-right:25px*/
}

#app .desktopAccountLoggerIn > [data-state] > main{
  width:410px;
  margin:10px 0 27px;
  transition:inherit
}

#app .desktopAccountLoggerIn > [data-state] > main > *{
  width:100%;
  display:block
}

#app .desktopAccountLoggerIn > [data-state] > main > :last-child > :nth-child(1)::before{
  top:50%;
  right:0;
  width:100%;
  height:2px;
  content:'';
  margin-top:1px;
  position:absolute;
  transform:translateY(-50%);
  background-color:#e6e6e6
}
#app .desktopAccountLoggerIn > [data-state] > main > :last-child > :nth-child(1){
  margin:35px 0 17px;
  position:relative;
  text-align:center
}

#app .desktopAccountLoggerIn > [data-state] > main > :last-child > :nth-child(1) > :nth-child(1){
  padding:0 7px;
  position:relative;
  background-color:#fff
}

#app .desktopAccountLoggerIn > [data-state] > main > form{
  display:none
}

#app .desktopAccountLoggerIn > [data-state] > main > aside{
  width:91%;
  font-size:.87em;
  margin:7px auto 37px;
  display:flex
}

#app .desktopAccountLoggerIn > [data-state] > main > aside > :nth-child(1){
  flex-grow:1;
  cursor:pointer
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor{
  width:87%;
  display:block;
  margin:10px auto 0;
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor > button{
  width:47.5%;
  color:#fff;
  border:0;
  padding:13px 10px;
  display:inline-flex;
  align-items:center;
  border-radius:23px;
  justify-content:center
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor > button > *{
  display:inline-block;
  vertical-align:middle
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor > button > :nth-child(1){
  font-size:1.25em;
  margin-right:7px;
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor > button.f{
  margin-right:2.5%;
  background-color:#3c5a99
}

#app .desktopAccountLoggerIn > [data-state] > main .vendor > button.g{
  margin-left:2.5%;
  background-color:#db4437
}

#app .desktopAccountLoggerIn > [data-state] > footer .signup{
  color:#ffa000;
  cursor:pointer
}
</style>

<template>
<section class=desktopAccountLoggerIn style="
 transform:translate(50%,-50%)
;backface-visibility:hidden
">
  <header class=themeBasis>
    <h2><span>Masuk</span></h2>
    <button class="cntrLowest cntrHigher" @click=close></button>
  </header>
  <transition name=slider mode=out-in>
  <div :key=state v-if="state === 0" :data-state=state>
    <main>
      <input class=themeBasis v-model=usr @keyup.enter=signin name=email type=text placeholder="Alamat e-mail" autofocus="" autocomplete=off />
      <input class=themeBasis v-model=pwd @keyup.enter=signin name=password type=password placeholder="Kata sandi" />
      <aside>
        <!-- <span @click="state = 1">Lupa kata sandi?</span> -->
        <!-- <slidey-switch label="Ingatkan saya" /> -->
      </aside>
      <button class=themeBasis @click=signin>Selanjutnya</button>
      <div>
        <div><span>atau masuk dengan</span></div>
        <div class=vendor>
          <button class=f @click=signFacebook>
            <font-awesome :icon="[ 'fab' , 'facebook' ]" />
            <span>Facebook</span>
          </button>
          <button class=g @click=signGoogle>
            <font-awesome :icon="[ 'fab' , 'google' ]" />
            <span>Google</span>
          </button>
        </div>
      </div>
    </main>
    <footer class=themeBasis>
      <span>Belum bergabung?</span>&#160;
      <span class=signup @click="$router.push({ name:'register' })">Daftar sekarang</span>
    </footer>
  </div>
  <div :key=state v-if="state === 1" :data-state=state>
    <main>
      <input class=themeBasis type=text placeholder="Alamat e-mail" />
      <input class=themeBasis type=text placeholder="Kata sandi lama" />
      <input class=themeBasis type=text placeholder="Kata sandi baru" />
    </main>
  </div>
  </transition>
  <transition name=snackShown @after-enter=onSnackAfterEntering>
  <div class=snack v-if=notif :class={info:msgInfo}>
    <div>{{ notif }}</div>
  </div>
  </transition>
</section>
</template>

<script>
import { regexEmail } from '@/assets/js/calcs'
import { login, vendorAuth, redirectGoogle, redirectFacebook } from '@/assets/js/fetchers'
import { APP as getApp } from '@/store/getters'
import { APP as setApp } from '@/store/actions'
import { KJUR } from 'jsrsasign'
//import SlideySwitch from '@/components/SlideySwitch'

export default {
   name:'desktopAccountLoggerIn'
  ,data:() => ({
     usr:null
    ,pwd:null
    ,state:0
    ,notif:null
    ,msgInfo:null
  })
  ,props:{ source:Object }
  ,methods:{
     signin({ currentTarget:e }) {
      if(!this.usr || !regexEmail(this.usr)) {
        return this.$el.querySelector('main>input[type=text]').focus()
      }
      if(!this.pwd/* || this.pwd.length < 8*/) {
        return this.$el.querySelector('main>input[type=password]').focus()
      }
      if(!(e = e.parentElement).querySelector('#signin')) {
        let token = this.$store.getters[ getApp.STATE.TOKEN ].data
        if(!token) {
          console.log('Token not unavailable')
          return;
        }
        Promise.resolve(e.insertAdjacentHTML('afterbegin', `<form id=signin method=POST action="/login" style=display:none><input type=hidden name="_token" value=${ token } /><input type=hidden name=remember value=on /></form>`))
          .then(() => {
            e.querySelectorAll(':scope > input[name]').forEach(e => e.setAttribute('form', 'signin'))
            signin.submit()
          })
        //signin.querySelector('input[name=_token]').setAttribute('value', document.head.querySelector('meta[name=csrf-token]').getAttribute('content'))
      }
      //login(this.usr, this.pwd)
      //  .then(data => {
      //    let n
      //    if((n = data.code) === 200 || n === 201) {
      //      data = KJUR.jws.JWS.parse(data.items.access_token)
      //      this.$store.dispatch('setUsr', data.payloadObj)
      //      //console.log(data.payloadObj)
      //      let v = document.head.querySelector('meta[name=csrf-token]').getAttribute('content'), cookie
      //      reLogin(this.usr, this.pwd, '_token', v, cookie = document.cookie)
      //        .then(async data => {
      //          for(let item of data.headers.entries())
      //            console.log(item)
      //          if(data.status === 200) {
      //            //function setCookie(k, v, d, attrs = { path:'/', expires:(n => {
      //            //  const D = new Date()
      //            //  D.setTime(D.getTime() + (n * 24 * 60 * 60 *1000))
      //            //  return D.toUTCString()
      //            //})(d) }) {
      //            //  let c = encodeURIComponent(k) + "=" + encodeURIComponent(v)
      //            //  for(let n in attrs)
      //            //    c += ";" + n + "=" + attrs[n]
      //            //  document.cookie = c
      //            //  console.log(document.cookie)
      //            //}
      //            document.cookie = cookie
      //            let html = await data.text()
      //            document.write(html)
      //            console.log('redirect')
      //            //window.open('/', '_self')
      //          }
      //        })
      //        .catch(e => {
      //          console.log(e)
      //        })
      //    } else {
      //      //console.log(data.message)
      //    }
      //  })
      //  .catch(e => {
      //    console.log(e)
      //  })
    }
    ,signGoogle() {
      redirectGoogle()
      //vendorAuth('google')
      //  .then(data => {
      //    window.open(data.items, '_self')
      //  })
      //  .catch(e => {
      //    console.log(e)
      //  })
    }
    ,signFacebook() {
      redirectFacebook()
      //vendorAuth('facebook')
      //  .then(data => {
      //    window.open(data.items, '_self')
      //  })
      //  .catch(e => {
      //    console.log(e)
      //  })
    }
    ,close() {
      //if(!this.source.name || window.history.length < 3) {
      //  this.$router.replace('/')
      //} else
      //  this.$router.back()
      if(this.source && this.source.name) {
        //this.$router.back()
        if(window.history.length > 3)
          this.$router.back()
        else
          this.$router.push('/')
      } else
        this.$router.replace('/')
    }
    ,onSnackAfterEntering() {
      setTimeout((() => () => {
        this.notif = ''
        let dispatch = this.$store.dispatch
        this.msgInfo = null
        dispatch(setApp.MESSAGE.INFO, null)
        dispatch(setApp.MESSAGE.ERROR, null)
        dispatch(setApp.MESSAGE.WARNING, null)
      }).call(this), 5000)
    }
  }
  ,mounted() {
    let v, getters = this.$store.getters
    if((v = getters[ getApp.MESSAGE.ERROR ]))
      this.notif = v
    if((v = getters[ getApp.MESSAGE.WARNING ]))
      this.notif = v
    if((v = getters[ getApp.MESSAGE.INFO ])) {
      this.msgInfo = true
      this.notif = v
    }
  }
  //,components:{ SlideySwitch }
}
</script>