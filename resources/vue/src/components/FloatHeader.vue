<style>
#app .floatHeader .searchButton::before,#app .floatHeader .searchButton > :only-child::before{
  background-color:#ffb300
}
</style>

<style scoped>
.floatHeader{
  position:relative
}

.floatHeader > *{
  display:inline-block;
  vertical-align:middle
}

.floatHeader > :nth-child(1){
  width:10%;
  position:relative
}

.floatHeader > :nth-child(1) > img{
  width:90px;
  height:50px;
  object-fit:cover
}

.floatHeader > :last-child{
  width:90%;
  position:relative
}

.floatHeader > :last-child > *{
  display:inline-block;
  vertical-align:middle
}

.floatHeader > :last-child > :nth-child(1){
  width:4%;
  color:#6c757d;
  cursor:pointer;
  font-size:1.25em;
  margin-right:1%
}

.floatHeader > .cartShown:last-child > .searchBox{
  width:80%
}
.floatHeader > :last-child > .searchBox{
  width:85%;
  padding-right:25px
}

.floatHeader > :last-child > button:last-child{
  width:10%;
  border:2px solid #ffb300;
  padding:7px 17px;
  box-shadow:none;
  border-radius:21px;
  background-color:#fff
}

.transitionCategory-enter,.transitionCategory-leave-to{
  opacity:0
}
.transitionCategory-enter-active,.transitionCategory-leave-active{
  transition:.15s ease-out
}

.floatHeader > .cartShown:last-child > .cart{
  width:5%;
  display:inline-block
}
.floatHeader .cart{
  width:0;
  display:none;
  position:relative
}
.floatHeader .cart > img{
  width:50%
}
.floatHeader .cart > :nth-child(2)::before{
  width:21px;
  height:21px;
  box-sizing:content-box;
  border-radius:50%;
  background-color:rgba(255,179,0,.93)
}
.floatHeader .cart > :nth-child(2){
  top:-13px;
  left:15px;
  position:absolute;
}
.floatHeader .cart > :nth-child(2) > :only-child{
  color:#fff;
  position:relative;
  font-size:.75em;
  font-weight:700
}

.floatHeader .profilePict{
  width:10%
}
.floatHeader .profilePict > img{
  width:34%;
  border-radius:50%
}

.floatHeader > :nth-child(2) > .cover{
  top:0;
  right:0;
  width:100%;
  height:100%;
  z-index:1;
  position:fixed
}

.floatHeader > :nth-child(2) > .category{
  top:47px;
  left:0;
  z-index:1;
  position:absolute;
}
</style>

<template>
<div ref=floatHeader class=simpleHeader
style="position:fixed;top:0;width:100%;padding:10px;z-index:2;background-color:#fff">
  <page class=floatHeader #default={phone}>
    <a href="/"><img :src="imagePath('mall.png')" alt="MSPMall" /></a>
    <div :class={cartShown:usrName}>
      <font-awesome icon=list-ul @click="categoryShown = !categoryShown" />
      <div v-if=categoryShown class=cover @click="categoryShown = false"></div>
      <transition name=transitionCategory>
      <!-- <div class=category v-if=categoryShown>
        <ul><li v-for="e of category" :key=e._ID @click=select(e.slug)><span>{{ e.text }}</span></li></ul>
      </div> -->
      <v-scrollable v-if=categoryShown class="category elevation-3" @select=onSelect :stuffs=category unique="_ID" header="text" height=435px />
      </transition>
      <banana class=searchBox :class={phone} label="Apa yang kamu cari?" :color=borderColor() cssClass=searchButton>
        <font-awesome icon=search style=color:#fff />
      </banana>
      <!-- <div class=searchBox>
        <input class=app__basic_theme type=text placeholder="Apa yang kamu cari?" />
        <button class=app__basic_theme><font-awesome icon=search class=center /></button>
      </div> -->
      <a class=cart href="/cart">
        <img :src="imagePath('cart.png')" />
        <div class=cntrLowest><span>{{ totalCart }}</span></div>
      </a>
      <a v-if=usrName class=profilePict href="/setting">
        <img :src=photoPath(profilePicture) :alt=usrName />
      </a>
      <button v-else class=app__basic_theme @click=actionLog>Masuk</button>
    </div>
  </page>
</div>
</template>

<script>
import { mapGetters } from 'vuex'
import { USR as getUsr, APP as getApp } from '@/store/getters'
import { PATH, getCategory, getCartCounter } from '@/assets/js/fetchers'
import { mapMutations } from 'vuex'
import Banana from '@/components/Banana'
import { AMBER_DARKEN_1 } from '@/assets/js/style'
import { SET_HEADER_HEIGHT } from '@/store/mutations'
import VScrollable from '@/components/VScrollable'

export default {
   name:'floatHeader'
  ,data:() => ({
     category:[ ]
    ,categoryShown:null
    ,usrStat:null
  })
  ,computed:{
    ...mapGetters({ usrName:getUsr.NAME, appStatus:getApp.STATUS, username:getUsr.USERNAME, profilePicture:getUsr.PROFILE_PICTURE })
    ,totalCart() {
      let v = this.usrStat
      return v? (v = v.cart)? v : 0 : 0
    }
  }
  ,methods:{
     ...mapMutations([ SET_HEADER_HEIGHT ])
    ,imagePath:v => PATH.image(v)
    ,photoPath:v => PATH.photo(v)
    ,borderColor:() => AMBER_DARKEN_1
    ,actionLog() {
      this.usrName? window.open('/logout', '_self') : this.$router.push({ name:'login' })
    }
    ,onSelect({ value:v }) {
      v = v.slug
      this.$el.insertAdjacentHTML('beforeend', `<form id=categoryList class=hidden method=GET action=search><input type=text name=category value=${ v } /><input type=text name=type value=1 /><input type=text name=condition value=1 /><input type=text name=location value=0 /><input type=text name=min value=10000 /><input type=text name=max value=500000 /><input type=text name=sort value=new /></form>`)
      categoryList.submit()
    }
  }
  ,mounted() {
    getCartCounter().then(data => {
      this.usrStat = data
    })
    this[SET_HEADER_HEIGHT](this.$refs.floatHeader.getBoundingClientRect().height)
    let category = this.category
    category.splice(0, category.length)
    getCategory()
      .then(data => {
        if(data.code === 200) {
          Array.from(data.items).forEach(e => {
            category.push({ _ID:e.id, text:e.name, slug:e.slug })
          })
        } else
          console.log('Categories not available')
      }).catch(e => { console.log(e) })
  }
  ,components:{ Banana, VScrollable }
}
</script>