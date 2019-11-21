<style>
 #app .vueperslides--touchable .vueperslides__track
,#app .vueperslides--touchable .vueperslides__track--dragging
,#app .vueperslides--touchable .vueperslides__track--mousedown{
  cursor:pointer
}

#app .vueperslides__bullets{
  height:40px
}
#app .vueperslides__bullet{
  width:0;
  height:0;
  border:none;
  padding:4px;
  transition:.3s ease-out;
  border-radius:6px;
  background-color:rgba(97,97,97,.9)
}

#app .vueperslides__bullet--active{
  border:0;
  border-radius:7px;
  padding-left:17px;
  padding-right:17px;
  background-color:rgba(255,202,40,.95)
}
</style>

<style>
#app article.desktopHome{
}

#app article.desktopHome > .page > .title{
  position:relative;
  font-size:1.1em;
  /*margin-top:25px;*/
  display:flex;
  align-items:center;
  margin-bottom:15px;
  border-bottom:1px solid rgba(67,67,67,.2)
}

#app article.desktopHome > .page > .title > h3,#app article.desktopHome > .page > .title > .anchorRight:last-child > a{
  padding:15px 5px
}

#app article.desktopHome > .page > .title > h3{
  margin:0;
  position:relative;
  font-weight:400;
  flex-shrink:0
}
#app article.desktopHome > .page > .title > h3::after{
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

#app article.desktopHome > .page > .title > .anchorRight:last-child{
  flex-grow:1
}
#app article.desktopHome > .page > .title > .anchorRight:last-child > a{
  float:right;
  color:inherit;
  text-align:right;
  text-decoration:none
}

#app article.desktopHome > .page > .title > .timer{
  margin:0 1.5%;
  display:inline-block;
  position:relative;
  /*font-size:0;*/
  vertical-align:middle
}
/* article > .title > .timer::before{
  width:100%;
  height:100%;
  padding:10px 15px;
  box-sizing:content-box;
  //box-shadow:0 3px 1px -2px rgba(0,0,0,0.2),0 2px 2px 0 rgba(0,0,0,0.14),0 1px 5px 0 rgba(0,0,0,0.12);
  border-radius:5px;
  background-color:rgb(255,202,40)
}
 */
#app article.desktopHome > .page > .title > .timer > :only-child{
  position:relative;
  font-size:.77em;
  font-weight:400;
  display:inline-block
}
/*
article.desktopHome > .slider{
  border:1px solid red;
  display:flex;
  //overflow:hidden
}

article.desktopHome > .slider > *{
  margin:5px;
  flex-shrink:0
}
*/
#app article.desktopHome > .page > .skate{
  display:flex
}

#app article.desktopHome > .page > .skate > :nth-child(1){
  flex:0 0 195px;
  /*height:300px;*/
  opacity:1;
  overflow:hidden;
  position:relative;
  margin-top:7px;
  transition:2.5s opacity;
  border-radius:5px 0 0 5px
}
#app article.desktopHome > .page > .skate > :nth-child(1).hidden{
  opacity:0
}

#app article.desktopHome > .page > .skate.hidden > :nth-child(1){
  display:none
}
#app article.desktopHome > .page > .skate > :nth-child(1) > *{
  height:100%
}
#app article.desktopHome > .page > .skate > :nth-child(1) > * > img{
  transition:.5s
}
#app article.desktopHome > .page > .skate > :nth-child(1) > * > img:hover{
  transform:scale(1.15)
}

#app article.desktopHome > .page > .skate > :nth-child(2){
  width:50%;
  flex-grow:1
}
#app article.desktopHome > .page > .skate:not(.hidden) > :nth-child(2){
  margin-left:5px
}

#app article.desktopHome > .page > .grid{
  /*outline:1px dotted blue;*/
  display:flex;
  flex-wrap:wrap;
  padding-left:5px;
  padding-right:5px
}
#app article.desktopHome > .page > .grid > *{
  margin-top:13px;
  margin-bottom:47px
}
#app article.desktopHome > .page > .grid > * > *{ width:100% }

