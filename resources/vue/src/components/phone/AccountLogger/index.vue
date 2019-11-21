<style>
#app .phoneAccountLogger > header{
  top:0;
  width:100%;
  display:flex;
  padding:5px 15px;
  position:fixed;
  align-items:center;
  background-color:#fff
}

#app .phoneAccountLogger > header > :nth-child(1){
  width:20px;
  height:2px;
  cursor:pointer;
  position:relative;
  transform:translateY(50%);
  border-radius:1px;
  background-color:#888
}
#app .phoneAccountLogger > header > :nth-child(1)::before{
  top:50%;
  left:0;
  width:67%;
  height:2px;
  content:'';
  position:absolute;
  transform:rotateZ(45deg) translateY(-50%);
  border-radius:1px;
  transform-origin:left top;
  background-color:#888
}
#app .phoneAccountLogger > header > :nth-child(1)::after{
  top:50%;
  left:0;
  width:67%;
  height:2px;
  content:'';
  position:absolute;
  transform:rotateZ(-45deg) translateY(-50%);
  border-radius:1px;
  transform-origin:left top;
  background-color:#888
}

#app .phoneAccountLogger > header > h2{
  margin:7px 15px;
  font-size:1.25em;
  font-weight:400
}

#app .phoneAccountLogger > main{
  margin-top:75px;
  padding-left:15px;
  padding-right:15px
}

#app .phoneAccountLogger > main > button.themeBasis{
  width:100%;
  border:none;
  padding:13px;
  margin:10px 0;
  font-size:1.25em;
  border-radius:25px
}

#app .phoneAccountLogger > main[data-state="1"] > :nth-child(4){
  width:100%;
  padding:15px 25px;
  font-size:.87em;
  text-align:center
}

#app .phoneAccountLogger > main[data-state="0"] > footer{
  font-size:.87em;
  margin-top:25px;
  text-align:center;
}
#app .phoneAccountLogger > main[data-state="0"] > footer > p{
  margin:5px;
}
#app .phoneAccountLogger > main[data-state="0"] > footer > p > a{
  color:#ffa000;
  font-size:1.05em
}

#app .phoneAccountLogger > main[data-state="0"] > .toLogin{
  margin:25px 0 15px;
  text-align:center
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin p{
  margin:0
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin > :nth-child(1){
  position:relative;
  font-size:.73em
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin > :nth-child(1) > p{
  padding:0 7px;
  display:inline;
  position:relative;
  background-color:#fff
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin > :nth-child(1)::before{
  top:50%;
  right:0;
  width:100%;
  height:2px;
  content:'';
  position:absolute;
  transform:translateY(-50%);
  background-color:#e6e6e6
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin > :nth-child(2){
  color:#ffa000;
  margin:5px;
  padding:3px 5px;
  font-size:1.25em
}
#app .phoneAccountLogger > main[data-state="0"] > .toLogin > :nth-child(2) > :only-child{
  cursor:pointer
}

#app .phoneAccountLogger > main[data-state="0"] > .candidateUsr{
  font-size:.93em;
  margin-top:25px;
  text-align:center;
}
#app .phoneAccountLogger > main[data-state="0"] > .candidateUsr > .highlight{
  margin:7px 0;
  font-size:1.5em;
  font-weight:500
}
#app .phoneAccountLogger > main[data-state="0"] > .candidateUsr > button{
  border:none;
  padding:13px 50px;
  margin-top:10px;
  border-radius:25px
}

#app .phoneAccountLogger > main[data-state="0"] > .register{
  padding:10px
}

#app .phoneAccountLogger > main[data-state="0"] > .register > input{
  width:100%;
  border:2px solid #e6e6e6;
  display:block;
  padding:12px 17px;
  transition:.25s;
  margin-bottom:10px;
  border-radius:23px
}
#app .phoneAccountLogger > main[data-state="0"] > .register > input:focus{
  border-color:#ffc107
}

