<style>
#app .xpander > *{
  transition:.5s ease-in-out
}

#app .xpander header{
  cursor:pointer;
  display:flex;
  transition:inherit;
  align-items:center
}

#app .xpander header > :nth-child(1){
  width:25px;
  height:25px;
  position:relative;
  transform:rotateZ(90deg);
  flex-shrink:0;
  margin-right:7px;
  transition:inherit;
  transition-timing-function:cubic-bezier(.93,-1.01,.16,1.97)
}
#app .xpander > .hidden > header > :nth-child(1){ transform:none }

#app .xpander header > :nth-child(2){
  height:50px;
  position:relative;
  overflow:hidden;
  flex-grow:1;
  transition:inherit;
  white-space:nowrap;
  text-overflow:ellipsis
}

#app .xpander header > :nth-child(2)::before{
  height:100%;
  content:'';
  display:inline-block;
  vertical-align:middle
}

#app .xpander header > :nth-child(2)::after{
  width:100%;
  right:50%;
  bottom:0;
  height:2px;
  content:'';
  position:absolute;
  transform:translateX(50%);
  transition:inherit;
  border-radius:1px;
  background-color:rgba(119,119,119,.67);
  transition-timing-function:cubic-bezier(.23,1,.32,1)
}
#app .xpander > .hidden > header > :nth-child(2)::after{
  width:0;
  opacity:0;
  transition-timing-function:cubic-bezier(.55,.06,.68,.19)
}

#app .xpander header > :nth-child(2) > :only-child{
  display:inline;
  font-weight:600;
  vertical-align:middle
}

#app .xpander main{
  overflow:hidden;
  position:relative;
  transition:inherit;
  transition-timing-function:cubic-bezier(.18,.89,.32,1.28)
  /*padding-left:47px;
  padding-right:15px;*/
  /*display:flex;
  flex-direction:column-reverse*/
}
#app .xpander > .hidden > main{
  transition-timing-function:cubic-bezier(.6,-0.28,.74,.05)
}

#app .xpander main > :only-child{
  position:relative;
  transition-duration:inherit;
  transition-property:transform,opacity;
  transition-timing-function:cubic-bezier(.18,.89,.32,1.28),ease-in-out
}
#app .xpander > .hidden > main > :only-child{
  opacity:0;
  transform:translateY(-100%);
  transition-timing-function:cubic-bezier(.6,-0.28,.74,.05),ease-in-out
}
</style>

<template>
<div class=xpander>
  <section v-for="(e, i) of titles" :key=i>
    <header @click=xpand @mousedown=first>
      <div>
        <font-awesome icon=chevron-right class=cntr />
      </div>
      <div>
        <h4>{{ e }}</h4>
      </div>
    </header>
    <main>
      <div><slot :name=i></slot></div>
    </main>
  </section>
</div>
</template>

<script>
import { calcHeight, clientHeight } from '@/assets/js/calcs'

export default {
   name:'xpander'
  ,props:{ titles:{ type:Array, default:() => [ ] } }
  ,methods:{
     xpand({ currentTarget:e }) {
      let child = e.parentElement.querySelector('main > :only-child')
      if(clientHeight(e = child.parentElement) > 0) {
        e.style.height = 0
        e.parentElement.classList.add('hidden')
      } else {
        e.style.height = calcHeight(child) + parseFloat(getComputedStyle(child.firstElementChild).marginTop) + parseFloat(getComputedStyle(child.lastElementChild).marginBottom) + 'px'
        e.parentElement.classList.remove('hidden')
      }
    }
    ,first({ currentTarget:e }) {
      let child = e.parentElement.querySelector('main > :only-child')
      let style = child.parentElement.style
      if(style.height === '') {
        style.height = calcHeight(child) + parseFloat(getComputedStyle(child.firstElementChild).marginTop) + parseFloat(getComputedStyle(child.lastElementChild).marginBottom) + 'px'
      }
    }
  }
  ,watch:{
     '$store.state.agent.ready'() {
      Array.from(this.$el.children).map(e => {
        let style = e.querySelector('main').style
        e = e.querySelector('header')
        let n = e.children[1].getBoundingClientRect().width
        style.width = n + 'px'
        style.right = -(e.getBoundingClientRect().width - n) + 'px'
      })
    }
  }
}
</script>