<styled>
#app .counterDown > :nth-child(1){
  border:1px dotted red;
  width:33%;
  height:43px;
  margin:0 auto;
  display:block;
  position:relative;
  pointer-events:none
}
/*
#app .counterDown > :nth-child(1)::before,#app .counterDown > :nth-child(1)::after{
  top:50%;
  content:':';
  position:absolute;
  font-size:1.15em;
  margin-top:1px;
  font-weight:700
}

#app .counterDown > :nth-child(1)::before{
  left:33.33%;
  transform:translate(-50%,-50%)
}

#app .counterDown > :nth-child(1)::after{
  right:33.33%;
  transform:translate(50%,-50%)
}
*/
#app .counterDown > :nth-child(1) > .D{
  top:0;
  left:0;
  padding:3px 9px;
  position:absolute;
  font-size:.83em;
  transform:translateX(-100%);
  border-radius:9px;
  background-color:#e9e9e9
}

#app .counterDown > :nth-child(1) > :not(.D){
  /*border:1px solid blue;*/
  width:33.3%;
  height:100%;
  padding:7px 5px;
  position:absolute;
  font-size:1.5em;
  text-align:center;
  font-family:'Roboto Mono',monospace
}

#app .counterDown > :nth-child(1) > .H{
  left:0
}

#app .counterDown > :nth-child(1) > .M{
  right:50%;
  transform:translateX(50%)
}

#app .counterDown > :nth-child(1) > .S{
  right:0
}

#app .counterDown > :nth-child(1) > .dropDown-enter,#app .counterDown > :nth-child(1) > .dropDown-leave-to{
  opacity:0
}

#app .counterDown > :nth-child(1) > :not(.M).dropDown-enter{
  transform:translateY(37px)
}
#app .counterDown > :nth-child(1) > :not(.M).dropDown-leave-to{
  transform:translateY(-17px) scale(.5)
}

#app .counterDown > :nth-child(1) > .M.dropDown-enter{
  transform:translateX(50%) translateY(37px)
}
#app .counterDown > :nth-child(1) > .M.dropDown-leave-to{
  transform:translateX(50%) translateY(-17px) scale(.5)
}

 #app .counterDown > :nth-child(1) > .dropDown-enter-active
,#app .counterDown > :nth-child(1) > .dropDown-leave-active{
  transition:.75s
}
</styled>

<style>
#app .counterDown > :nth-child(1){
  display:flex;
  position:relative;
  /*padding-bottom:7px;*/
  pointer-events:none;
  justify-content:center
}

#app .counterDown > :nth-child(1) > *{
  /*border:1px dotted blue;*/
}

#app .counterDown > :nth-child(1) > .D{
  padding:3px 9px;
  font-size:.73em;
  align-self:flex-start;
  border-radius:9px;
  background-color:#d9d9d9
}

 #app .counterDown > :nth-child(1) > .H
,#app .counterDown > :nth-child(1) > .M
,#app .counterDown > :nth-child(1) > .S{
  margin:0 3px;
  font-size:1.5em;
  font-family:'Roboto Mono',monospace
}

#app .counterDown > :nth-child(1) > .vline{
  position:relative;
  font-size:1.25em;
  align-self:center;
  font-weight:700
}

#app .counterDown > hr{
  right:50%;
  border:0;
  bottom:0;
  height:2px;
  position:absolute;
  margin:0;
  transform:translate(50%,100%);
  transition:.5s;
  background-color:#bdbdbd
}
/*
#app .counterDown > :nth-child(1) > .colon{
  position:relative
}
 #app .counterDown > :nth-child(1) > .colon::before
,#app .counterDown > :nth-child(1) > .colon::after{
  right:0;
  width:3px;
  height:3px;
  content:'';
  position:absolute;
  transform:translateX(50%);
  border-radius:50%;
  background-color:rgba(0,0,0,.75)
}
#app .counterDown > :nth-child(1) > .colon::before{
  top:31%
}
#app .counterDown > :nth-child(1) > .colon::after{
  bottom:31%
}*/
#app .counterDown > :nth-child(1) > .slideRight-enter,#app .counterDown > :nth-child(1) > .slideRight-leave-to{
  opacity:0
}
#app .counterDown > :nth-child(1) > .slideRight-enter{
  transform:translateX(-57px)
}
#app .counterDown > :nth-child(1) > .slideRight-leave-to{
  transform:scale(1.5)
}
#app .counterDown > :nth-child(1) > .slideRight-enter-active,#app .counterDown > :nth-child(1) > .slideRight-leave-active{
  transition:.75s
}
</style>

<template>
<div class=counterDown style="
 display:block
;position:relative
;transition:.5s
">
  <div>
    <transition name=slideRight>
    <div class=D v-if="d > 0" :style={backgroundColor:color}>{{ d }}&#160;hari</div>
    </transition>
    <!-- <transition name=dropDown> -->
    <div class=H v-if=hourShown>{{ h }}</div>
    <div class=vline v-if=hourShown>:</div>
    <!-- </transition>
    <transition name=dropDown> -->
    <div class=M>{{ m }}</div>
    <div class=vline>:</div>
    <!-- </transition> -->
    <div class=S>{{ s }}</div>
  </div>
  <!-- <hr :style={width:w} /> -->
</div>
</template>

<script>
import { zeroFill } from '@/assets/js/calcs'
export default {
   name:'counterDown'
  ,data:() => ({
     d:0
    ,h:'--'
    ,m:'--'
    ,s:'--'
    ,ID:null
  })
  ,props:{
     cntrl:{ type:[ Number, String ], default:0 }
    ,limit:{ type:[ Number, String ] }
    ,color:String
  }
  ,methods:{ calc:() => { } }
  ,computed:{
     n() { return parseInt(this.limit) - 1 }
    ,hourShown() { return this.d > 0 || this.h > 0 }
  }
  ,watch:{
     cntrl(v) {
      switch(v) {
        case 1: case "1":
          if(!this.ID) {
            let n = this.n
            this.ID = setTimeout((that => function loop() {
              that.calc(n)
              if(--n < 0) {
                clearTimeout(that.ID)
                that.$emit('clear', that.$el)
                that.ID = null
              } else
                setTimeout(loop, 1000)
            })(this), 1000)
          }
          break;
      }
    }
  }
  ,mounted() {
    const $2 = zeroFill(2);
    (this.calc = n => {
      this.d = Math.floor(n / (3600 * 24))
      this.h = $2(Math.floor((n % (3600 * 24)) / 3600))
      this.m = $2(Math.floor((n % 3600) / 60))
      this.s = $2(n % 60)
    })(this.limit)
  }
}
</script>