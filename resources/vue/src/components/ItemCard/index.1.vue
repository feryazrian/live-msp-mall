<style>
.discount::after{
  width:100%;
  height:2px;
  margin-top:1px;
  border-radius:2px;
  background-color:rgba(139,139,139,.75)
}
</style>

<style scoped>
article.itemCard{
  /*border:1px solid red;*/
  display:inline-block;
  transition:.25s ease-out;
  background-color:#fff
}
article.itemCard:hover{
  box-shadow:0px 3px 1px -2px rgba(0,0,0,.07),0px 2px 2px 0px rgba(0,0,0,.14),0px 1px 5px 0px rgba(0,0,0,.12)
}

article.itemCard,article.itemCard figure > img{
  border-radius:5px;
}

article.itemCard figure{
  margin:0;
  position:relative
}
article.itemCard figure > img{
  /*border-bottom:1px solid blue;*/
  width:100%;
  object-fit:cover;
  border-bottom-left-radius:0;
  border-bottom-right-radius:0
}

article.itemCard header{
  /*border:1px solid green;*/
  overflow:hidden;
  position:relative;
  margin-top:10px
}
/*
article header::after{
  top:0;
  left:0;
  width:17px;
  height:100%;
  content:'';
  position:absolute;
  background:linear-gradient(to right,#fff 25%,transparent)
}
*/

article.itemCard header > h3{
  /*color:#f9a825;*/
  color:rgba(53,53,53,1);
  right:0;
  margin:0;
  height:2.5em;
  font-size:1.15em;
  text-shadow:1px 1px 1px rgba(0,0,0,.21);
  font-weight:400;
  font-family:'Roboto Condensed',sans-serif
}

article.itemCard main{
  /*border:2px solid cyan;*/
  color:#878787;
  padding-top:10px;
  padding-bottom:10px
}

article.itemCard main > .vendor{
  font-size:.85em;
  font-family:'Roboto Condensed',sans-serif;
  margin-bottom:10px
}

article.itemCard main .charge{
  position:relative
}

article.itemCard main .charge > :nth-child(1){
  color:#737373;
  padding:0 2px;
  display:inline-block;
  position:relative;
  font-size:1em;
  font-family:'Open Sans Condensed',sans-serif
}

article.itemCard main .charge > :nth-child(2){
  color:#495f69;
  font-size:1.25em;
  font-weight:600
}
article.itemCard main .charge > :nth-child(2).blink{
  color:#ba1239
}

article.itemCard main .scores{
  display:flex;
  margin-top:7px;
  justify-content:center
}

article.itemCard main .scores > :nth-child(1){
  width:75%
}

article.itemCard main .scores > :last-child{
  font-size:.75em;
  padding:2px 7px;
  text-shadow:none
}
article.itemCard main .scores > :last-child:hover{
  color:#f9a825
}

/*
article:hover{
  z-index:1;
  border-bottom-right-radius:0;
  border-bottom-left-radius:0;
  box-shadow:0 5px 19px 7px rgba(0,0,0,.15),1px 3px 11px 0 rgba(0,0,0,.15)
  //box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}

article aside{
  width:100%;
  //bottom:0;
  cursor:default;
  position:absolute;
  //transform:translateY(100%);
  box-shadow:0 5px 19px 0 rgba(0,0,0,.15),0 3px 11px 0 rgba(0,0,0,.15);
  font-weight:400;
  padding-top:5px;
  border-radius:0 0 7px 7px;
  padding-bottom:5px;
  background-color:#fff
}

article .order{
  padding:7px;
  display:flex;
  position:relative;
  font-size:1.15em;
  align-items:center;
  border-radius:23px;
  background:linear-gradient(to top,#ffd600,#ffff8d)
}
article .order > *{
  flex-grow:1
}

article .order > button{
  //border:1px solid red
}

article .order > .count{
  border:1px solid #f5f5f5;
  padding:4px 4px 4px 7px;
  display:flex;
  position:relative;
  border-radius:17px;
  background-color:#ffd600
}
article .order > .count > :not(:last-child){
  margin-right:3px
}

article .order > .count > input{
  width:30px;
  text-align:center;
  flex-grow:1;
}

article .order > .count > button{
  padding:12px;
  position:relative;
  font-weight:bold;
  border-radius:50%;
  background-color:#ffa000
}
article .order > .count > button:hover{
  background-color:#f57f17
}

article .order > .count > button::before{
  width:60%;
  height:2px;
  background-color:#fff
}
article .order > .count > button:nth-child(3)::after{
  width:2px;
  height:60%;
  background-color:#fff
}

article.flux .xtra-enter-active,article.flux .xtra-leave-active{
  transition:all .15s ease-out
}
article.flux .xtra-enter,article.flux .xtra-leave-to{
  opacity:0;
  transform:translateY(-25px)
}
 article.flux .xtra-enter-active
,article.flux .xtra-leave-active
,article.flux .xtra-enter-to
,article.flux .xtra-leave{
  z-index:1
}

article.flux header > h3{
  width:100%;
  height:100%;
  overflow:hidden;
  position:relative;
  transition:all .75s ease-out;
  white-space:nowrap;
  text-overflow:ellipsis
}
 article.flux
,article.flux .order > .count > button{
  transition:all .25s ease-out
}

*/
article.itemCard .layout{
  padding-left:15px;
  padding-right:15px
}
</style>

