<style>
 #app .desktopAccountLoggerUp > header
,#app .desktopAccountLoggerUp > [data-state] > main{
  padding:0 25px;
  position:relative
}

#app .desktopAccountLoggerUp > [data-state] > main{
  width:410px;
  margin:19px 0 7px
}

 #app .desktopAccountLoggerUp > [data-state] > main
,#app .desktopAccountLoggerUp > [data-state] > main > *{
  display:inline-block
}
#app .desktopAccountLoggerUp > [data-state] > main > *{
  width:100%
}

#app .desktopAccountLoggerUp > [data-state] > main > :nth-child(1){
  margin-top:21px;
  text-align:center
}
#app .desktopAccountLoggerUp > [data-state="0"] > main > :nth-child(1){
  font-size:.93em
  /*font-family:'Open Sans',sans-serif*/
}

#app .desktopAccountLoggerUp > [data-state] > main > input.themeBasis{
  margin:25px 0 15px;
}

#app .desktopAccountLoggerUp > [data-state] > main > :nth-child(1) > .signin{
  color:#ffa000;
  cursor:pointer;
  font-size:1.25em;
  font-weight:400
}

#app .desktopAccountLoggerUp > [data-state="0"] > footer{
  width:100%;
  color:#757575;
  text-align:center;
  line-height:1.5em;
  padding-bottom:10px
}

#app .desktopAccountLoggerUp > [data-state="0"] > footer > :nth-child(1)::first-line{
  font-size:.93em
}
#app .desktopAccountLoggerUp > [data-state="0"] > footer > :nth-child(1){
  width:100%;
  opacity:0;
  display:inline-block;
  margin-top:0;
  transition:.75s ease-out
}

#app .desktopAccountLoggerUp > [data-state="0"] > footer > :nth-child(1) > a{
  color:#ffa000
}

#app .desktopAccountLoggerUp > [data-state="0"] > footer.shown > :nth-child(1){
  opacity:1
}

#app .desktopAccountLoggerUp > [data-state="1"] > main > :nth-child(1) > b{
  display:inline-block;
  max-width:100%;
  font-size:1.3em;
  font-weight:500;
  line-height:2em
}

#app .desktopAccountLoggerUp > [data-state="1"] > main > .serialInput{
  width:81%;
  margin:17px auto;
  display:block
}

#app .desktopAccountLoggerUp > [data-state="1"] > main > .counter{
  margin-top:7px
}

#app .desktopAccountLoggerUp > [data-state="1"] > main > .resetCode{
  text-align:center;
  margin-top:15px;
  margin-bottom:5px
}
#app .desktopAccountLoggerUp > [data-state="1"] > main > .resetCode > :nth-child(1){
  cursor:pointer
}

#app .desktopAccountLoggerUp > [data-state] > main > button{
  transition:.5s ease-out
}

#app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-enter,#app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-leave-to{
  opacity:0
}
#app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-enter{
  transform:translateY(27px)
}
#app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-leave-to{
  transform:scale(1.5)
}
 #app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-enter-active
,#app .desktopAccountLoggerUp > [data-state="1"] > main > .switch-leave-active{
  transition:.5s
}

#app .desktopAccountLoggerUp > [data-state="2"] > main > :nth-child(1){
  margin-bottom:10px
}
#app .desktopAccountLoggerUp > [data-state="2"] > main > input.themeBasis{
  margin:10px 0;
}
</style>

<template>
<div class=desktopAccountLoggerUp style="
 transform:translate(50%,-50%) rotateY(180deg)