#app .phoneAccountLogger > main[data-state="0"] > .register > button{
  width:100%;
  border:none;
  display:inline-block;
  padding:15px 25px;
  margin-top:15px;
  border-radius:25px
}

#app .phoneAccountLogger > .snack{
  top:0;
  width:53%;
  right:50%;
  color:#fff;
  position:fixed;
  transform:translate(50%,47px);
  pointer-events:none
}
#app .phoneAccountLogger > .snack > :only-child{
  position:relative;
  font-size:.87em;
  text-align:center
}
#app .phoneAccountLogger > .snack::before{
  top:50%;
  right:50%;
  width:100%;
  height:100%;
  content:'';
  padding:13px 15px;
  position:absolute;
  transform:translate(50%,-50%);
  box-sizing:content-box;
  box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12);
  border-radius:3px;
  background-color:#D32F2F
}
#app .phoneAccountLogger > .snackShown-enter,#app .desktopAccountLogger .snackShown-leave-to{
  opacity:0;
  transform:translate(50%,-100%)
}
#app .phoneAccountLogger > .snackShown-enter-active,#app .desktopAccountLogger .snackShown-leave-active{
  transition:.25s ease-out
}
</style>

<template>
<div class=phoneAccountLogger style="
 position:absolute
;top:0
;right:0
;width:100%
;z-index:3
;padding-bottom:25px
;background-color:#fff
">
  <main v-if="action == 1" :data-state=action>
    <float-input @input="({ value:v }) => usr = v" name=usr label="Alamat e-mail" />
    <float-input @input="({ value:v }) => pwd = v" name=pwd label="Kata sandi" type=password />
    <button class=themeBasis @click=login>Masuk</button>
    <div>Belum bergabung? <router-link :to="{ name:'register' }" style="color:#ffa000">Daftar sekarang</router-link></div>
  </main>
  <main v-if="action == 0" :data-state=action>
    <template v-if="state == 0">
    <float-input @input="({ value:v }) => usr = v" :sleep=input.regis.disabled name=usr label="Alamat e-mail" />
    <button class=themeBasis @click=agree :disabled=input.regis.disabled>Selanjutnya</button>
    <footer v-if=!input.regis.disabled>
      <p>Dengan mendaftar, saya telah menyetujui</p>
      <p><a :href=linkTermCondition()>Syarat & Ketentuan</a> serta <a :href=linkPrivacyPolicy()>Kebijakan Privasi</a>.</p>
    </footer>
    <div v-if=verificatorShown class=candidateUsr>
      <span>Masukkan kode rahasia yang telah dikirim ke</span>
      <div class="highlight ellips">{{ usr }}</div>
      <span>sebelum waktu berakhir.</span>
      <counter-down v-if="phase === 0" :limit=limit :cntrl=cntrl @clear=reset style="margin-top:15px;font-size:1.25em" />
      <template v-if="phase === 1">
      <span class=resetCode style="margin:17px 0 10px">Belum menerima kode rahasia?</span>
      <span class=themeBasis-color @click=resendCode style="cursor:pointer">Kirim ulang</span>
      </template>
      <div style="padding:0 10px;margin:15px 0">
        <serial-digit-input ref=secretDigit length=6 @exact="v => { digit = v }" @reset="digit = null" />
      </div>
      <button class=themeBasis @click=check :disabled=!digit>Verifikasi</button>
    </div>
    <div class=toLogin v-if=!input.regis.disabled>
      <div><p>Sudah bergabung?</p></div>
      <p><span @click=toLogin>Masuk</span></p>
    </div>
    </template>
    <template v-if="state == 1">
    <div style="text-align:center;margin:5px 0 15px">Lengkapi data pribadi Anda dengan benar.</div>
    <div class=register>
      <input class=themeBasis @keyup.enter=register type=text v-model=usr placeholder="Alamat e-mail" disabled />
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.name placeholder="Nama lengkap" />
      <input class=themeBasis @keyup.enter=register type=password v-model=userData.pwd[0] placeholder="Kata sandi" />
      <input class=themeBasis @keyup.enter=register type=password v-model=userData.pwd[1] placeholder="Konfirmasi kata sandi" />
      <button class=themeBasis @click=register>Daftar Sekarang</button>
    </div>
    </template>
  </main>
  <header class=elevation-3>
    <div @click="$emit('close')"></div>
    <h2>{{ action === 1? 'Masuk' : 'Daftar' }}</h2>
  </header>
  <transition name=snackShown @after-enter=onSnackAfterEntering>
  <div class=snack v-if=notif>
    <div>{{ notif }}</div>
  </div>
  </transition>
