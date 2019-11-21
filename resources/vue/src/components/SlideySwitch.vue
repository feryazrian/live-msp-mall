<style>
#app .slideySwitch{
  /*border:1px dotted red;*/
  display:inline;
}

#app .slideySwitch > .slidey{
  width:41px;
  height:17px;
  position:relative;
  transition:inherit;
  border-radius:15px;
  background-color:rgba(0,0,0,.38)
}
#app .slideySwitch > .slidey.active{
  /*background-color:#ffe082*/
  background:linear-gradient(to top,#ffc107,#e08c06 93%)
}

#app .slideySwitch > .slidey > :only-child{
  top:50%;
  left:0;
  width:26px;
  height:26px;
  cursor:pointer;
  position:absolute;
  transform:translateY(-50%);
  transition:inherit;
  box-shadow:0 2px 4px -1px rgba(0,0,0,.2),0 4px 5px 0 rgba(0,0,0,.14),0 1px 10px 0 rgba(0,0,0,.12);
  border-radius:50%
}

#app .slideySwitch > .slidey > :only-child::before{
  width:100%;
  height:100%;
  transition:inherit;
  border-radius:inherit;
  pointer-events:none;
  background-color:rgba(67,67,67,.1)
}
#app .slideySwitch > .slidey > :only-child:hover::before{
  transform:translate(50%,-50%) scale(2)
}
#app .slideySwitch > .slidey.active > :only-child:hover::before{
  background-color:rgba(255, 171,0,.3)
}

#app .slideySwitch > .slidey > :only-child::after{
  width:100%;
  height:100%;
  border-radius:inherit;
  background-color:#fff
}
#app .slideySwitch > .slidey.active > :only-child::after{
  background-color:#ffc107
}

#app .slideySwitch > label{
  margin-left:7px
}
</style>

<template>
<div class=slideySwitch
style="display:inline-flex;transition:.15s;align-items:center">
  <div class=slidey :class={active:value}>
    <div class="cntrLowest cntrHigher" @click=change></div>
  </div>
  <label if=label>{{ label }}</label>
</div>
</template>

<script>
import { calcWidth } from '@/assets/js/calcs'

export default {
   name:'slideySwitch'
  ,data:() => ({ value:false })
  ,methods:{
     change({ currentTarget:e }) {
      e.style.transform = !(this.value = !this.value)? '' : `translate(${ e.parentElement.getBoundingClientRect().width - calcWidth(e) }px,-50%)`
      this.$emit('change', (this.value))
    }
  }
  ,props:{ label:String }
  //,mounted() {
  // // let e = this.$el.querySelector('.slidey > :only-child'), style, N = 7;
  // //(style = e.style).width = e.style.height = ((e = e.parentElement.getBoundingClientRect().height) + N) + 'px'
  // // style.top = (e - (e + N)) / 2 + 'px'
  //}
}
</script>