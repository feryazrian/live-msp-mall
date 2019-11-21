<style>
#app a.itemCard{
  color:unset;
  text-decoration:none
}
#app .itemCard{
  /*outline:1px solid red;*/
  width:100%
}
#app .itemCard:hover{
  /*transform:scale(1.05)*/
}

#app .itemCard > figure{
  height:187px;
  border-radius:inherit
}
#app .itemCard:hover > figure{
  border-bottom-left-radius:unset;
  border-bottom-right-radius:unset
}
#app .itemCard.phone.xs > figure{
  height:185px
}
#app .itemCard.phone.sm > figure{
  height:205px
}

#app .itemCard > header,#app .itemCard > main{
  margin:7px 15px;
}

#app .itemCard > header > :nth-child(1){
  color:rgba(53,53,53,1);
  right:0;
  margin:0;
  /*height:2.5em;*/
  overflow:hidden;
  position:relative;
  font-size:.89em;
  /*text-shadow:1px 1px 1px rgba(0,0,0,.21);*/
  font-weight:500;
  font-family:'Roboto Condensed',sans-serif
}
#app .itemCard > header > :nth-child(1).hidden::after{
  right:0;
  bottom:0;
  content:'···';
  padding:0 5px;
  position:absolute;
  font-size:1.25em;
  background:linear-gradient(to right,rgba(247, 247, 247, 0.83),rgb(234, 234, 234));
  line-height:.87em;
  border-radius:1px;
  letter-spacing:1px
}

#app .itemCard > main{
  color:#212121;
  position:relative;
  margin-bottom:7px
}

#app .itemCard > main > .seller{
  font-size:.85em;
  font-family:'Roboto Condensed',sans-serif
}

#app .itemCard > main > .charge{
  /*outline:1px dotted red;*/
  display:flex;
  margin-top:5px;
  align-items:flex-start;
  margin-bottom:3px
}
#app .itemCard.phone > main > .charge{
  flex-direction:column
}
#app .itemCard > main > .charge > :nth-child(1) > sup{
  font-size:.63em
}
#app .itemCard > main > .charge > :nth-child(2) > sup{
  font-size:.67em
}
#app .itemCard > main > .charge > :nth-child(1):not(.header){
  position:relative;
  font-size:.87em;
  font-family:'Open Sans Condensed',sans-serif;
  padding-right:1px
}
#app .itemCard > main > .charge > :nth-child(1):not(.header)::before{
  width:100%;
  /*margin-top:1px;*/
  height:2px;
  border-radius:1px;
  background-color:rgba(147,147,147,.67)
}
 #app .itemCard > main > .charge > :nth-child(1).header
,#app .itemCard > main > .charge > :nth-child(2){
  flex-grow:1;
  font-size:1.1em;
  font-weight:500
}
 #app .itemCard > main > .charge > :nth-child(1).header
,#app .itemCard > main > .charge > .shadow + .header{
  color:#495f69
}
#app .itemCard > main > .charge > :nth-child(2){
  color:#ba1239
}
#app .itemCard.phone > main > .charge > :nth-child(1){
  align-self:flex-end
}
#app .itemCard.phone > main > .charge > :nth-child(2){
  font-size:1em;
  align-self:center
}
#app .itemCard:not(.phone) > main > .charge > :nth-child(2){
  margin-left:7px
}

#app .itemCard > main > .review{
  width:67%;
  margin:1px auto;
  display:flex;
  align-items:center;
  justify-content:center
}
#app .itemCard > main > .review > :nth-child(1){
  flex-basis:75%
}
#app .itemCard > main > .review > :nth-child(2){
  font-size:.75em;
  flex-basis:25 %;
  margin-left:5px;
  font-family:'Roboto Condensed',sans-serif
}

#app .itemCard > aside{
  top:0;
  width:100%;
  /*height:100%;*/
  position:absolute;
  transition:.25s;
  border-radius:inherit
}
#app .itemCard:hover > aside{
  pointer-events:none;
  /*box-shadow:0 4px 5px -2px rgba(0,0,0,.2),0 7px 10px 1px rgba(0,0,0,.14),0 2px 16px 1px rgba(0,0,0,.12)*/
  box-shadow:0 3px 3px -2px rgba(0,0,0,.2),0 3px 4px 0 rgba(0,0,0,.14),0 1px 8px 0 rgba(0,0,0,.12)
}
/*
#app .itemCard.low:hover > aside{
  box-shadow:0 3px 3px -2px rgba(0,0,0,.2),0 3px 4px 0 rgba(0,0,0,.14),0 1px 8px 0 rgba(0,0,0,.12)
}
*/

#app .itemCard > aside > :nth-child(2){
  display:flex;
  padding:3px 7px 9px;
  align-items:center;
  pointer-events:all;
  justify-content:center;
  background-color:#fff;
  border-bottom-left-radius:inherit;
  border-bottom-right-radius:inherit
}
#app .itemCard > aside > :nth-child(2) > button{
  width:57%;
  border:0;
  padding:5px 13px;
  overflow:hidden;
  position:relative;
  box-shadow:0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12);
  border-radius:5px;
  background-color:#fafafa
}
#app .itemCard > aside > :nth-child(2) > button::before{
  top:0;
  right:0;
  width:100%;
  height:0;
  opacity:0;
  content:'';
  position:absolute;
  transition:.25s;
  background-color:#ffc107
}
#app .itemCard > aside > :nth-child(2) > button:hover::before{
  opacity:1;
  height:100%
}
#app .itemCard > aside > :nth-child(2) > button > :only-of-type{ position:relative }
/*a[title]:hover:after {
  content: attr(title);
  position: absolute;
  top: -100%;
  left: 0;
}*/
</style>

