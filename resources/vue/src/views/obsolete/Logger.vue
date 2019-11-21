<style scoped>
.logger{
  top:0;
  right:0;
  width:100%;
  height:100%;
  position:absolute;
  background-color:rgba(0,0,0,.75)
}

article{
  top:3%;
  right:50%;
  position:absolute;
  transform:translateX(50%);
  border-radius:7px;
  background-color:#fafafa
}
article::before{
  width:100%;
  height:100%;
  padding:11px;
  box-sizing:content-box;
  border-radius:11px;
  background-color:rgba(255,255,255,.25)
}

 article > .signin
,article > footer{
  padding-left:15px;
  padding-right:15px
}

article > header{
  border:1px solid red;
}

article > header > button{
  top:0;
  right:0;
  border:5px solid rgba(255,255,255,.95);
  padding:23px;
  position:absolute;
  transform:translate(50%,-50%);
  border-radius:50%;
  background-color:#fb0
}
 article > header > button::before
,article > header > button::after{
  width:75%;
  height:4px;
  border-radius:3px;
  background-color:#fff
}
article > header > button::before{
  transform:translate(50%,-50%) rotateZ(45deg)
}
article > header > button::after{
  transform:translate(50%,-50%) rotateZ(-45deg)
}

article > .signin{
  position:relative;
  padding-top:10px;
  padding-bottom:10px
}

article > footer{
  /* border:1px solid blue; */
  position:relative;
  padding-top:5px;
  padding-bottom:10px
}
article > footer button.plain{
  width:75%;
  margin:0 auto;
  display:block;
  padding:10px 15px;
  font-size:1.25em;
  border-radius:23px;
  background-color:#fb0
}

button.plain:hover,button.plain:focus{
  background-color:#ffa000
}
</style>

<template>
<div class="logger">
  <article class="centerHead" :class="{ disabled }">
    <header>
      <h2>Masuk</h2>
      <button ref="close" class="plain centerHead centerFoot" @click="$router.push('/')"></button>
    </header>
    <form class="signin">
      <!-- <v-text-field ref="username" :disabled=disabled @input="$v.username.value.$touch()" @blur="$v.username.value.$touch()" @keyup.enter="signin" color="amber darken-2" v-model.trim=username.value :error-messages="errorUsername" label="E-mail yang telah terdaftar" /> -->
      <!-- <v-text-field ref="password" :disabled=disabled @input="$v.password.value.$touch()" @blur="$v.password.value.$touch()" @keyup.enter="signin" color="amber darken-2" v-model=password.value :error-messages="errorPassword" label="Kata sandi" :append-icon="password.shown? 'visibility' : 'visibility_off'" :type="password.shown? 'text' : 'password'" @click:append="password.shown = !password.shown" /> -->
      <!-- <v-snackbar v-model=notifier.shown color="red darken-4" top>{{ notifier.value }}</v-snackbar> -->
      <float-input label="E-mail Anda yang telah terdaftar" />
    </form>
    <!-- <v-progress-circular v-show=disabled class="center progressCircular" indeterminate color="amber" size=93 width=9></v-progress-circular> -->
    <footer>
      <!-- <v-btn large :loading=disabled color="amber darken-4" class="white--text" @click="signin">OK</v-btn> -->
      <button class="plain" @click=signin>Masuk</button>
      <div>atau masuk dengan</div>
      <button>Facebook</button>
      <button>Google</button>
    </footer>
  </article>
</div>
</template>

<script>
import FloatInput from '@/components/FloatInput'
import { validationMixin } from 'vuelidate'
import { required, email, minLength } from 'vuelidate/lib/validators'

export default {
   name:'Logger'
  ,data:() => ({
  //   username:{ value:'' }
  //  ,password:{ value:'', shown:false }
     notifier:{ value:'', shown:false }
    ,disabled:null
  })
  ,components:{ FloatInput }
  ,methods:{
     signin() {
  //    if(this.disabled) return;
  //    this.$v.$touch()
  //    if(this.$v.username.$error) {
  //      this.$refs.username.focus()
  //    } else
  //    if(this.$v.password.$error) {
  //      this.$refs.password.focus()
  //    } else
  //    if(!this.$v.$error) {
  //      this.disabled = !false
  //      this.password.shown = false
  //      let label = document.head.querySelector('meta[name="label"]').getAttribute('content')
  //      let token = document.head.querySelector('meta[name="token"]').getAttribute('content')
  //      if(!label || !token) {
  //        this.notifier.value = 'Proses tidak dapat dilanjutkan karena isu keamanan data'
  //        this.notifier.shown = !false
  //        this.reset()
  //        return
  //      }
  //      fetch('/login', {
  //         method:'POST'
  //        ,headers:{ 'Content-Type':'application/json', [label]:token }
  //        ,body:JSON.stringify({ usr:this.username.value, pwd:this.password.value })
  //      })
  //      .then(response => {
  //        console.log(response)
  //        return response.status === 200? response.json() : Promise.reject(response.json())
  //      })
  //      .then(response => {
  //        console.log('OK')
  //        console.log(response)
  //        this.reset()
  //      })
  //      .catch(error => {
  //        console.log('Error!')
  //        console.log(error)
  //        this.reset()
  //      })
  //      //let xhttp = new new XMLHttpRequest()
  //      //xhttp.open('POST', '/login', true)
  //      //xhttp.setRequestHeader('Content-Type', 'application/json')
  //      //xhttp.setRequestHeader(header, token)
  //      //xhttp.send(JSON.stringify({ username:this.username, password:this.password }))
  //      //xhttp.onreadystatechange = function() {
  //      //  if(this.readyState == 4) {
  //      //    console.log(this.status)
  //      //    console.log(this.responseText)
  //      //    console.log(this)
  //      //  }
  //      //}
  //    }
  //  }
  //  ,reset() {
  //    this.disabled = false
  //    this.$refs.close.focus()
  //    this.username.value = ''
  //    this.password.value = ''
  //    this.$v.$reset()
    }
  }
  //,computed:{
  //   errorUsername() {
  //    if(this.$v.username.value.$dirty) {
  //      if(!this.$v.username.value.required)
  //        return 'E-mail harus terisi'
  //      if(!this.$v.username.value.email)
  //        return 'Alamat e-mail yang Anda berikan salah'
  //    }
  //    return null
  //  }
  //  ,errorPassword() {
  //    if(this.$v.password.value.$dirty) {
  //      if(!this.$v.password.value.required)
  //        return 'Kata sandi harus terisi'
  //      if(!this.$v.password.value.min)
  //        return 'Minimal 8 karakter'
  //    }
  //    return null
  //  }
  //}
  ,validations:{
     username:{ value:{ required, email } }
    ,password:{ value:{ required, min:minLength(8) } }
  }
  ,mixins:[ validationMixin ]
}
</script>