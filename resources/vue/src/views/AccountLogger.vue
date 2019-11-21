<template>
<phone-account-logger v-if=phone :action=action :source=source @close=onClose />
<desktop-account-logger v-else :action=action :source=source />
</template>

<script>
import { BREAKPOINT, APP as getApp } from '@/store/getters'
import { TITLE } from '@/assets/js/words'
import AccountLogger from '@/components/AccountLogger'
import PhoneAccountLogger from '@/components/phone/AccountLogger'

export default {
   name:'accountLogger'
  ,data:() => ({
     phone:null
    ,close:null
    ,source:null
  })
  ,props:{
    action:Number
  }
  ,computed:{ breakpoint() { return this.$store.getters[ BREAKPOINT ] } }
  ,methods:{
    onClose() {
      this.close = 1
      this.$router.push('/')
    }
  }
  ,mounted() {
    if(this.breakpoint) {
      let v = this.breakpoint.n
      this.phone = 1 >= v
    }
  }
  ,beforeRouteEnter(into, from, next) {
    document.head.querySelector('title').innerText = TITLE[into.name]
    next(that => { that.source = from })
  }
  ,beforeRouteLeave(into, from, next) {
    let b = this.$store.getters[ getApp.ROUTER.FROZEN ]
    if(!b) {
      if(this.close) {
        document.body.classList.remove('frozen')
        document.documentElement.classList.remove('frozen')
      }
      document.head.querySelector('title').innerText = TITLE[into.path]
    }
    next(!b)
  }
  ,components:{ desktopAccountLogger:AccountLogger, PhoneAccountLogger }
}
</script>