<template>
<div class=itemCard :to="{ name:'product', params:{ path:value.slug, args:value } }" :class="[ { phone }, phone? breakpointName : null ]" @mouseenter=onEnter @mouseleave=onLeave @click=onClick style="
 cursor:pointer
;display:inline-block
;position:relative
;border-radius:5px
;background-color:inherit
">
  <transition name=pullup @before-enter=onBeginEntering @after-leave=onAfterEscaping>
  <aside v-show=asideShown>
    <div></div>
    <div>
      <button><span>Beli</span></button>
      <!-- <font-awesome :icon="[ 'far', 'heart' ]" />
      <font-awesome :icon="[ 'fas', 'heart' ]" /> -->
    </div>
  </aside>
  </transition>
  <photo :image=image :title=title />
  <header class=layout>
    <h3>{{ title }}</h3>
  </header>
  <main>
    <div class="seller ellips">{{ sellerName }}</div>
    <div class="charge">
      <template v-if=discount.full>
      <template v-if=discount.trim>
      <div class=cntrLowest :title="'Rp ' + discount.full" v-html="`<sup>Rp</sup>&#160;${ discount.trim }&#160;juta`"></div>
      </template>
      <template v-else>
      <div class=cntrLowest :title="'Rp ' + discount.full">
        <sup>Rp</sup>&#160;{{ discount.full }}
      </div>
      </template>
      </template>
      <template v-else-if=phone>
      <div class=shadow style=visibility:hidden>0</div>
      </template>
      <template v-if=price.trim>
      <div class=header :title="'Rp ' + price.full" v-html="`<sup>Rp</sup>&#160;${ price.trim }&#160;juta`"></div>
      </template>
      <template v-else>
      <div class=header>
        <sup>Rp</sup>&#160;{{ price.full }}
      </div>
      </template>
    </div>
    <span class="review">
      <rating :value=rating />&#160;
      <div>{{ review }}</div>
    </span>
  </main>
</div>
</template>

<script>
import { PATH } from '@/assets/js/fetchers'
import Rating from '@/components/Rating'
import Photo from '@/components/Photo'

export default {
   name:'itemCard'
  ,data:() => ({ asideShown:null })
  ,props:{
     value:Object
    ,react:Number
    ,phone:Boolean
    ,motion:Boolean
    ,target:[ String, Array ]//Delete soon
    ,breakpointType:Number
    ,breakpointName:String
    //,isLower:{ type:[ Boolean, String ], default:false }
  }
  ,computed:{
     title() {
      let v = this.value
      return !v || !(v = v.name)? null : v
    }
    ,price() {
      return this.change('price')
    }
    ,discount() {
      return this.change('discount')
    }
    ,image() {
      let v = this.value
      return !v || !(v = v.photo)? null : PATH.product(v)
    }
    ,rating() {
      let v = this.value
      return v && (v = v.rating)? v : null
    }
    ,review() {
      let v = this.value
      return !v || !(v = v.review)? null : v.toLocaleString('id-ID')
    }
    ,sellerName() {
      let v = this.value
      return v && (v = v.seller) && (v = v.name)? v : null
    }
    ,resolvedTarget() {
      let x
      if((x = this.target) instanceof Array) {
        let n
        if((n = x.length) > 1) {
          let i = -1, v = ''
          for(--n; ++i < n; v += x[i]);
          return v + this.value[ x[i] ]
        } else
          return this.value[x[0]]
      }
      return this.value[x]
    }
  }
  ,methods:{
     async calcTitle(v = this.$el.querySelector('header>:nth-child(1)')) {
      v.style.height = ''
      v.style.whiteSpace = 'nowrap'
      let x = Math.ceil(2 * v.getBoundingClientRect().height)
      await new Promise(finish => {
        finish(v.style.whiteSpace = '')
      })
      if(x < v.getBoundingClientRect().height) {
        v.classList.add('hidden')
        v.setAttribute('title', this.title)
      } else {
        v.classList.remove('hidden')
        v.removeAttribute('title')
      }
      v.style.height = x + 'px'
    }
    ,change(k) {
      const N = 1000000
      let i
      if((i = this.value) && (i = i[k])) {
        if(i < 10 * N) {
          return { full: i.toLocaleString('id-ID') }
        } else
          return { full: i.toLocaleString('id-ID'), trim:(parseFloat((i / N).toFixed(1))).toLocaleString('id-ID') }
      }
      return { full:0 }
    }
    ,onClick(e) {
      if(this.motion)
        e.preventDefault()
    }
    ,onEnter({ currentTarget:e }) {
      this.asideShown = true
      let v = e.getBoundingClientRect().height + 'px'
      e.querySelector('aside').firstElementChild.style.height = v
      this.$emit('calc', v)
    }
    ,onLeave() {
      this.asideShown = null
    }
    ,onBeginEntering(e) {
      e.style.zIndex = 1
      e.parentElement.style.zIndex = 1
    }
    ,onAfterEscaping(e) {
      e.style.zIndex = ''
      e.parentElement.style.zIndex = ''
    }
  }
  //,watch:{
  //   react() {
  //    this.calcTitle()
  //  }
  //  ,breakpointType(v) {
  //    if(v > 0)
  //      this.calcTitle()
  //  }
  //  ,title() { this.calcTitle() }
  //}
  ,mounted() {
    this.calcTitle()
    const F = () => { this.calcTitle() }
    this.$watch('react', F)
    this.$watch('title', F)
    this.$watch('breakpointType', () => { this.calcTitle() })
  }
  ,components:{ Rating, Photo }
}
</script>