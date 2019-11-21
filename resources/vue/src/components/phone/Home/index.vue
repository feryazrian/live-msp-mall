<style>
#app .phoneHome > .page > .title{
  display:flex;
  position:relative;
  font-size:.87em;
  align-items:center;
  /*margin-top:25px;*/
  margin-bottom:7px;
  border-bottom:1px solid rgba(67,67,67,.2)
}
#app .phoneHome > .page > .title > h3{
  margin:0;
  padding:7px 3px;
  display:inline-block;
  position:relative;
  font-weight:400;
  flex-shrink:0
}
#app .phoneHome > .page > .title > h3::after{
  left:0;
  width:100%;
  height:3px;
  bottom:0;
  content:'';
  position:absolute;
  transform:translateY(75%);
  border-radius:3px;
  background-color:#ffca28
}

#app .phoneHome > .page > .title > .timer{
  margin:0 1.5%;
  font-size:.75em;
  display:inline-block;
  position:relative;
  /*font-size:0;*/
  vertical-align:middle
}

#app .phoneHome > .page > .grid{
  display:flex;
  flex-wrap:wrap;
  padding-left:5px;
  padding-right:5px
}
#app .phoneHome > .page > .grid > *{
  margin-top:13px;
  margin-bottom:43px
}
#app .phoneHome > .page > .grid > * > *{ width:100% }

#app .phoneHome > .page > .title > .anchorRight:last-child{
  flex-grow:1
}
#app .phoneHome > .page > .title > .anchorRight:last-child > a{
  float:right;
  color:inherit;
  font-size:.87em;
  text-align:right;
  text-decoration:none
}
</style>

<template>
<div class=phoneHome :style="{ paddingTop:`${ headerHeight }px` }">
  <vueper-slides v-if="bannerList && bannerList.length > 0" class=no-shadow v-bind=bannerOption :fixed-height="breakpoint == 1? '275px' : '145px'" :arrows=false>
    <vueper-slide v-for="e of bannerList" :key=e._ID :image=e.src />
  </vueper-slides>
  <page #default={breakpointType,breakpointName,phone,react} style="margin-top:15px">
    <billing-input :phone=phone style="font-size:.87em" />
    <template v-if="limits.flashSales > 0 && flashSaleList && flashSaleList.length > 0">
    <transition name=source>
    <div class=title>
      <h3>Flash Sale</h3>
      <div class=timer>
        <counter-down :limit=limits.flashSales :cntrl=cntrls.flashSales @clear="limits.flashSales = 0" />
      </div>
    </div>
    </transition>
    <transition name=evolve>
    <div class=skate>
      <h-scrollable unique=slug :width=185 padding="7px 5px 49px" #default={stuff} @select=redirect :stuffs=flashSaleList>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" />
      </h-scrollable>
    </div>
    </transition>
    </template>
    <template v-for="(e, i) of seasonalDiscount">
    <div class=title :key="'header-' + e.slug">
      <h3>{{ e.name }}</h3>
      <div class=timer>
        <counter-down v-if=cntrls.seasonalDiscount[i] :limit="Math.round((new Date(e.expired).getTime() - Date.now()) / 1000)" :cntrl=cntrls.seasonalDiscount[i].status @clear="limit = 0" />
      </div>
      <div class=anchorRight><a :href="'season/' + e.slug">Lihat semua</a></div>
    </div>
    <div class=skate :key="'detail-' + e.slug">
      <h-scrollable unique=slug :width=185 padding="7px 5px 49px" #default={stuff,motion} @select=redirect :stuffs=e.products>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" :motion=motion />
      </h-scrollable>
    </div>
    </template>
    <template v-for="(e, i) of categories">
    <div class=title :key="'header-' + e.slug">
      <h3>{{ e.name }}</h3>
      <div class=anchorRight><a :href="'category/' + e.slug">Lihat semua</a></div>
    </div>
    <h-scrollable unique=slug :width=185 padding="7px 5px 49px" #default={stuff,motion} :stuffs=e.products :key="'detail-' + e.slug">
      <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" :motion=motion />
    </h-scrollable>
    </template>
    <template v-if="discountList && discountList.length > 0">
    <div class=title>
      <h3>Group by Promo</h3>
    </div>
    <div class=skate>
      <h-scrollable unique=slug :width=185 padding="7px 5px 49px" #default={stuff} @select=redirect :stuffs=discountList>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" />
      </h-scrollable>
    </div>
    </template>
    <div class=title>
      <h3>Rekomendasi untuk Kamu</h3>
    </div>
    <div class=grid>
      <div v-for="e of recommendations" :key=e.slug>
        <item-card :value=e :phone=phone :breakpointType=breakpointType :breakpointName=breakpointName :react=react :target="[ 'product/', 'slug' ]" @click=redirect />
      </div>
    </div>
  </page>
  <float-header style="z-index:2" />
  <simple-footer style="margin-top:20px" />
  <transition><router-view /></transition>