<template>
<article :class="[ 'itemCard', { flux } ]" :style="{ width:`${ size }px` }" @mouseenter=mouseEnter @mouseleave=mouseLeave
style="position:relative;font-family:'Open Sans',sans-serif;cursor:pointer">
  <figure>
    <img :src=image :alt=title :style="{ height:`${ size }px` }" />
  </figure>
  <header class=layout>
    <h3 ref=mainText :title=title>{{ title }}</h3>
  </header>
  <main class=layout>
    <div class="vendor">{{ vendor.name }}</div>
    <div class="charge">
      <div class="cntrHigher discount">Rp&#160;{{ formatter(promo) }}</div>
      <div :class="{ blink:promo }">Rp&#160;{{ formatter(price) }}</div>
    </div>
    <div class="scores">
      <rating :value=rating />
      <span>{{ reviewer && reviewer > 0? formatter(reviewer) : 0 }}</span>
      <!-- <star-rating :rating=rating :read-only=true :show-rating=false :star-size=15 :increment=0.01 :fixed-points=2 :star-points="[23,2,14,17,0,19,10,34,7,50,23,43,38,50,36,34,46,19,31,17]"></star-rating> -->
    </div>
  </main>
  <transition name="xtra">
  <aside class=layout v-if=xtraShown>
    <div class="order">
      <button>Beli</button>
      <div class="count">
        <input type=text v-model=qty />
        <button @click=subQty class="centerHead"></button>
        <button @click=addQty class="centerHead centerFoot"></button>
      </div>
    </div>
  </aside>
  </transition>
</article>
</template>

<script>
import Rating from '@/components/obsolete/Rating'
//import StarRating from 'vue-star-rating'

export default {
   name:'itemCard'
  ,data:() => ({
     qty:0
    ,state:{
       mainText:{
         width:null
        ,right:null
      }
    }
    ,xtraShown:null
  })
  ,props:{
     flux:{ type:Boolean, default:false }
    ,size:{ type:Number  }
    ,image:{ type:String }
    ,title:{ type:String }
    ,price:{ type:Number }
    ,promo:{ type:Number }
    ,vendor:{ type:Object }
    ,rating:{ type:Number, default:0 }
    ,reviewer:{ type:Number, default:0 }
    ,discount:{ type:Number }
  }
  ,methods: {
     mouseEnter() {
    //  let i
    //  if((i = this.state.mainText.right) > 0) {
    //    let s = this.$refs.mainText.style
    //    s.width = `${ this.state.mainText.width }px`
    //    s.right = `${ i }px`
    //    s.transitionDuration = ((i = i / 125.) < .75? .75 : i) + 's'
    //  }
    //  this.xtraShown = true
    }
    ,mouseLeave() {
    //  if(this.state.mainText.right > 0) {
    //    let s = this.$refs.mainText.style
    //    s.width = ''
    //    s.right = ''
    //    s.transitionDuration = null
    //  }
    //  this.xtraShown = null
    }
    ,formatter:v => v.toLocaleString('id-ID')
    ,async calcTitleWidth() {
    //  let e = this.$refs.mainText
    //  let i = e.getBoundingClientRect().width
    //  await Promise.resolve(e.style.display = 'inline')
    //  let j = e.getBoundingClientRect().width
    //  await Promise.resolve(e.style.display = '')
    //  this.state.mainText.width = j
    //  this.state.mainText.right = j - i
    }
    ,addQty() {
    //  if(this.qty < 10000)
    //    ++this.qty
    }
    ,subQty() {
    //  if(this.qty > 1)
    //    --this.qty
    }
  }
  ,computed:{
  }
  ,watch:{
     flux() {
      this.calcTitleWidth()
    }
    //,title() {
    //  this.calcTitleWidth()
    //}
  }
  ,mounted() {
    //this.calcTitleWidth()
  }
  ,components:{ Rating/*StarRating*/ }
}
</script>