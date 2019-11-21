<style scoped>
/*.container{
  border:1px dotted red;
  padding:10px 5%;
}
.container > *{
  margin:0 25px;
  display:inline-block;
  vertical-align:middle
}
button,input[type=button],input[type=submit]{
  border:0;
  padding:10px 25px;
  border-radius:33px
}*/
</style>

<style>
/*

#app .font-sm.font-thin,#app .font-sm .font-thin{
  color:rgba(67,67,67,.67);
  font-weight:300
}

.mainLayout .font-sm.font-thin *,.mainLayout .font-sm .font-thin *{
  color:inherit;
  font-weight:inherit
}
*/

/*
.font-thin-sm,.font-bold-sm{
  font-size:.75em
}
.font-thin-sm{
  color:rgba(67,67,67,.67);
  font-weight:300;
  text-shadow:0 0 3px rgba(67,67,67,.05),1px 0 1px rgba(67,67,67,.31),0 1px 1px rgba(0,0,0,.07)
}
.font-bold-sm{
  color:rgba(67,67,67,.67);
  font-weight:500;
  text-shadow:0 0 2px rgba(0,0,0,.1),1px 0 1px rgba(0,0,0,.17),0 1px 1px rgba(67,67,67,.17)
}

button.font-thin-sm,input[type=submit].font-thin-sm{
  color:rgba(0,0,0,.87);
  text-shadow:0 0 2px rgba(0,0,0,.27),1px 0 1px rgba(0,0,0,.17),0 1px 1px rgba(0,0,0,.31)
}
button.font-bold-sm,input[type=submit].font-bold-sm{
  color:rgba(37,37,37,.67);
  font-weight:700;
  text-shadow:0 0 3px rgba(0,0,0,.27),1px 0 1px rgba(0,0,0,.21),0 1px 1px rgba(0,0,0,.1)
}


.font-thin-lg,.font-bold-lg{
  font-size:2em
}
.font-thin-lg{
  color:rgba(67,67,67,.67);
  font-weight:400;
  text-shadow:0 1px 3px rgba(0,0,0,.23),0 1px 1px rgba(0,0,0,.17),1px 1px 2px rgba(0,0,0,.31)
}

.font-bold-lg{
  color:rgba(67,67,67,.97);
  font-weight:600;
  text-shadow:1px 0 1px rgba(0,0,0,.17),0 1px 1px rgba(0,0,0,.13)
}



.openSans-thin-sm,.openSans-bold-sm,.openSans-thin-lg,.openSans-bold-lg{
  font-family:'Open Sans',sans-serif
}

.openSans-thin-sm,.openSans-bold-sm{
  font-size:.75em
}
.openSans-thin-sm{
  color:rgba(57,57,57,.93);
  font-weight:300;
  text-shadow:1px 0 2px rgba(0,0,0,.23),0 1px 1px rgba(0,0,0,.1)
}
.openSans-bold-sm{
  color:rgba(57,57,57,.97);
  font-weight:600;
  text-shadow:1px 0 1px rgba(0,0,0,.15),0 1px 1px rgba(0,0,0,.17)
}

button.openSans-bold-sm,input[type=submit].openSans-bold-sm{
  text-shadow:1px 0 1px rgba(0,0,0,.2),0 1px 1px rgba(0,0,0,.15)
}


.openSans-thin-lg,.openSans-bold-lg{
  font-size:2em
}
.openSans-thin-lg{
  color:rgba(57,57,57,.87);
  font-weight:300;
  text-shadow:1px 0 2px rgba(0,0,0,.05),0 1px 1px rgba(0,0,0,.13)
}
.openSans-bold-lg{
  color:rgba(57,57,57,.97);
  font-weight:600;
  text-shadow:1px 0 1px rgba(0,0,0,.15),0 1px 1px rgba(0,0,0,.17)
}
*/
</style>

<template>
  <!-- <div style="width:1264px;display:block;margin:0 auto">
  <item-detail v-bind=itemDetail />
  </div>
  <div style="z-index:1;padding:50px">
    <float-input label="E-mail Anda" style="width:250px" color=#f00 />
  </div>
  <item-card v-bind=test :flux=flux :dimen=250 /> -->
<div v-if="appStatus == 1">
  <phone-home v-if=phone ref=phoneHome :breakpoint=breakpoint :banners=bannerList :discounts=discountList :categories=categoryList :flash-sales=flashSaleList :seasonal-discount=seasonalDiscount :recommendations=recommendations />
  <desktop-home v-else ref=desktopHome :breakpoint=breakpoint :banners=bannerList :discounts=discountList :categories=categoryList :flash-sales=flashSaleList :seasonal-discount=seasonalDiscount :recommendations=recommendations />
</div>
</template>

<script>
//import ItemCard from '@/components/ItemCard'
//import FloatInput from '@/components/FloatInput'
//import ItemDetail from './../components/ItemDetail'
import { PATH, PASSED, bannerList, getFlashSale, getGroupByPromo, getRecommendation, getCategoryHighlight, getSeasonalPromo } from '@/assets/js/fetchers'
import { BREAKPOINT, APP as getApp } from '@/store/getters'
import { TITLE } from '@/assets/js/words'
import Home from '@/components/Home'
import PhoneHome from '@/components/phone/Home'

