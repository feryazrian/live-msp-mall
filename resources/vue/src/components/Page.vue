<style>
#app .page{
  width:100%;
  position:relative;
  margin-left:auto!important;
  margin-right:auto!important
}
</style>

<template>
<div class=page :style="{ maxWidth:[ width? width + 'px' : '' ] }">
  <slot :breakpointType=breakpointType :breakpointName=breakpointName :phone=phone :react=react />
</div>
</template>

<script>
import { BREAKPOINT, APP as getApp } from '@/store/getters'
import { mapGetters } from 'vuex'

export default {
   name:'page'
  ,data:() => ({
     breakpointType:null
    ,breakpointName:null
    ,react:null
    ,phone:null
    ,width:null
  })
  ,computed:{ ...mapGetters({ appStatus:getApp.STATUS, breakpoint:BREAKPOINT }) }
  ,methods:{
     check(n) {
      let b = true
      if(n === 0) {
        (n = this.$el.style).paddingLeft = n.paddingRight = '1.5%'
      } else
      if(n === 1) {
        (n = this.$el.style).paddingLeft = n.paddingRight = '2.5%'
      } else {
        (n = this.$el.style).paddingLeft = n.paddingRight = ''
        b = !b
      }
      return b
    }
    ,reset() {
      this.set(this.breakpoint.n)
      this.$watch('breakpoint.n', v => {
        this.set(v)
      })
      this.$watch('breakpoint.width', v => {
        if(v < this.breakpoint.maxWidth)
          this.react = v
      })
    }
    ,set(v) {
      let { name, maxWidth:width } = this.breakpoint
      this.breakpointName = name
      this.phone = this.check(this.breakpointType = v)
      this.$emit('breakpoint', v)
      this.width = width
    }
  }
  ,watch:{ appStatus(v) { if(1 === v) this.reset() }
  //   'breakpoint.width'(v) {
  //    console.log('>>> ' + v)
  //    this.react = v
  //  }
  //  ,'breakpoint.n'(v) { console.log('+ ' + v);this.set(v) }
  }
  ,mounted() {
    if(this.appStatus === 1) this.reset()
  }
}
</script>