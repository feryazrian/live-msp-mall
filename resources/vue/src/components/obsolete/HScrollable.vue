<style>
.hScrollable{
  border:3px solid red;
  margin:25px 0;
}

.hScrollable > *{
  border:3px solid green;
  cursor:pointer;
  position:relative
}

.hScrollable > * > *{
  border:1px dotted blue;
  display:inline-block;
  vertical-align:middle
}
</style>

<template>
<div class=hScrollable
style="overflow:hidden">
  <div @mousedown=startMove>
    <slot></slot>
  </div>
</div>
</template>

<script>
import { calcWidth, calcDescentWidth } from '@/assets/js/calcs'

export default {
   name:'hScrollable'
  ,data:() => ({
  })
  ,methods:{
    startMove(e) {
      e.preventDefault()
      //if(e.buttons === 1) {
      	let target = e.currentTarget
        let parent = target.parentElement
        target.style.transition = ''
        //let x = e.clientX - parent.getBoundingClientRect().x
        //let first = parseFloat(e.style.left) || parseFloat(getComputedStyle(e).left) || 0
        let x = e.clientX - target.getBoundingClientRect().left
        //target.style.left = e.clientX - parent.getBoundingClientRect().x - x + 'px'
        function mouseMove(e) {
          let n = e.clientX - parent.getBoundingClientRect().left - x
          //if(n < 0) n = 0
          //let N = parent.getBoundingClientRect().width - calcWidth(target)
          //if(n > N) n = N
          target.style.left = n + 'px'
        }
        document.addEventListener('mousemove', mouseMove)
        function releaseUp(e) {
          let x, n
          if((x = parseFloat(target.style.left)) > 0) {
            target.style.left = 0
          } else
          if((n = parent.getBoundingClientRect().width - calcWidth(target)) > x)
            target.style.left = n + 'px'
          target.style.transition = '5s cubic-bezier(.18,.89,.32,1.28)'
          document.removeEventListener('mouseup', releaseUp)
          document.removeEventListener('mousemove', mouseMove)
          //e.removeEventListener('mouseleave', releaseUp)
        }
        document.addEventListener('mouseup', releaseUp)
        //target.addEventListener('mouseleave', releaseUp)
      //}
    }
  }
  ,mounted() {
    let e = this.$el.querySelector(':only-child')
    e.style.width = calcDescentWidth(e) + 'px'
    //e.parentElement.style.height = calcHeight(e) + 'px'
  }
}
</script>