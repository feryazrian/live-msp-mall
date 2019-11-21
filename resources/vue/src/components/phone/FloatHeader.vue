<style>
#app .phoneHeader main{
  color:#90a4ae;
  display:flex;
  padding:10px 15px;
  font-size:1.25em;
  align-items:center;
  background-color:#fff
}
/*#app .phoneHeader main > *{
  display:inline-block;
  vertical-align:middle
}*/
#app .phoneHeader main > :nth-child(1){
  /*width:11%*/
}

#app .phoneHeader main > :nth-child(1) > :only-child{
  /*outline:1px dotted red;*/
  margin:0 auto;
  display:block;
  cursor:pointer
}

#app .phoneHeader main > :nth-child(2){
  /*width:60%;*/
  display:inline-flex;
  padding:3px 10px;
  flex-grow:1;
  margin-left:13px;
  align-items:center;
  margin-right:13px;
  border-radius:5px;
  background-color:rgba(207,216,220,.23)!important
}
/*#app .phoneHeader main > :nth-child(2) > *{
  display:inline-block;
  vertical-align:middle
}*/

#app .phoneHeader main > :nth-child(2) > input{
  color:#546e7a;
  /*width:87%;*/
  border:none;
  height:30px;
  flex-grow:1;
  font-size:.83em;
  background-color:transparent!important
}
#app .phoneHeader main > :nth-child(2) > :nth-child(2){
  margin-left:5px
  /*width:10%;
  margin-left:3%*/
}

#app .phoneHeader main > :nth-child(3){
  /*width:25%;*/
  font-size:1.2em
}

#app .phoneHeader main > :nth-child(3) .profilePict > img{
  height:33px;
  object-fit:cover;
  border-radius:50%
}

#app .cursor{
  cursor:pointer
}

#app .phoneHeader > main > .category{
  top:0;
  left:125px;
  color:#333;
  position:absolute;
  transform:translate(-120px,54px);
  font-size:.73em;
  box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12);
  border-radius:3px;
  background-color:#fff
}
#app .phoneHeader > main > .cover{
  top:0;
  right:0;
  width:100%;
  height:100%;
  position:fixed
}

#app .phoneHeader > main > .category ul > li{
  margin:0 10px;
  padding:11px 0;
  white-space:nowrap
}
/*
#app .phoneHeader > main > .category ul{
  padding:7px 0;
}

#app .phoneHeader > main > .category ul > li:not(:last-child){
  border-bottom:1px solid rgba(67,67,67,.17);
}

#app .phoneHeader > main > .category ul > li > :only-child{
  cursor:pointer;
}
*/
#app .phoneHeader > main > .transitionCategory-enter,.transitionCategory-leave-to{
  opacity:0
}
#app .phoneHeader > main > .transitionCategory-enter-active,.transitionCategory-leave-active{
  transition:.15s ease-out
}

#app .phoneHeader > aside{
  top:0;
  right:0;
  width:100%;
  height:100%;
  display:flex;
  position:fixed;
  flex-direction:column
}

#app .phoneHeader > aside > :nth-child(1){
  display:flex;
  padding:10px 17px;
  align-items:center;
  border-bottom:1px #cfcfcf solid;
  justify-content:space-between;
  background-color:#fff
}

#app .phoneHeader > aside > :nth-child(1) > :nth-child(1){
  cursor:pointer;
  position:relative
}
#app .phoneHeader > aside > :nth-child(1) > :nth-child(1) > :last-child{
  top:50%;
  right:0;
  color:#fff;
  position:absolute;
  font-size:.73em;
  transform:translate(19px,-50%)
}
#app .phoneHeader > aside > :nth-child(1) > :nth-child(1) > :last-child > :only-child{ position:relative }
#app .phoneHeader > aside > :nth-child(1) > :nth-child(1) > :last-child::before{
  width:25px;
  height:25px;
  border-radius:50%;
  background-color:#ffb300
}