export default {
   name:'home'
  ,data:() => ({
     phone:null
    ,bannerList:{ }
    ,discountList:{ }
    ,categoryList:[ ]
    ,flashSaleList:{ }
    ,seasonalDiscount:{ }
    ,recommendations:{ }
    // flux:null
  })
  ,computed:{
     appStatus() { return this.$store.getters[ getApp.STATUS ] }
    ,breakpoint() { return this.$store.getters[ BREAKPOINT ].n }
  }
  ,watch:{
     appStatus(v) {
      if(1 === v) {
        this.phone = 1 >= this.breakpoint
        this.$watch('breakpoint', v => {
          this.phone = 1 >= this.breakpoint
        })
      }
    }
  }
  ,mounted() {
    bannerList()
      .then(data => {
        if(data.code === 200)
          this.bannerList = data.items
        else console.log('Banners not available')
      }).catch(e => { console.log(e) })
    Promise.allSettled([ getFlashSale(), getGroupByPromo(), getCategoryHighlight(), getRecommendation(), getSeasonalPromo() ]).then(result => {
       [ v => {
          if(v.status === PASSED) {
            if((v = v.value).code === 200) {
              this.flashSaleList = v.items
            } else console.log('Flash sales not available')
          } else console.log(v.reason)
        }
        ,v => {
          if(v.status === PASSED) {
            if((v = v.value).code === 200) {
              this.discountList = v.items
            } else console.log('Promotions not available')
          } else console.log(v.reason)
        }
        ,v => {
          if(v.status === PASSED) {
            if((v = v.value).code === 200) {
              this.categoryList = v.items
            } else console.log('Category highlights not available')
          } else console.log(v.reason)
        }
        ,v => {
          if(v.status === PASSED) {
            if((v = v.value).code === 200) {
              this.recommendations = v.items
            } else console.log('Recommendations not available')
          } else console.log(v.reason)
        }
        ,v => {
          if(v.status === PASSED) {
            if((v = v.value).code === 200) {
              this.seasonalDiscount = v.items
            } else console.log('Seasonal promo not available')
          } else console.log(v.reason)
        }
      ].forEach((e, i) => { e(result[i]) })
    }).then(() => {
      this.$refs[ this.phone? 'phoneHome' : 'desktopHome' ].calcWidth()
      this.phone = 1 >= this.breakpoint
      this.$watch('breakpoint', v => {
        this.$refs[ this.phone? 'phoneHome' : 'desktopHome' ].calcWidth()
      })
    })

    //getFlashSale()
    //  .then(data => {
    //    if(data.code === 200)
    //      this.flashSaleList = data.items
    //    else console.log('Flash sales not available')
    //  }).catch(e => { console.log(e) })
    //getGroupByPromo()
    //  .then(data => {
    //    if(data.code === 200)
    //      this.discountList = data.items
    //    else console.log('Promotions not available')
    //  }).catch(e => { console.log(e) })
    //getCategoryHighlight()
    //  .then(data => {
    //    if(data.code === 200)
    //      this.categoryList = data.items
    //    else console.log('Category highlights not available')
    //  }).catch(e => { console.log(e) })
    //getRecommendation(this.phone? 34 : 30)
    //  .then(data => {
    //    if(data.code === 200)
    //      this.recommendations = data.items
    //    else console.log('Recommendations not available')
    //  }).catch(e => { console.log(e) })
    //getSeasonalPromo()
    //  .then(data => {
    //    if(data.code === 200) {
    //      this.seasonalDiscount = data.items
    //    }
    //    else console.log('Seasonal promo not available')
    //  }).catch(e => { console.log(e) })

    //Promise.allSettled([ getGroupByPromo(), getCategoryHighlight(), getRecommendation(), getSeasonalPromo() ]).then(result => {
    //   [ /*v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.bannerList = v
    //        } else console.log('Banners not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.flashSaleList = v
    //        } else console.log('Flash sales not available')
    //      } else console.log(v.reason)
    //    }
    //    ,*/v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.discountList = v
    //        } else console.log('Promotions not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.categoryList = v.items
    //        } else console.log('Category highlights not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.recommendations = v
    //        } else console.log('Recommendations not available')
    //      } else console.log(v.reason)
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.seasonalDiscount = v
    //        } else console.log('Seasonal promo not available')
    //      } else console.log(v.reason)
    //    }
    //  ].forEach((e, i) => { e(result[i]) })
    //})

    //Promise.allSettled([ bannerList(), getGroupByPromo(), getRecommendation(32), getCategoryHighlight() ]).then(result => {
    //   [ v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          Array.from((v = v.items).list).forEach(e => {
    //            this.bannerList.push({ _ID:e.id, src:v.image_path + '/' + e.photo, alt:e.name })
    //          })
    //        } else console.log('Banners not available')
    //      } else { console.log(v.reason) }
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.discountList = (v = v.items).data
    //          this.cover.discount.image = PATH.option(v.image_path)
    //        } else console.log('Promotions not available')
    //      } else { console.log(v.reason) }
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          this.recommendations = v.items
    //        } else console.log('Recommendations not available')
    //      } else { console.log(v.reason) }
    //    }
    //    ,v => {
    //      if(v.status === PASSED) {
    //        if((v = v.value).code === 200) {
    //          console.l
    //          let i = this.cover.category
    //          Array.from(this.categoryList = v.items).forEach(e => {
    //            i.push({ image:PATH.category(e.cover), shown:1 })
    //          })
    //        } else console.log('Category highlights not available')
    //      } else { console.log(v.reason) }
    //    }
    //  ].forEach((e, i) => { e(result[i]) })
    //}).then(() => {
    //  if(this.phone) this.$refs.phoneHome.calcWidth()
    //})
  }
  ,beforeRouteEnter(into, from, next) {
    document.head.querySelector('title').innerText = TITLE[from.path]
    next()
  }
  ,components:{ desktopHome:Home, phoneHome:PhoneHome }
}
</script>