;transition:.75s
;backface-visibility:hidden
">
  <header class=themeBasis>
    <h2><span>{{ state === 1? 'Verifikasi' : 'Daftar' }}</span></h2>
    <button class="cntrLowest cntrHigher" @click=close v-if="state === 0"></button>
  </header>
  <transition name=slider mode=out-in @enter=onSliderEnter>
  <div :key=state v-if="state === 0" :data-state=state>
    <main>
      <div>
        <span>Sudah bergabung di MSPMall?</span>&#160;
        <span class=signin @click=login>Masuk</span>
      </div>
      <input class=themeBasis v-model=input type=text @keydown="e => { if(e.keyCode === 32) e.preventDefault() }" @keyup.enter=agree placeholder="Alamat e-mail" />
      <!-- <button class=g-recaptcha data-sitekey="6Le5TrMUAAAAALiKdURDf9yY73nZ17YYAS3ni7PS" data-callback=onSubmit>Submit</button> -->
      <!-- <button class=themeBasis :disabled=!allow @click=agree>Selanjutnya</button> -->
      <button class=themeBasis @click=agree>Selanjutnya</button>
    </main>
    <transition @before-enter=onRulerBeginEntering @enter=onRulerEnter>
    <footer v-if=shown>
      <p>Dengan mendaftar, saya telah menyetujui<br /><a :href=linkTermCondition()>Syarat & Ketentuan</a> serta <a :href=linkPrivacyPolicy()>Kebijakan Privasi</a>.</p>
    </footer>
    </transition>
  </div>
  <div :key=state v-if="state === 1" :data-state=state class=check>
    <main>
      <div>Masukkan kode rahasia yang telah dikirim ke<br /><b class=ellips :title=input>{{ input }}</b><br />sebelum waktu berakhir.</div>
      <transition name=switch mode=out-in @after-enter=onViewAfterEntering>
      <counter-down v-if="phase === 0" class=counter :limit=limit :cntrl=cntrl @clear=reset />
      <div class=resetCode v-if="phase === 1">Belum menerima kode rahasia? <span class=themeBasis-color @click=resendCode>Kirim ulang</span></div>
      </transition>
      <serial-digit-input ref=secretDigit class=serialInput length=6 @exact="v => { digit = v }" @reset="digit = null" />
      <button class=themeBasis @click=check :disabled=!digit>Verifikasi</button>
    </main>
  </div>
  <div :key=state v-if="state === 2" :data-state=state>
    <main>
      <div>Lengkapi data pribadi Anda dengan benar.</div>
      <input class=themeBasis @keyup.enter=register type=text v-model=requestArgs.user placeholder="Alamat e-mail" disabled />
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.name placeholder="Nama lengkap" />
      <input class=themeBasis @keyup.enter=register type=password v-model=userData.password placeholder="Kata sandi" />
      <input class=themeBasis @keyup.enter=register type=password v-model=userData.passwordConfirmation placeholder="Konfirmasi kata sandi" />
      <!--
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.email placeholder="Alamat e-mail" />
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.phone placeholder="Nomor ponsel" @keydown=onPress maxlength=13 />
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.name placeholder="Nama lengkap" />
      <input class=themeBasis @keyup.enter=register type=password v-model=userData.password placeholder="Kata sandi" />
      <date-picker :popover="{ visibility:'click' }" @input=onDateSelected value>
        <div style=line-height:normal>
          <input class=themeBasis @keyup.enter=register type=text v-model=userData.birthText placeholder="Tanggal lahir" style="width:100%;border-width:2px;margin:10px 0" />
        </div>
      </date-picker>
      <input class=themeBasis @keyup.enter=register type=text v-model=userData.birthPlace placeholder="Tempat lahir" /> -->
      <button class=themeBasis @click=register>Daftar Sekarang</button>
    </main>
  </div>
  </transition>
  <transition name=snackShown @after-enter=onSnackAfterEntering>
  <div class=snack v-if=notif :class={info:msgInfo}>
    <div>{{ notif }}</div>
  </div>
  </transition>
</div>
</template>

<script>
import { msisdn, regexEmail, digitKeys, arrowKeys, calcHeight, totalHeight, transitionName, GAP_POS } from '@/assets/js/calcs'
import { requestOTP, verifyOTP, register, ACTION } from '@/assets/js/fetchers'
import { APP as setApp } from '@/store/actions'
import { APP as getApp } from '@/store/getters'
import { MONTHS_ID } from '@/assets/js/locale.js'
import SerialDigitInput from '@/components/SerialDigitInput'
import CounterDown from '@/components/CounterDown'
//import { DatePicker } from 'v-calendar'

