<style>
#app .progressCircular{
  /*animation:rotate 3s linear infinite*/
}
/*
#app .progressCircular.cntr{
  animation:rotateCenter 3s linear infinite
}
*/

#app .progressCircular > svg{
  transition-property:transform
}
#app .progressCircular > svg > circle{
  fill:transparent;
  stroke:#ffc107;
  transform:rotate(-90deg);
  transition:all .75s;
  stroke-linecap:round;
  transform-origin:50% 50%
}

@keyframes rotate{
  0%  { transform:rotateZ(0) }
  50% { transform:rotateZ(180deg) }
  100%{ transform:rotateZ(360deg) }
}
/*
@keyframes rotateCenter{
  0%  { transform:translate(50%,-50%) rotateZ(0) }
  50% { transform:translate(50%,-50%) rotateZ(180deg) }
  100%{ transform:translate(50%,-50%) rotateZ(360deg) }
}
*/
</style>

<template>
<div class=progressCircular :style="{ width:size + 'px', height:size + 'px' }" style="display:inline-block">
  <svg xmlns=http://www.w3.org/2000/svg width=100% height=100%>
    <circle :stroke-width=line :r=r cx=50% cy=50% />
  </svg>
</div>
</template>


<script>
import { transitionName } from '@/assets/js/calcs'

export default {
   name:'progressCircular'
  ,props:{
     line:{ type:[ Number, String ], default:2 }
    ,size:{ type:[ Number, String ], default:25 }
  }
  ,computed:{
     r() { return (this.size / 2) - this.line }
    ,n() {
      let n = this.r * 2 * Math.PI
      this.$el.querySelector('svg>circle').style.strokeDasharray = n + ' ' + n
      return n
    }
  }
  ,methods:{ set(n, e/* = this.$el.querySelector('svg>circle')*/) { e.style.strokeDashoffset = this.n - n / 100 * this.n } }
  ,async mounted() {
    let v = this.$el.querySelector('svg>circle'), n = this.n, b = false
    v.addEventListener(transitionName(), e => {
      if(e.propertyName === 'stroke-dashoffset') {
        this.set(b? 83 : 1, v)
        if((b = !b)) {
          x.transitionDuration = S? S : '.75s'
          x.transform = 'rotateZ(360deg)'
        }
        else {
          x.transitionDuration = '0s'
          x.transform = 'rotateZ(0)'
        }
      }
    })
    await new Promise(finish => {
      v.style.strokeDasharray = n + ' ' + (v.style.strokeDashoffset = n)
      setTimeout(finish, 25)
    })
    this.set(83, v)
    this.$el.style.animation = 'rotate 3s linear infinite'
    let x = v.parentElement.style, S = getComputedStyle(v).transitionDuration
  }
}
</script>