#app article.desktopHome .swiperContainer{
  padding-top:7px;
  padding-left:3px;
  padding-bottom:23px;
}
/*
#app .desktopHome .scaleDown-enter,#app .desktopHome .scaleDown-leave-to{
  opacity:0;
  transform:scale(1.75)!important
}

#app .desktopHome .scaleDown-enter-active,#app .desktopHome .scaleDown-leave-active{
  transition:.37s ease-out
}

#app article.desktopHome > .page > .scaleUp-enter,#app article.desktopHome > .page > .scaleUp-leave-to{
  opacity:0;
  transform:scale(1.05)
}
#app article.desktopHome > .page > .scaleUp-enter-active,#app article.desktopHome > .page > .scaleUp-leave-active{
  transition:1s
}

#app article.desktopHome > .page > .slideRight-enter,#app article.desktopHome > .page > .slideRight-leave-to{
  opacity:0;
  transform:translateX(-50%)
}
#app article.desktopHome > .page > .slideRight-enter-active,#app article.desktopHome > .page > .slideRight-leave-active{
  transition:1s
}*/
</style>

<template>
<article class="desktopHome"
style="position:relative">
  <vueper-slides class=no-shadow v-bind=bannerOption() :style="{ marginTop:`${ headerHeight }px` }">
    <vueper-slide v-for="e of bannerList" :key=e._ID :image=e.src />
    <div slot=arrowRight><font-awesome style="font-size:1em" class=cntr icon=chevron-right /></div>
    <div slot=arrowLeft><font-awesome style="font-size:1em" class=cntr icon=chevron-right /></div>
  </vueper-slides>
  <!-- <page>
    <billing-input style="margin-top:15px" />
  </page> -->

  <!-- <template v-if="flashSaleList && flashSaleList.length > 0">
  <page class=title>
    <h3>Flash Sale / Jual Cepat</h3>
    <div class="timer cntrLowest">Berakhir dalam
      <div>17:39:26</div>
    </div>
  </page> -->

  <!-- <page class=slider> -->
  <!-- <page>
    <swiper class=swiperContainer :options=swiperOption() ref="mySwiper">
      <swiper-slide v-for="(e, i) of sampleProducts" :key=i>
        <item-card v-bind=e :size=190 />
      </swiper-slide>
    </swiper>
  </page> -->
  
  <!-- <page>
    <h-scrollable :stuffs=flashSaleList :padding="'10px 5px'">
      <template v-slot={stuff:e}>
        <item-card v-bind=e :size=190 />
      </template>
    </h-scrollable>
  </page> -->
  
  <!-- </template> -->
  
  <!-- <page class=title>
    <h3>Barang Elektronik</h3>
  </page> -->
  
  <!-- <page>
    <swiper class=swiperContainer :options=swiperOption()>
      <swiper-slide v-for="(e, i) of sampleProducts" :key=i>
        <item-card v-bind=e />
      </swiper-slide>
    </swiper>
  </page> -->

  <!-- <page class=title>
    <h3>Make up Andalan</h3>
  </page>
  <page>
    <h-scrollable :stuffs=sampleProducts :padding="'10px 5px'">
      <template v-slot={stuff:e}>
        <item-card2 v-bind=e :size=190 />
      </template>
    </h-scrollable>
  </page> -->
  <!-- <page class=title>
    <h3>Rekomendasi untuk Kamu</h3>
  </page> -->

  <page #default={breakpointType,breakpointName,phone,react}>
    <billing-input style="margin:15px 0px 43px" />
    <!-- <div class=title>
      <h3>Make up Andalan</h3>
    </div>
    <div class=skate :class=[breakpointName]>
      <div>
        <img :src=covers.promo.image @load="covers.promo.shown = undefined" :style="{ opacity:[ covers.promo.shown? 0 : '' ] }" />
      </div>
      <h-scrollable :stuffs=recommendations :width=201 :padding="'7px 5px 49px'" #default={stuff,index}>
        <item-card :value=stuff is-lower />
      </h-scrollable>
    </div> -->
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
      <div :class={hidden:covers.flashSales.shown}>
        <photo :image=covers.flashSales.image title="Flash Sale" @ready="covers.flashSales.shown = undefined" />
      </div>
      <h-scrollable unique=slug :width=cardWidth padding="7px 5px 49px" #default={stuff} @select=redirect :stuffs=flashSaleList>
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
      <div v-if=covers.seasonalDiscount[i] :class={hidden:covers.seasonalDiscount[i].shown}>
        <photo :image=covers.seasonalDiscount[i].image :title=e.name @ready="covers.seasonalDiscount[i].shown = undefined" />
      </div>
      <h-scrollable unique=slug :width=cardWidth padding="7px 5px 49px" #default={stuff,motion} @select=redirect :stuffs=e.products>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" :motion=motion />
      </h-scrollable>
    </div>
    </template>

    <template v-for="(e, i) of categories">
    <div class=title :key="'header-' + e.slug">
      <h3>{{ e.name }}</h3>
      <div class=anchorRight><a :href="'category/' + e.slug">Lihat semua</a></div>
    </div>
    <div class=skate :key="'detail-' + e.slug">
      <div v-if=covers.category[i] :class={hidden:covers.category.shown}>
        <photo :image=covers.category[i].image :title=e.name @ready="covers.category[i].shown = undefined" />
      </div>
      <h-scrollable unique=slug :width=cardWidth padding="7px 5px 49px" #default={stuff,motion} @select=redirect :stuffs=e.products>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" :motion=motion />
      </h-scrollable>
    </div>
    </template>
    <template v-if="discountList && discountList.length > 0">
    <div class=title>
      <h3>Group by Promo</h3>
    </div>
    <div class=skate>
      <div :class={hidden:covers.discount.shown}>
        <photo :image=covers.discount.image title=Discount @ready="covers.discount.shown = undefined" />
      </div>
      <h-scrollable unique=slug :width=cardWidth padding="7px 5px 49px" #default={stuff} @select=redirect :stuffs=discountList>
        <item-card :value=stuff :phone=phone :target="[ 'product/', 'slug' ]" />
      </h-scrollable>
    </div>
    </template>

    <div class=title>
      <h3>Rekomendasi untuk Kamu</h3>
    </div>
    <div class=grid>
      <div v-for="e of recommendations" :key=e.slug @click="redirect({ value:e })">
        <item-card :value=e :phone=phone :breakpointType=breakpointType :breakpointName=breakpointName :react=react :target="[ 'product/', 'slug' ]" />
      </div>
    </div>
  </page>
  <float-header />
  <simple-footer />
  <transition name=shrink @before-enter=onViewBeginEntering @before-leave=onViewBeginEscaping @after-enter=onViewAfterEntering @after-leave=onViewAfterEscaping><router-view /></transition>