export default {
   name:'desktopAccountLoggerUp'
  ,data:() => ({
     state:0
    ,shown:null
    ,input:null
    ,digit:null
    ,notif:null
    ,msgInfo:null
    //,allow:null
    ,limit:120
    ,cntrl:0
    ,phase:0
    ,userData:{
      // email:null
      //,phone:null
       name:null
      ,password:null
      ,passwordConfirmation:null
      //,birthDate:null
      //,birthText:null
      //,birthPlace:null
    }
    ,requestArgs:{
       user:null
      ,type:null
    }
    ,pendingNote:null
  })
  ,props:{ source:Object }
  ,computed:{ startYear() { return new Date().getFullYear() - 12 } }
  ,methods:{
     onSubmit(token) {
      console.log(token)
    }
    ,agree() {
      let v, i, t
      function check() {
        i = this.$el.querySelector('main>input[type=text]')
        if(!(v = this.input)) {
          i.focus()
          this.notif = 'Alamat e-mail masih kosong'
          return 0
        } else this.input = v.toLocaleLowerCase()
        if((t = (x => {
          if((v = msisdn(x)))
            return 0
          if(regexEmail(x)) {
            v = x
            return 1
          }
          return false
        })(v)) === false) {
          i.focus()
          i.setSelectionRange(0, i.value.length)
          this.notif = 'Alamat e-mail kurang tepat'
          return 0
        }
        this.shown = !!(this.$el.querySelector('main>button').innerText = 'Saya setuju')
        i.setAttribute('disabled', 'disabled')
        return 1
      }
      if(check.call(this)) {
        let next = (user, type) => {
          this.$store.dispatch(setApp.ROUTER.FREEZE, true)
          this.state = 1
          this.requestArgs.user = user
          this.requestArgs.type = type
        }
        let over = f => {
          this.agree = () => {
            if(check.call(this)) f.call(this)
          }
          //this.allow = !this.allow
          i.removeAttribute('disabled')
        }
        this.agree = (that => function agree() {
          //that.allow = false
          //next(v, t)
          requestOTP(v, t)
            .then(data => {
              let code = data.code
              if(201 === code || code === 200) {
                next(v, t)
              } else {
                let x
                if((x = data.errors) && (x = x.timer)) {
                  that.limit = x
                  next(v, t)
                  that.pendingNote = data.message
                } else {
                  over(agree)
                  that.notif = data.message
                }
              }
            })
            .catch(e => {
              over(agree)
              that.notif = e.message
            })
        })(this)
      }
    }
    ,login() {
      let name = this.source.name
      if(!name) {
        this.$router.replace({ name:'login' })
      } else
      if('login' === name)
        this.$router.back()
      //window.history.length > 2? this.$router.back() : this.$router.replace({ name:'login' })
    }
    ,check() {
      let v
      if((v = this.digit)) {
        //this.state = 2
        verifyOTP(this.input, v)
          .then(data => {
            let code = data.code
            if(201 === code || code === 200) {
              this.state = 2
            } else
              this.notif = data.message
          })
          .catch(e => { this.notif = e.message })
      }
    }
    ,reset() {
      this.cntrl = 0
      setTimeout(() => {
        this.phase = 1
      }, 1000)
    }
    ,resendCode() {
      this.$refs.secretDigit.clear()
      let v = this.requestArgs, next = n => {
        this.limit = n
        this.phase = 0
      }
      requestOTP(v.user, v.type)
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
    ,async register() {
      let v, i, x;
      //i = this.$el.querySelector('main>input:nth-of-type(1)')
      //if(!(v = this.userData.email)) {
      //  i.focus()
      //  this.notif = 'Alamat e-mail harus terisi'
      //  return;
      //}
      //if(!regexEmail(v)) {
      //  i.focus()
      //  this.notif = 'Alamat e-mail kurang tepat'
      //  return;
      //} else
      //  this.userData.email = v.toLocaleLowerCase()
      //i = this.$el.querySelector('main>input:nth-of-type(2)')
      //if(!(v = this.userData.phone)) {
      //  i.focus()
      //  this.notif = 'Nomor ponsel harus terisi'
      //  return;
      //}
      //if(!(v = msisdn(v))) {
      //  i.focus()
      //  this.notif = 'Nomor ponsel kurang tepat'
      //  return;
      //} else
      //  this.userData.phone = '0' + v
      i = this.$el.querySelector('main>input:nth-of-type(2)')
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
      i = this.$el.querySelector('main>input:nth-of-type(3)')
      if(!(v = this.userData.password)) {
        i.focus()
        this.notif = 'Kata sandi harus terisi'
        return;
      }
      if(v.length < 8) {
        i.focus()
        this.notif = 'Kata sandi sekurangnya delapan karaketer.'
        return;
      }
      i = this.$el.querySelector('main>input:nth-of-type(4)')
      if(!(x = this.userData.passwordConfirmation)) {
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
      //i = this.$el.querySelector('main>input:nth-of-type(5)')
      //if(!this.userData.birthDate) {
      //  i.focus()
      //  this.notif = 'Tanggal lahir harus terisi'
      //  return;
      //}
      //i = this.$el.querySelector('main>input:nth-of-type(6)')
      //if(!this.userData.birthPlace) {
      //  i.focus()
      //  this.notif = 'Tempat lahir harus terisi'
      //  return;
      //}
      let token = this.$store.getters[ getApp.STATE.TOKEN ].data
      if(!token) {
        console.log('Token not unavailable')
        return;
      }
      await Promise.resolve(this.$el.querySelector(':scope>[data-state]>main').insertAdjacentHTML('beforeend', ACTION.register('registerUser', this.requestArgs.user, this.userData.name, this.userData.password, this.userData.passwordConfirmation, token)))
      registerUser.submit()
    }
    ,close() {
      //window.history.length > 3? this.$router.go(-2) : this.$router.replace('/')
      if(this.source && this.source.name === 'login' && window.history.length > 3) {
        this.$router.go(-2)
      } else
        this.$router.replace('/')
    }
    ,onPress(e) {
      let i = e.keyCode
      if(!(digitKeys(i) || i === 8 || i === 46 || i === 35 || i === 36 || arrowKeys(i)))
        e.preventDefault()
    }
    ,onDateSelected(v) {
      this.userData.birthDate = v
      if(!v) return null
      this.userData.birthText = v.getDate() + ' ' + MONTHS_ID[v.getMonth()] + ' ' + v.getFullYear()
    }
    ,linkTermCondition:() => ACTION.termCondition
    ,linkPrivacyPolicy:() => ACTION.privacyPolicy
    ,onRulerBeginEntering(e) {
      e.style.position = 'absolute';
     (e = this.$el).style.height = e.getBoundingClientRect().height + 'px'
    }
    ,onRulerEnter(e, finish) {
      let x, v = this.$el
      v.style.height = v.getBoundingClientRect().height + e.getBoundingClientRect().height + 'px'
      v.addEventListener(x = transitionName(), function callback() {
        v.removeEventListener(x, callback)
        v.querySelector('footer').classList.add('shown')
        e.style.position = ''
        //v.style.height = ''
        finish()
      })
    }
    ,onSliderEnter(e, finish) {
      let x, v = this.$el
      //v.style.height = calcHeight(v.firstElementChild) + calcHeight(e) + 'px'
      let siblings = []
      for(x = v.querySelector(':scope>[data-state]'); (x = x.previousElementSibling);)
        siblings.push(x)
      v.style.height = Array.from(siblings).reduce((n, c) => n + (!GAP_POS.find(e => e === getComputedStyle(c).position)? 0 : totalHeight(c)), 0) + calcHeight(e) + 'px'
      function attach() {
        v.addEventListener(x, function callback() {
          v.removeEventListener(x, callback)
          //v.style.height = ''
        })
      }
      v.addEventListener(x = transitionName(), (that => function callback(e) {
        v.removeEventListener(x, callback)
        attach()
        finish()
        if(that.phase === 0) that.cntrl = 1
        let j
        if((j = that.pendingNote)) that.notif = j
      })(this))
    }
    ,onSnackAfterEntering() {
      setTimeout((() => () => {
        this.notif = ''
        this.pendingNote = ''
        let dispatch = this.$store.dispatch
        this.msgInfo = null
        dispatch(setApp.MESSAGE.INFO, null)
        dispatch(setApp.MESSAGE.ERROR, null)
        dispatch(setApp.MESSAGE.WARNING, null)
      }).call(this), 5000)
    }
    ,onViewAfterEntering(e) {
      if(this.phase === 0) this.cntrl = 1
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
  //,watch:{ input(v) { this.allow = msisdn(v) || regexEmail.test(this.input) } }
  ,components:{ SerialDigitInput, CounterDown }
}
</script>