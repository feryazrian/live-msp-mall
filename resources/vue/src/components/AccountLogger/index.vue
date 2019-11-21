<style>
#app .desktopAccountLogger > main{
  /*border:5px solid blue;*/
  /*transition:.5s ease-out;*/
  position:absolute;
  transform:translate(-50%,-50%);
  border-radius:9px;
  transform-style:preserve-3d;
  background-color:#fff
}

#app .desktopAccountLogger > main > *{
  /*box-shadow:rgba(255, 255, 255, 0.43) 0px 0px 87px 39px,0 0 33px 12px rgba(255,255,255,.25)*/
  top:50%;
  right:50%;
  overflow:hidden;
  position:absolute;
  border-radius:inherit;
  background-color:inherit
}

#app .desktopAccountLogger header.themeBasis{
  /*border-top-left-radius:inherit*/
}
#app .desktopAccountLogger header.themeBasis > :nth-child(1){
  margin:0;
  display:inline-block;
  padding:25px 0 8px;
  position:relative;
  font-size:1.75em;
  font-weight:400;
  text-shadow:0 0 3px rgba(67,67,67,.23),1px 1px 2px rgba(67,67,67,.17);
  /*border-top-left-radius:inherit*/
}
/*#app .desktopAccountLoggerIn > header > :nth-child(1)::after{
  width:100%;
  bottom:0;
  height:5px;
  content:'';
  position:absolute;
  border-radius:3px;
  background-color:#ffc107
}
*/
 #app .desktopAccountLogger header.themeBasis::before
,#app .desktopAccountLogger header.themeBasis > :nth-child(1)::before{
  top:0;
  content:'';
  position:absolute;
  /*border-top-left-radius:inherit*/
}

#app .desktopAccountLogger header.themeBasis::before{
  /*width:150px;
  height:125px;
  content:'';
  position:absolute;
  border-radius:50%;
  background-color:#ffc107*/
  /*
  top:0;
  left:0;
  width:153px;
  height:81px;
  content:'';
  position:absolute;
  background-color:#ffc107;
  border-top-right-radius:18px 67%;
  border-bottom-left-radius:66% 36px;
  border-bottom-right-radius:187%;
  */
  left:0;
  width:180px;
  height:101px;
  background-color:rgba(255, 193, 7,.4);
  border-bottom-right-radius:77% 103px
}
#app .desktopAccountLogger header.themeBasis > :nth-child(1)::before{
  left:-25px;
  width:177px;
  height:78px;
  background-color:#ffc107;
  border-bottom-right-radius:77% 93px
}

#app .desktopAccountLogger header.themeBasis > :nth-child(1) > :only-child{
  position:relative
}

#app .desktopAccountLogger header.themeBasis > button{
  /*border:1px solid red;*/
  top:55%;
  right:30px;
  padding:0;
  position:relative;
  position:absolute;
  transform:translateY(-100%)
}
 #app .desktopAccountLogger header.themeBasis > button::before
,#app .desktopAccountLogger header.themeBasis > button::after{
  width:17px;
  height:4px;
  border-radius:3px;
  background-color:#d9d9d9
}

#app .desktopAccountLogger header.themeBasis > button::before{
  transform:translate(50%,-50%) rotateZ(45deg)
}
#app .desktopAccountLogger header.themeBasis > button::after{
  transform:translate(50%,-50%) rotateZ(-45deg)
}

#app .desktopAccountLogger input.themeBasis,#app .desktopAccountLogger button.themeBasis{
  margin:19px 0;
  padding:13px 15px;
  border-radius:27px
}

#app .desktopAccountLogger input.themeBasis{
  border-color:#e6e6e6;
  transition:inherit
}

#app .desktopAccountLogger input.themeBasis:focus{
  border-color:#ffc107
}

#app .desktopAccountLogger button.themeBasis{
  border:0;
  font-size:1.25em
}

#app .desktopAccountLogger footer.themeBasis{
  text-align:center;
  padding-top:25px;
  padding-bottom:25px;
  background-color:#f5f5f5
  /*border-bottom-left-radius:inherit;
  border-bottom-right-radius:inherit*/
}

#app .desktopAccountLogger .snack{
  top:21px;
  width:53%;
  right:50%;
  color:#fff;
  position:absolute;
  transform:translateX(50%);
  pointer-events:none
}
#app .desktopAccountLogger .snack > :only-child{
  position:relative;
  font-size:.87em;
  text-align:center
}
#app .desktopAccountLogger .snack::before{
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
  background-color:#d32f2f
}
#app .desktopAccountLogger .snack.info::before{
  background-color:#558B2F
}

#app .desktopAccountLogger .snackShown-enter,#app .desktopAccountLogger .snackShown-leave-to{
  opacity:0;
  transform:translate(50%,-47px)
}
#app .desktopAccountLogger .snackShown-enter-active,#app .desktopAccountLogger .snackShown-leave-active{
  transition:.25s ease-out
}

#app .desktopAccountLogger .slider-enter{
  transform:translateX(-100%)
}
#app .desktopAccountLogger .slider-leave-to{
  transform:translateX(100%)
}
#app .desktopAccountLogger .slider-enter-active,#app .desktopAccountLoggerUp > .slider-leave-active{
  transition:.75s
}
</style>

<template>
<div class=desktopAccountLogger style="
 top:50%
/*;border:3px solid red*/
;right:50%
;position:fixed
;transform:translate(50%,-50%)
;perspective:1000px
;z-index:3
">
  <main>
    <logger-in :source=source />
    <logger-up :source=source />
  </main>
</div>
</template>

<script>
import { APP as setApp } from '@/store/actions'
import LoggerIn from './LoggerIn'
import LoggerUp from './LoggerUp'
import { TimelineLite, Elastic } from 'gsap'

export default {
   name:'desktopAccountLogger'
  ,data:() => ({ twist:null
  })
  ,props:{
     action:Number
    ,source:Object
  }
  ,methods:{
     shiftFocus(e) {
      if(e.keyCode === 9) {
        e.preventDefault()
      }
    }
  }
  ,watch:{
     action(v) {
      //this.options.rotationY = v? -180 : 0
      //this.spinner.restart()
      this.twist[v].restart()
    }
  }
  ,mounted() {
    let e = this.$el.querySelector('main')
    this.twist = [
       new TimelineLite({ paused:true }).to(e, .5, { scale:1.15 }).to(e, 1.5, { rotationY:-180, ease:Elastic.easeOut.config(1,.4) }).to(e, .25, { scale:1 })
      ,new TimelineLite({ paused:true }).to(e, .5, { scale:1.15 }).to(e, 1.5, { rotationY:0, ease:Elastic.easeOut.config(1,.4) }).to(e, .25, { scale:1 })
    ]
    document.body.classList.add('frozen')
    document.addEventListener('keydown', this.shiftFocus)
    this.$store.dispatch(setApp.FREEZE, true)
    if(this.action === 0)
      e.style.transform = 'translate(50%,-50%) rotateY(-180deg)'
  }
  ,beforeDestroy() {
    document.removeEventListener('keydown', this.shiftFocus)
  }
  ,components:{ LoggerIn, LoggerUp }
}
</script>