#app .phoneHeader > aside > :nth-child(1) > :last-child{
  width:20px;
  height:20px;
  cursor:pointer;
  border:2px #777 solid;
  transform:rotateZ(45deg);
  position:relative;
  border-radius:50%;
  background-color:#fff
}
#app .phoneHeader > aside > :nth-child(1) > :last-child::before,#app .phoneHeader > aside > :nth-child(1) > :last-child::after{
  background-color:#777
}
#app .phoneHeader > aside > :nth-child(1) > :last-child::before{
  width:75%;
  height:2px;
}
#app .phoneHeader > aside > :nth-child(1) > :last-child::after{
  width:2px;
  height:75%
}

#app .phoneHeader > aside > :nth-child(2){
  width:100%;
  height:100%;
  overflow:hidden
}

#app .phoneHeader > aside > :nth-child(2) a > .icon{
  width:20px;
  color:#888;
  position:relative;
  margin-right:15px
}
#app .phoneHeader > aside > :nth-child(2) a > .icon > :only-child{
  top:50%;
  right:50%;
  position:absolute;
  transform:translate(50%,-50%)
}

#app .phoneHeader > aside > :nth-child(2) a{
  display:flex
}

#app .phoneHeader > .anchorRight-enter,#app .phoneHeader > .anchorRight-leave-to{
  transform:translateX(100%)
}

#app .phoneHeader > .anchorRight-enter-active,#app .phoneHeader > .anchorRight-leave-active{
  transition:.5s;
}
</style>

<template>
<div class=phoneHeader style="
 top:0
;width:100%
;position:fixed
">
  <main>
    <div>
      <font-awesome icon=bars @click="categoryShown = !categoryShown" />
      <!-- <svg aria-hidden=true focusable=false role="img" xmlns=http://www.w3.org/2000/svg viewBox="0 0 448 512" width=20>
        <path fill=currentColor d="M442 114H6a6 6 0 0 1-6-6V84a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6z"></path>
      </svg> -->
    </div>
    <form method=GET :action=action>
      <input type=text placeholder="Saya ingin mencari..." name=keyword style="width:83%" />
      <font-awesome icon=search class=cursor style="font-size:.87em" @click=submit />
    </form>
    <div>
      <div class=cursor>
        <!-- <a v-if=!usrName href="/setting" class=profilePict>
          <img :src="photoPath()" :alt=usrName />
        </a> -->
        <div v-if=usrName class=profilePict @click="menuShown = true">
          <img :src="photoPath()" :alt=usrName />
        </div>
        <font-awesome v-else icon=sign-in-alt class=cursor @click=actionLog />
      </div>
    </div>
    <div class=cover v-if=categoryShown @click="categoryShown = false"></div>
    <transition name=transitionCategory>
    <!-- <div class=category v-if=categoryShown>
      <ul><li v-for="e of category" :key=e._ID><a :href="'category/' + e.slug">{{ e.text }}</a></li></ul>
    </div> -->
    <v-scrollable v-if=categoryShown class=category :stuffs=category unique="_ID" #default={stuff:e} height=260px>
      <a :href="'category/' + e.slug">{{ e.text }}</a>
    </v-scrollable>
    </transition>
  </main>
  <transition name=anchorRight>
  <aside v-if=menuShown>
    <div>
      <a href="cart">
        <font-awesome icon=shopping-cart style="color:#888;font-size:1.37em" />
        <div class=cntrLowest><span>{{ totalCart }}</span></div>
      </a>
      <div @click="menuShown = false" class="cntrLowest cntrHigher"></div>
    </div>
    <v-scrollable :stuffs=menu unique=link #default={stuff:e}>
      <a v-if="e.link.startsWith('http')" :href=e.link target=_blank>
        <div class=icon><font-awesome :icon=e.icon /></div>
        <div>{{ e.text }}</div>
      </a>
      <!-- <div v-else-if=e.user @click=redirectToUser>{{ e.text }}</div> -->
      <a v-else :href=e.link>
        <div class=icon><font-awesome :icon=e.icon /></div>
        <div>{{ e.text }}</div>
      </a>
    </v-scrollable>
  </aside>
  </transition>
</div>
</template>