</div>
</template>

<script>
import { APP as getApp } from '@/store/getters'
import Photo from '@/components/Photo'
import ItemCard from '@/components/ItemCard'
import HScrollable from '@/components/HScrollable'
import FloatHeader from '@/components/phone/FloatHeader'
import SimpleFooter from '@/components/phone/SimpleFooter'
import { SLIDER_BANNER } from '@/assets/js/options'
import { VueperSlide, VueperSlides } from 'vueperslides'
import CounterDown from '@/components/CounterDown'
import BillingInput from '@/components/Home/BillingInput'

export default {
   name:'phoneHome'
  ,data:() => ({
     cntrls:{
       flashSales:0
      ,seasonalDiscount:[ ]
    }
    ,limits:{
      flashSales:null
    }
    ,bannerList:[ ]
    ,discountList:[ ]
    ,flashSaleList:[ ]
  })
  ,props:{
    // status:Number
     banners:Object
    ,discounts:Object
    ,breakpoint:[ Number, String ]
    ,flashSales:Object
    ,categories:Array
    ,recommendations:[ Array, Object ]
    ,seasonalDiscount:[ Array, Object ]
  }
  ,computed:{
     headerHeight() { return this.$store.getters[ getApp.HEADER.HEIGHT ] }
    ,bannerOption:() => ({
       draggingDistance:75
      ,infinite:true
      ,autoplay:true
      ,speed:5000
    })
  }
  ,methods:{
     getColumn:() => { }
    ,redirect({ value:v }) {
      redirectToProduct(v.slug)
    }
    ,async calcWidth({ n, x = .5, coverShown:shown } = this.getColumn()) {
      let v = this.$el.querySelector(':scope .page>.grid')
      //x = getComputedStyle(v); x = ((this.breakpoint.maxWidth - INNER.reduce((n, k) => n + parseFloat(x[k]), 0) - (2 * margin * (n - 1))) / n)// + ((2 * margin) / n)
      await Promise.resolve(Array.from(v.children).forEach((e, i) => {
        let style = e.style
        //style.width = x + 'px'
        //style.width = ((x * 100) / (x * n)) + '%'
        style.width = (100 - (2 * x) * (n - 1)) / n + '%'
        style.marginLeft = style.marginRight = ''
        if(0 !== i % n)
          style.marginLeft = x + '%'
        if(0 !== (i + 1) % n)
          style.marginRight = x + '%'
      }))
    }
  }
  ,mounted() {
    let updateBanners = v => {
      let x
      if((x = Array.from(v.list)).length > 0) {
        this.bannerList.splice(0)
        x.forEach(e => {
          this.bannerList.push({ _ID:e.id, src:v.image_path + '/' + e.photo, alt:e.name })
        })
      }
    }
    let updateDiscounts = v => {
      let x
      if((x = Array.from(v.data)).length > 0) {
        this.discountList = x
      }
    }
    let updateFlashSales = async v => {
      let x
      if((x = Array.from(v.data)).length > 0) {
        this.flashSaleList = x
        await Promise.resolve(this.limits.flashSales = Math.round(((v.countdown.expiry_timestamp * 1000) - Date.now()) / 1000))
        this.cntrls.flashSales = 1
      }
    }
    let updateSeasonalDiscount = v => {
      if(v.length > 0) {
        let j = this.cntrls.seasonalDiscount
        v.forEach(async(e, i) => {
          await Promise.resolve(j.push({ status:0 }))
          j[i].status = 1
        })
      }
    }
    let o
    if((o = this.banners).hasOwnProperty('list'))
      updateBanners(o)
    if((o = this.discounts).hasOwnProperty('data') && o.hasOwnProperty('image_path'))
      updateDiscounts(o)
    if((o = this.flashSales).hasOwnProperty('data') && o.hasOwnProperty('image_path') && o.hasOwnProperty('countdown'))
      updateFlashSales(o)
    if((o = this.seasonalDiscount).length > 0)
      updateSeasonalDiscount(o)
    this.$watch('banners', updateBanners)
    this.$watch('discounts', updateDiscounts)
    this.$watch('flashSales', updateFlashSales)
    this.$watch('seasonalDiscount', updateSeasonalDiscount)
    const XS = { n:2, x:1.5 }
    const SM = { n:4, x:.75 }
    const MD = { n:4, coverShown:1 }
    const LG = { n:6, coverShown:1 }
    const XL = { n:8, coverShown:1 }
    this.getColumn = () => {
      switch(this.breakpoint) {
        case 1: return SM
        case 2: return MD
        case 3: return LG
        case 4: return XL
      }
      return XS
    }
    //this.calcWidth()
    //this.$watch('breakpoint', () => { this.calcWidth() })
  }
  ,components:{ Photo, ItemCard, HScrollable, FloatHeader, SimpleFooter, CounterDown, VueperSlide, VueperSlides, BillingInput }
}
</script>