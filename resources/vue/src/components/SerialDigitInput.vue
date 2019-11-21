<style>
#app .serialDigitInput > main {
  padding:0 5px;
  display:flex
}

#app .serialDigitInput > main > *{
  margin:0 7px;
  padding:5px 0;
  position:relative;
  flex-grow:1;
  transition:.5s ease-out;
  border-bottom:1px solid #d0d0d0
}

#app .serialDigitInput > main > .focus{
  border-bottom-color:transparent
}

#app .serialDigitInput > main > ::after{
  right:50%;
  width:100%;
  height:3px;
  bottom:-2px;
  opacity:0;
  content:'';
  position:absolute;
  transform:translateX(50%) scaleX(0);
  transition:.37s ease-in;
  border-radius:3px;
  background-color:#ffc107
}
#app .serialDigitInput > main > .focus::after{
  opacity:1;
  transform:translateX(50%) scaleX(1)
}

#app .serialDigitInput > main > :first-child{
  margin-left:0
}
#app .serialDigitInput > main > :last-child{
  margin-right:0
}

#app .serialDigitInput > main > * > input{
  /*color:#f9a825;*/
  width:100%;
  border:0;
  font-weight:700;
  font-family:'Roboto Mono',sans-serif;
  font-size:2.5em;
  text-align:center
}
#app .serialDigitInput > main > * > input::selection{
  border-radius:3px;
  background-color:#fff9c4
}

#app .serialDigitInput > button.clear{
  top:0;
  right:0;
  border:0;
  padding:10px;
  position:absolute;
  transform:translate(50%,-50%);
  background-color:transparent
}
 #app .serialDigitInput > button.clear::before
,#app .serialDigitInput > button.clear::after{
  width:17px;
  height:4px;
  border-radius:3px
}

#app .serialDigitInput > button.clear::before{
  transform:translate(50%,-50%) rotateZ(45deg)
}
#app .serialDigitInput > button.clear::after{
  transform:translate(50%,-50%) rotateZ(-45deg)
}

#app .serialDigitInput > button.clear.shown-enter{
  opacity:0;
  transform:translate(50%,-50%) translateX(37px)
}
#app .serialDigitInput > button.clear.shown-leave-to{
  opacity:0;
  transform:translate(50%,-50%) scale(2)
}
#app .serialDigitInput > button.clear.shown-enter-active,#app .serialDigitInput > button.clear.shown-leave-active{
  transition:.25s
}
</style>

<template>
<div class=serialDigitInput style="position:relative">
  <main class>
    <div v-for="i of n" :key=i>
      <input type=text class=themeBasis-color @keydown=onPress($event,i) @focus=onFocus maxlength=1 />
    </div>
  </main>
  <transition name=shown>
  <button v-if="value" class="clear cntrLowest cntrHigher themeBasis-bgColorHigher themeBasis-bgColorLowest" @click=clear></button>
  </transition>
</div>
</template>

<script>
import { digitKeys } from '@/assets/js/calcs'

export default {
   name:'serialDigitInput'
  ,data:() => ({ value:false })
  ,props:{ length:{ type:[ Number, String ], default:5 } }
  ,methods:{
     onPress(e, i) {
      let shift = (v, b) => {
          if((b = !(v = v.parentElement[(b? 'next' : 'previous') + 'ElementSibling'])))
            return b
          if((v = v.querySelector('input')))
            v.focus()
        },force = v => {
          for(;;) {
            let n = v.parentElement.nextElementSibling
            if(!n)
              break
            if((n = n.querySelector('input')))
              v.parentElement.classList[!(v.value = n.value)? 'remove' : 'add']('focus')
            v = n
          }
          v.value = null
          v.parentElement.classList.remove('focus')
        },check = e => {
          let x = ''
          for(let v of e.children)
            if((v = v.querySelector('input'))) {
              if((v = v.value))
                x += v
              else {
                x = null
                break
              }
            }
          if(x && this.value !== x)
            this.$emit('exact', this.value = x)
        },n
      if(digitKeys(n = e.keyCode)) {
        (n = e.currentTarget).value = e.key
        if(shift(n, 1)) {
          n.setSelectionRange(0, n.value.length)
        } n = n.parentElement
        n.classList.add('focus')
        check(n.parentElement)
      } else
      if(n === 8) {
        if(i > 1) {
          force(n = e.currentTarget)
          if(shift(n, 0))
            n.setSelectionRange(0, n.value.length)
          if(this.value) {
            this.value = false
            this.$emit('reset')
          }
        }
      } else
      if(n === 46) {
        force(n = e.currentTarget)
        n.setSelectionRange(0, n.value.length)
        if(this.value) {
          this.value = false
          this.$emit('reset')
        }
      } else
      if(n === 39) {
        shift(e.currentTarget, 1)
      } else
      if(n === 37) {
        shift(e.currentTarget, 0)
      }
      e.preventDefault()
    }
    ,onFocus({ currentTarget:e }) {
      e.setSelectionRange(0, e.value.length)
    }
    ,clear() {
      for(let e of this.$el.querySelector('main').children) {
        e.classList.remove('focus')
        e.querySelector('input').value = null
      }
      this.value = false
      this.$emit('reset')
    }
  }
  ,computed:{ n() { return parseInt(this.length) } }
}
</script>