<script>
import { mapGetters } from 'vuex'
import { APP as setApp } from '@/store/actions'
import { USR as getUsr } from '@/store/getters'
import { ACTION, getCategory, getCartCounter } from '@/assets/js/fetchers'
import { PATH } from '@/assets/js/fetchers'
import VScrollable from '@/components/VScrollable'

export default {
   name:'phoneFloatHeader'
  ,data() { return {
     category:[ ]
    ,categoryShown:null
    ,menuShown:null
    ,menu:[
       { link:'category'
        ,icon:'layer-group'
        ,text:'Kategori Produk'
      }
      ,{ link:'ads'
        ,icon:'ad'
        ,text:'Beriklan Sekarang'
      }
      ,{ link:'message'
        ,icon:'envelope'
        ,text:'Pesan Masuk'
      }
      ,{ link:'wishlist'
        ,icon:'heart'
        ,text:'Wishlist Saya'
      }
      ,{ link:'transaction/buy'
        ,icon:'file-invoice'
        ,text:'Pembelian Saya'
      }
      ,{ link:'balance'
        ,icon:'wallet'
        ,text:'Mons Wallet'
      }
      ,{ link:'u/' + this.usrName
        ,icon:'user'
        ,text:'Profil Saya'
      }
      ,{ link:'setting'
        ,icon:'cog'
        ,text:'Pengaturan'
      }
      ,{ link:'/loyalty/request'
        ,icon:'user-check'
        ,text:'Gabung Member Loyalty'
      }
      ,{ link:'/contact'
        ,icon:'headset'
        ,text:'Hubungi Kami'
      }
      ,{ link:'/page'
        ,icon:'question-circle'
        ,text:'Bantuan'
      }
      ,{ link:'http://forum.mymspmall.id'
        ,icon:'blog'
        ,text:'MSP Forum'
      }
      ,{ link:'/logout'
        ,icon:'times'
        ,text:'Keluar'
      }
    ]
    ,usrStat:null
  } }
  ,computed:{
     ...mapGetters({ usrName:getUsr.NAME, profilePicture:getUsr.PROFILE_PICTURE })
    ,action:() => ACTION.search
    ,totalCart() {
      let v = this.usrStat
      return v? (v = v.cart)? v : 0 : 0
    }
  }
  ,methods:{
     submit({ currentTarget:e }) {
      e.parentElement.submit()
    }
    ,photoPath() { return PATH.photo(this.profilePicture) }
    ,actionLog() {
      this.usrName? window.open('/logout', '_self') : this.$router.push({ name:'login' })
    }
    //,redirectToUser() {
    //  window.open('u/' + this.usrName, '_self')
    //}
  }
  ,mounted() {
    this.$store.dispatch(setApp.HEADER.HEIGHT, this.$el.getBoundingClientRect().height)
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
    getCartCounter().then(data => {
      this.usrStat = data
    })
    let getters = this.$store.getters, x = this.menu
    if(getters[ getUsr.MERCHANT ]) {
      x.splice(1, 0, {
         link:'merchant/store/edit'
        ,icon:'cog'
        ,text:'Pengaturan Merchant'
      })
      x.splice(1, 0, {
         link:'transaction/sell'
        ,icon:'file-invoice'
        ,text:'Transaksi Penjualan'
      })
      x.splice(1, 0, {
         link:'product'
        ,icon:'shopping-cart'
        ,text:'Daftar Produk'
      })
      x.splice(1, 0, {
         link:'product/2/add'
        ,icon:'plus'
        ,text:'Jual E-Voucher'
      })
      x.splice(1, 0, {
         link:'product/1/add'
        ,icon:'plus'
        ,text:'Jual Produk'
      })
    } else
      x.splice(1, 0, {
         link:'merchant/join'
        ,icon:'user-cog'
        ,text:'Menjadi Merchant'
      })
    if(getters[ getUsr.IS_ADMIN ]) {
      x.splice(1, 0, {
         link:'dashboard'
        ,icon:'cogs'
        ,text:'Administrator'
      })
    }
  }
  ,components:{ VScrollable }
}
</script>