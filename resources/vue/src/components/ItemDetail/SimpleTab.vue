<style>
.simpleTab{
  transition:.25s ease-in-out;
  border-radius:5px;
  background-color:#fff
}

.simpleTab > header{
  /*border:1px dotted red;*/
  padding:3px 25px 0;
  position:relative;
  transition:inherit
}
.simpleTab > header.hidden{
  height:0;
  overflow:hidden
}

.simpleTab > header::before{
  right:0;
  width:100%;
  bottom:1px;
  height:1px;
  content:'';
  position:absolute;
  background-color:gainsboro
}
.simpleTab > header.hidden::before{ content:unset }

.simpleTab > header > :nth-child(1){
  /*border:2px solid blue;*/
  overflow:hidden;
  position:relative;
  transition:inherit;
  padding-bottom:1px
}

.simpleTab > header > :nth-child(1) > .slider{
  /*border:3px solid gray;*/
  /*position:absolute;*/
  display:inline-block
}

.simpleTab > header > :nth-child(1) > .slider > :not(hr){
  /*border:3px solid blue;*/
  padding:13px;
  display:inline-block;
  vertical-align:middle
}

.simpleTab > header > :nth-child(1) > .slider > hr{
  margin:0;
  border:0;
  height:3px;
  bottom:-1px;
  padding:0;
  position:absolute;
  transition:.5s cubic-bezier(.23,1,.32,1);
  border-radius:3px;
  background-color:#ffb300
}

.simpleTab > header > :nth-child(1) > .slider > * > *{
  cursor:pointer
}

.simpleTab > header > :nth-child(1) > button{
  top:50%;
  border:0;
  opacity:0;
  padding:15px;
  position:absolute;
  transition:inherit;
  border-radius:50%;
  background-color:#fff;
  transition-duration:.15s;
  transition-timing-function:linear
}
.simpleTab > header > .scroll:nth-child(1) > button.active:hover{
  opacity:.95
}
.simpleTab > header > .scroll:nth-child(1) > button.active{
  opacity:.67
}

.simpleTab > header > :nth-child(1) > button:nth-of-type(1){
  left:0;
  transform:translate(-100%,-50%) rotateZ(180deg)
}

.simpleTab > header > .scroll:nth-child(1) > button.active:nth-of-type(1){
  transform:translate(25%,-50%) rotateZ(180deg)
}

.simpleTab > header > :nth-child(1) > button:nth-of-type(1)::before{
  transform:rotateZ(180deg)
}

.simpleTab > header > :nth-child(1) > button:nth-of-type(2){
  right:0;
  transform:translate(100%,-50%)
}

.simpleTab > header > .scroll:nth-child(1) > button.active:nth-of-type(2){
  transform:translate(-25%,-50%)
}

.simpleTab > header > :nth-child(1) > button > :only-child{
  margin-right:-1px
}

.simpleTab > main{
  /*border:1px solid cyan;*/
  padding:15px 25px;
}
</style>

<template>
<div class="simpleTab pop">




  <header class=hidden>
    <div @mouseenter=onEnter @mouseleave=onLeave>
      <div class=slider>
        <div v-for="(e, i) of titles" :key=i>
          <div @click=setPointer($event.currentTarget,i)>
            <slot :name="'header' + i">{{ e }}</slot>
          </div>
        </div>
        <hr />
      </div>
      <button class=popLowest @click=prev>
        <font-awesome class=cntr icon=chevron-right />
      </button>
      <button class=popLowest @click=next>
        <font-awesome class=cntr icon=chevron-right />
      </button>
    </div>
  </header>




  <main>
    <slot v-for="(e, i) of titles" :name=i v-if="cursor===i" />
  </main>
</div>
</template>

<script>
import { clientWidth, calcDescentWidth } from '@/assets/js/calcs'

export default {
   name:'simpleTab'
  ,data:() => ({
     N:0
    ,slider:null
    ,cursor:0
  })
  ,props:{
    titles:Array
  }
  ,methods:{
     update() {
      let e = this.$el.querySelector('header > :nth-child(1) > .slider')
      e.style.width = calcDescentWidth(e) + 'px'
      this.N = e.parentElement.getBoundingClientRect().width / 2.
      this.slider.refresh()
    }
    ,onEnter({ currentTarget:e }) {
      if(clientWidth(e) - calcDescentWidth(e.firstElementChild) < 0)
        e.classList.add('scroll')
    }
    ,onLeave({ currentTarget:e }) {
      e.classList.remove('scroll')
    }
    ,next({ currentTarget:e }) {
      let x = clientWidth(e = e.parentElement) - calcDescentWidth(e.firstElementChild)
      let N = this.N, n = this.slider.x - N
      this.slider.scrollBy(x < n? -N : -N - (n - x), 0, 500)
    }
    ,prev() {
      let N = this.N, n = this.slider.x + N
      this.slider.scrollBy(n < 0? N : N - n, 0, 500)
    }
    ,setPointer(e, i) {
      let line = this.$el.querySelector('.slider > hr')
      if(e == null || i < 0) {
        line.style.left = 0
        line.style.width = 0
        return;
      }
      e = e.parentElement
      line.style.width = e.getBoundingClientRect().width + 'px'
      e = 0
      for(let j = -1, children = line.parentElement.children; ++j < i; )
        e += children[j].getBoundingClientRect().width
      line.style.left = e + 'px'
      this.cursor = i
    }
  }
  ,watch: {
     async titles(v) {
      if(v.length > 0) {
        await Promise.resolve(this.$el.querySelector('header > :nth-child(1) > .slider'))
        this.update()
      } else {
        this.setPointer(null)
        this.$el.querySelector('header').classList.add('hidden')
      }
    }
    ,async '$store.state.agent.ready'() {
      let e = await Promise.resolve(this.$el.querySelector('header > :nth-child(1)'));
      (this.slider = new IScroll(e, { click:true, scrollX:true, scrollY:false })).on('scrollEnd', () => {
        let classList = e.querySelector('button:nth-of-type(1)').classList
        if(this.slider.x > -1)
          classList.remove('active')
        else
          classList.add('active')
      })
      e.parentElement.classList.remove('hidden')
      this.update()
    }
  }
  ,mounted() {
    if(this.titles.length < 1) return;
    this.setPointer(this.$el.querySelector('.slider > :nth-child(1) > :only-child'), 0)
  }
  ,beforeDestroy() { this.slider.destroy() }
}
</script>