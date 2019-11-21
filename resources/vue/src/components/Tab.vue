<style>
.tab{
  border:1px dotted red;
  transition:.25s ease-in-out;
}

.tab > .tabHeader{
  border:1px solid blue;
  overflow:hidden;
  position:relative;
  transition:inherit
}

.tab > .tabHeader > button{
  top:50%;
  border:0;
  opacity:.5;
  padding:15px;
  position:absolute;
  transition:inherit;
  border-radius:50%;
  background-color:#fff;
  transition-timing-function:.15s
}
.tab > .tabHeader > button:hover{
  opacity:.93
}

.tab > .tabHeader > button:nth-child(2){
  left:0;
  transform:translate(-100%,-50%) rotateZ(180deg)
}
.tab > .tabHeader.scroll > button:nth-child(2){
  transform:translate(25%,-50%) rotateZ(180deg)
}

.tab > .tabHeader > button:nth-child(3){
  right:0;
  transform:translate(100%,-50%)
}
.tab > .tabHeader.scroll > button:nth-child(3){
  transform:translate(-25%,-50%)
}

.tab > .tabHeader > .popLowest:nth-child(2)::before{
  transform:rotateZ(180deg)
}

.tab > .tabHeader > :nth-child(1){
  border:2px solid red;
  display:inline-block;
  user-select:none
}

.tab > .tabHeader > :nth-child(1) > *{
  border:1px dotted red;
  display:inline-block;
  padding:10px 15px;
}

.tab > main{
  border:1px solid cyan;
}
</style>

<template>
<div class=tab>
  <div class=tabHeader :class={scroll:hover} @mouseenter=onEnter @mouseleave=onLeave>
    <div>
      <div v-for="(e, i) of titles" :key=i>{{ e }}</div>
    </div>
    <button class=popLowest @click=next>
      <font-awesome class=cntr icon=chevron-right />
    </button>
    <button class=popLowest @click=prev>
      <font-awesome class=cntr icon=chevron-right />
    </button>
  </div>
  <main>
    Content
  </main>
</div>
</template>

<script>
import { clientWidth, calcDescentWidth } from '@/assets/js/calcs'

export default {
   name:'tab'
  ,data:() => ({
  	 N:300
  	,hover:null
    ,header:null
    ,titles:[
       'Detail Produk'
      ,'Ulasan'
      ,'Tanya - Jawab'
      ,'Title #1'
      ,'Title #2'
      ,'Title #3'
      ,'Title #4'
      ,'Title #5'
      ,'Title #6'
      //,'Title #7'
      //,'Title #8'
      //,'Title #9'
      //,'Title #10'
      //,'Title #11'
      //,'Title #12'
      //,'Title #13'
    ]
  })
  ,methods:{
     prev() {
      let x = clientWidth((x = this.getSlider()).parentElement) - calcDescentWidth(x)
      let n = this.header.x - this.N
      this.header.scrollBy(x < n? -this.N : -this.N - (n - x), 0, 500)
    }
    ,next() {
      let n = this.header.x + this.N
      this.header.scrollBy(n < 0? this.N : this.N - n, 0, 500)
    }
    ,onEnter() {
      let x = this.getSlider()
      if(clientWidth(x.parentElement) - calcDescentWidth(x) < 0) this.hover = true
    }
    ,onLeave() {
      this.hover = null
    }
    ,getSlider() { return this.$el.querySelector('.tabHeader > :nth-child(1)') }
  }
  ,watch:{
     '$store.state.agent.ready'() {
      (this.header = new IScroll('.tab > .tabHeader', { scrollX:true, scrollY:false })).on('scrollEnd', ((e) => function() {
        //
      })(this.header))
    }
  }
  ,mounted() {
    let e = this.getSlider()
    e.style.width = calcDescentWidth(e) + 'px'
  }
}
</script>