</article>
</template>

<script>
import { mapState } from 'vuex'
import { PATH, redirectToProduct/*, PASSED, bannerList, getFlashSale, getCategoryHighlight, getGroupByPromo, getRecommendation, getSeasonalPromo*/ } from '@/assets/js/fetchers'
import { APP as setApp } from '@/store/actions'
import { USR as getUsr } from '@/store/getters'
import FloatHeader from '@/components/FloatHeader'
import BillingInput from './BillingInput'
import ItemCard from '@/components/ItemCard'
import SimpleFooter from '@/components/SimpleFooter'
import { SLIDER_BANNER } from '@/assets/js/options'
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
//import { swiper, swiperSlide } from 'vue-awesome-swiper'
//import 'swiper/dist/css/swiper.css'
import HScrollable from '@/components/HScrollable'
import Photo from '@/components/Photo'
import CounterDown from '@/components/CounterDown'

export default {
   name:'desktopHome'
  ,data:() => ({
     cntrls:{
       flashSales:0
      ,seasonalDiscount:[ ]
    }
    ,limits:{
      flashSales:null
    }
    ,covers:{
       flashSales:{ image:'', shown:1 }
      ,discount:{ image:'', shown:1 }
      ,category:[ ]
      ,seasonalDiscount:[ ]
    }
    ,bannerList:[ ]
    ,discountList:[ ]
    ,flashSaleList:[ ]
  })
  ,props:{
     banners:Object
    ,discounts:Object
    ,categories:Array
    ,breakpoint:Number
    ,flashSales:Object
    ,recommendations:[ Array, Object ]
    ,seasonalDiscount:[ Array, Object ]
  }
  ,methods:{
     getColumn:() => { }
    ,async calcWidth({ n, x = .5, coverShown:shown } = this.getColumn()) {
      let v = this.$el.querySelector('.page>.grid')
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
      function calcSkate(e) {
        e.classList[shown? 'remove' : 'add']('hidden')
        let v = e.children[0].style
        if(e.childElementCount > 1)
          v.height = e.children[1].querySelector('.swiper>.slider>:nth-child(1)>.itemCard').getBoundingClientRect().height + 'px'
      }
      for(let e of this.$el.querySelectorAll('.page>.skate')) calcSkate(e)
      this.refetch(n)
    }
    //,resolveID(v, f, i = (k, v) => v) {
    //  let x = [ ]
    //  if(!f) f = () => x
    //  Array.from(v).forEach(e => {
    //    f().push(Object.entries(e).reduce((o, [ k, v ]) => {
    //      o[k] = i(k, v)
    //      return o
    //    }, { _ID:e.slug }))
    //  })
    //  return x
    //}
    ,refetch() {
      //let i = this.recommendations.length, j = n * 10
      //if(i > j) {
      //} else
      //if(i < j) {
      //  //getRecommendation(j - i)
      //  //  .then(data => {
      //  //    if(data.code === 200) {
      //  //      Array.from(data.items).forEach(e => {
      //  //        this.recommendations.push(Object.entries(e).reduce((o, [ k, v ]) => {
      //  //          o[k] = v
      //  //          return o
      //  //        }, { _ID:e.slug }))
      //  //      })
      //  //    } else
      //  //      console.log('Recommendations not available')
      //  //  })
      //}
    }
    ,bannerOption:() => SLIDER_BANNER
    //,swiperOption:() => SWIPER
    ,onViewBeginEntering() {
      document.body.style.transition = '.37s ease-out'
    }
    ,onViewAfterEntering() {
      document.body.style.transition = ''
    }
    ,onViewBeginEscaping() {
      document.body.style.transition = '.37s ease-out'
      if(this.$router.currentRoute.name === 'home') document.body.classList.remove('frozen')
    }
    ,onViewAfterEscaping() {
      document.body.style.transition = ''
      if(this.$router.currentRoute.name === 'home') this.$store.dispatch(setApp.FREEZE, false)
    }
    ,redirect({ value:v }) {
      //window.open(TARGET + '/product/' + v.slug, '_self')
      redirectToProduct(v.slug)
      //this.$router.push({ name:'product', params:{ path:v.slug, args:v } })
    }
  }
  ,computed:{
     ...mapState({
       headerHeight:state => state.layout.headerHeight
  //    //,sampleItemCard:state => state.sample.itemCard
  //    ,sampleProducts:state => state.sample.products
  //    //,bundleProducts:state => {
  //    //  let bundle = []
  //    //  for(let i = -1; ++i < 3; )
  //    //    for(let e of state.sample.products)
  //    //      bundle.push(e)
  //    //  bundle.pop()
  //    //  bundle.pop()
  //    //  bundle.pop()
  //    //  return bundle
  //    //}
  //    //,banners:state => state.sample.banners
    })
    ,cardWidth() {
      switch(this.breakpoint) {
        case 4: return 218
        case 3: return 203
        case 2: return 187
      }
      return 0
    }
  }
  ,mounted() {
    //let v
    //if((v = this.appBuffer['/'])) {
    //  this.breakpoint = v.breakpoint
    //  this.setAppBuffer({ k:'/' })
    //}
    //bannerList()
    //  .then(data => {
    //    if(data.code === 200) {
    //      Array.from((data = data.items).list).forEach(e => {
    //        this.bannerList.push({ _ID:e.id, src:data.image_path + '/' + e.photo, alt:e.name })
    //      })
    //    } else
    //      console.log('Banners not available')
    //  }).catch(e => { console.log(e) })

    //getFlashSale()
    //  .then(data => {
    //    if(data.code === 200) {
    //      this.flashSaleList = (data = data.items).data
    //      covers.flash.image = PATH.option(data.image_path)
    //      this.limit = Math.round(((data.countdown.expiry_timestamp * 1000) - Date.now()) / 1000)
    //    } else
    //      console.log('Flash sales not available')
    //  })
    //  .then(() => {
    //    this.cntrl = 1
    //  }).catch(e => { console.log(e) })
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
        this.covers.discount.image = PATH.option(v.image_path)
      }
    }
    let updateFlashSales = async v => {
      let x
      if((x = Array.from(v.data)).length > 0) {
        this.flashSaleList = x
        this.covers.flashSales.image = PATH.option(v.image_path)
        await Promise.resolve(this.limits.flashSales = Math.round(((v.countdown.expiry_timestamp * 1000) - Date.now()) / 1000))
        this.cntrls.flashSales = 1
      }
    }
    let updateCategories = v => {
      if(v.length > 0) {
        let x = this.covers.category
        x.splice(0)
        v.forEach(e => { x.push({ image:PATH.category(e.cover), shown:1 }) })
      }
    }
    let updateSeasonalDiscount = v => {
      if(v.length > 0) {
        let x = this.covers.seasonalDiscount, j = this.cntrls.seasonalDiscount
        x.splice(0)
        v.forEach(async(e, i) => {
          x.push({ image:PATH.season(e.background), shown:1 })
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
    if((o = this.categories).length > 0)
      updateCategories(o)
    if((o = this.seasonalDiscount).length > 0)
      updateSeasonalDiscount(o)
    this.$watch('banners', updateBanners)
    this.$watch('discounts', updateDiscounts)
    this.$watch('flashSales', updateFlashSales)
    this.$watch('categories', updateCategories)
    this.$watch('seasonalDiscount', updateSeasonalDiscount)

    //let covers = { category:[ ], flash:{ }, count:{ }, seasonalPromo:[ ] }
    const INNER = [ 'borderRightWidth', 'borderLeftWidth', 'paddingRight', 'paddingLeft' ]
    const XS = { n:2, x:1.75 }
    const SM = { n:3, x:1.25 }
    const MD = { n:5, coverShown:1 }
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
    //let recommendations
    //Promise.allSettled([ getGroupByPromo(), getCategoryHighlight(), getRecommendation(), getSeasonalPromo() ]).then(result => {
    //   [ v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.discountList = (v = v.items).data
    //          covers.count = { image:PATH.option(v.image_path) }
    //        } else console.log('Promotions not available')
    //      } else {
    //        covers.count = { }
    //        console.log(v.reason)
    //      }
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          let i = covers.category, j = this.covers.category
    //          Array.from(this.categoryList = v.items).forEach(e => {
    //            i.push({ image:PATH.category(e.cover), shown:1 })
    //            j.push({ image:'', shown:1 })
    //          })
    //        } else console.log('Category highlights not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          recommendations = v.items
    //        } else console.log('Recommendations not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          let i = covers.seasonalPromo, j = this.covers.seasonalPromo
    //          Array.from(this.seasonalPromo = v.items).forEach(e => {
    //            i.push({ image:PATH.season(e.background), shown:1 })
    //            j.push({ image:'', shown:1 })
    //            this.cntrls.push({ status:0 })
    //          })
    //        } else console.log('Seasonal promo not available')
    //      } else console.log(v.reason)
    //    }
    //  ].forEach((e, i) => { e(result[i]) })
    //  this.covers = covers
    //  this.recommendations = recommendations
    //}).then(() => {
    //  this.calcWidth()
    //  this.$watch('breakpoint', (v) => { this.calcWidth() })
    //  for(let i = -1, n = this.cntrls.length; ++i < n; )
    //    this.cntrls[i].status = 1
    //})
  }
  //,watch:{ breakpoint() { this.calcWidth() } }
  //,beforeDestroy() {
  //  this.setAppBuffer({ k:'/', v:{ breakpoint:this.breakpoint } })
  //}
  ,components:{ FloatHeader, BillingInput, ItemCard, SimpleFooter, HScrollable, Photo, CounterDown, VueperSlides, VueperSlide }
}
</script>