</div>
</template>

<script>
import { regexEmail } from '@/assets/js/calcs'
import { ACTION, requestOTP, verifyOTP } from '@/assets/js/fetchers'
import { APP as getApp } from '@/store/getters'
import FloatInput from '@/components/FloatInput'
import CounterDown from '@/components/CounterDown'
import SerialDigitInput from '@/components/SerialDigitInput'

export default {
   name:'phoneAccountLogger'
  ,data:() => ({
     usr:null
    ,pwd:null
    ,notif:null
    ,msgInfo:null
    ,input:{ regis:{ disabled:false } }
    ,limit:120
    ,cntrl:0
    ,phase:0
    ,digit:null
    ,state:0
    ,userData:{ name:null, pwd:[ null, null ] }
    ,verificatorShown:false
  })
  ,props:{
     action:Number
    ,source:Object
  }
  ,methods:{
     async login({ currentTarget:e }) {
      let v, i
      i = this.$el.querySelector(':scope>main>* input[name=usr]')
      if(!(v = this.usr)) {
        i.focus()
        this.notif = 'Alamat e-mail belum terisi'
        return;
      }
      if(!regexEmail(v)) {
        i.focus()
        this.notif = 'Alamat e-mail kurang tepat'
        return;
      } else
        this.usr = v.toLocaleLowerCase()
      i = this.$el.querySelector(':scope>main>* input[name=pwd]')
      if(!(v = this.pwd)) {
        i.focus()
        this.notif = 'Kata sandi belum terisi'
        return;
      }
      //if(v.length < 8) {
      //  i.focus()
      //  this.notif = 'Kata sandi sekurangnya delapan karaketer.'
      //  return;
      //}
      let token = this.$store.getters[ getApp.STATE.TOKEN ].data
      if(!token) {
        console.log('Token not unavailable')
        return;
      }
      await Promise.resolve(e.parentElement.insertAdjacentHTML('beforeend', ACTION.login(this.usr, this.pwd, token)))
      signin.submit()
    }
    ,agree() {
      if(this.input.regis.disabled) return;
      let v, i
      i = this.$el.querySelector(':scope>main>* input[name=usr]')
      if(!(v = this.usr)) {
        i.focus()
        this.notif = 'Alamat e-mail belum terisi'
        return;
      }
      if(!regexEmail(v)) {
        i.focus()
        this.notif = 'Alamat e-mail kurang tepat'
        return;
      } else
        this.usr = v.toLocaleLowerCase()
      let token = this.$store.getters[ getApp.STATE.TOKEN ].data
      this.input.regis.disabled = true
      requestOTP(v)
        .then(data => {
          let code = data.code
          if(201 === code || code === 200) {
            Promise.resolve(this.verificatorShown = true)
              .then(() => {
                this.cntrl = 1
              })
          } else {
            let x
            if((x = data.errors) && (x = x.timer)) {
              this.limit = x
              Promise.resolve(this.verificatorShown = true)
                .then(() => {
                  this.cntrl = 1
                })
            } else {
              this.notif = data.message
              this.input.regis.disabled = false
            }
          }
        })
        .catch(e => {
          this.notif = e.message
          this.input.regis.disabled = false
        })
    }
    ,check() {
      let v
      if((v = this.digit)) {
        verifyOTP(this.usr, v)
          .then(data => {
            let code = data.code
            if(201 === code || code === 200) {
              this.state = 1
            } else
              this.notif = data.message
          })
          .catch(e => { this.notif = e.message })
      }
    }
    ,async register() {
      let v, i, x;
      i = this.$el.querySelector(':scope>main[data-state="0"]>.register>input:nth-of-type(2)')
      if(!(v = this.userData.name)) {
        i.focus()
        this.notif = 'Nama lengkap Anda harus terisi'
        return;
      }
      function beautify(v) {
        let x = '', i = -1, c, words = v.split(' ')
        for(let n = words.length - 1; ++i < n; )
          x += (c = words[i]).charAt(0).toLocaleUpperCase() + c.substring(1).toLocaleLowerCase() + ' '
        return x + (c = words[i]).charAt(0).toLocaleUpperCase() + c.substring(1).toLocaleLowerCase()
      }
      this.userData.name = beautify(v)
      i = this.$el.querySelector(':scope>main[data-state="0"]>.register>input:nth-of-type(3)')
      if(!(v = this.userData.pwd[0])) {
        i.focus()
        this.notif = 'Kata sandi harus terisi'
        return;
      }
      if(v.length < 8) {
        i.focus()
        this.notif = 'Kata sandi sekurangnya delapan karaketer.'
        return;
      }
      i = this.$el.querySelector(':scope>main[data-state="0"]>.register>input:nth-of-type(4)')
      if(!(x = this.userData.pwd[1])) {
        i.focus()
        this.notif = 'Konfirmasi kata sandi harus terisi'
        return;
      }
      if(x.length < 8) {
        i.focus()
        this.notif = 'Kata sandi sekurangnya delapan karaketer.'
        return;
      }
      if(v != x) {
        i.focus()
        this.notif = 'Konfirmasi kata sandi salah.'
        return;
      }
      let token = this.$store.getters[ getApp.STATE.TOKEN ].data
      if(!token) {
        console.log('Token not unavailable')
        return;
      }
      this.toLogin()
      await Promise.resolve(this.$el.querySelector(':scope>main[data-state="0"]>.register').insertAdjacentHTML('beforeend', ACTION.register('registerUser', this.usr, this.userData.name, this.userData.pwd[0], this.userData.pwd[1], token)))
      registerUser.submit()
    }
    ,reset() {
      this.cntrl = 0
      setTimeout(() => {
        this.phase = 1
      }, 1000)
    }
    ,resendCode() {
      this.$refs.secretDigit.clear()
      let v = this.usr, next = n => {
        this.limit = n
        Promise.resolve(this.phase = 0)
          .then(() => {
            this.cntrl = 1
          })
      }
      requestOTP(this.usr)
        .then(data => {
          let code = data.code
          if(201 === code || code === 200) {
            next(120)
          } else {
            if((v = data.errors) && (v = v.timer))
              next(v)
            this.notif = data.message
          }
        })
        .catch(e => { this.notif = e.message })
    }
    ,toLogin() {
      let name = this.source.name
      if(!name) {
        this.$router.replace({ name:'login' })
      } else
      if('login' === name)
        this.$router.back()
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
    ,linkTermCondition:() => ACTION.termCondition
    ,linkPrivacyPolicy:() => ACTION.privacyPolicy
  }
  ,mounted() {
    window.scrollTo({ top:0, left:0, behavior:'smooth' })
    document.documentElement.classList.remove('frozen')
    document.body.classList.add('white')
    let callback
    document.addEventListener('scroll', ((client, { style }) => (callback = e => {
      document.removeEventListener('scroll', callback)
      let n
      if((n = client.getBoundingClientRect().height)) {
        style.overflow = 'hidden'
        style.height = n + 'px'
      }
    }))(this.$el, this.$el.parentElement.parentElement))
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
  ,beforeDestroy() {
    document.body.classList.remove('white')
    document.body.classList.remove('frozen')
    let v
    if((v = this.$el) && (v = v.parentElement) && (v = v.parentElement)) {
      let x = v.style
      x.height = ''
      x.overflow = ''
    }
  }
  ,components:{ FloatInput, CounterDown, SerialDigitInput }
}
</script>