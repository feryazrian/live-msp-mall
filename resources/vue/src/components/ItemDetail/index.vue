<style scoped>
.itemDetail > :nth-child(1){
  /*border:3px solid green;*/
  font-family:'Open Sans',sans-serif;
}

.itemDetail > :nth-child(1) > *{
  display:inline-block;
  vertical-align:top
}
.itemDetail > :nth-child(1) > main{
  margin-right:1%;
  width:79%
}
.itemDetail > :nth-child(1) > aside{
  width:20%;
  border:1px solid rgba(67,67,67,.19);
  padding:12px 3px;
  border-radius:5px
}

.itemDetail > :nth-child(1) > aside > .vendor{
  outline:1px solid red;
}

.itemDetail > :nth-child(1) > aside > .vendor > *{
  display:inline-block;
  vertical-align:middle
}

.itemDetail > :nth-child(1) > aside > .vendor > :nth-child(1){
  width:35%;
  height:80px;
  overflow:hidden;
  position:relative
}

.itemDetail > :nth-child(1) > aside > .vendor > :nth-child(1) > img{
  top:50%;
  right:50%;
  width:75px;
  height:75px;
  position:absolute;
  transform:translate(50%,-50%);
  object-fit:cover;
  border-radius:50%
}

.itemDetail > :nth-child(1) > aside > .vendor > :nth-child(2){
  width:65%;
  padding-left:7px
}

.itemDetail aside > .vendor > :nth-child(2) > h3{
  display:inline;
  font-size:1em;
  font-weight:600;
}

.itemDetail aside > .vendor > :nth-child(2) > .location{
  margin-top:5px
}

.itemDetail aside > .vendor > :nth-child(2) > .location > *{
  display:inline-block;
  vertical-align:middle
}

.itemDetail aside > .vendor > :nth-child(2) > .location > :nth-child(1){
  font-size:1.1em;
  margin-right:7px
}

.itemDetail aside > .vendor > :nth-child(2) > .location > :nth-child(2){
  font-size:.75em
}

.mainScroller .header{
  padding:10px 15px;
}
</style>

<template>
<div class=itemDetail style="position:relative">
  <page :style="{ marginTop:`${ headerHeight }px` }">
    <main>
      <item-detail-summary :title=value.name :rating=value.rating :review=value.review :price=value.price :discount=value.discount :gallery=gallery :stock=23 :test-rating=value.rating />

      <!-- <h-scrollable :stuffs=titles :margin=10>
        <template v-slot:default={stuff}>
        </template>
      </h-scrollable>
      <button @click=addArray(titles)>Tambah</button>
      <button @click=subArray(titles)>Kurang</button>

      <h-scrollable style="margin-top:50px" :stuffs=titles2 :show-arrows=false :show-shadow=false>
      </h-scrollable>
      <button @click=addArray(titles2)>Tambah</button>
      <button @click=subArray(titles2)>Kurang</button> -->

      <!-- <simple-tab :titles=titles>
        <template #0>
          <button @click="titles.push('Title N')">Tambah</button>
          <button @click="titles.pop()">Hapus</button>
        </template>
        <template #1>
          <h1>Ulasan</h1>
        </template>
      </simple-tab>
      <simple-tab :titles=titles2>
        <template #0>
          <button @click="titles2.push('Title X')">Tambah</button>
          <button @click="titles2.pop()">Hapus</button>
        </template>
        <template #1>
          <h1>Test</h1>
        </template>
      </simple-tab> -->

      <!-- <tab /> -->
      <!-- <h-scrollable class=mainScroller>
        <template>
          <div class=header>Deskripsi Produk</div>
          <div class=header>Ulasan</div>
          <div class=header>Tanya & Jawab</div>
          <div class=header>Spesifikasi</div>
          <div class=header>Tanya & Jawab</div>
          <div class=header>Tanya & Jawab</div>
          <div class=header>Spesifikasi</div>
          <div class=header>Spesifikasi</div>
          <div class=header>Tanya & Jawab</div>
          <div class=header>Tanya & Jawab</div>
          <div class=header>Spesifikasi</div>
          <div class=header>Spesifikasi</div>
        </template>
      </h-scrollable> -->
      <!-- <simple-tab :menu=menu></simple-tab> -->
    </main>
    <aside>
      <div class="vendor">
        <photo :image="'null'" :title=value.name />
        <div>
          <h3>{{ vendorName }}</h3>
          <div class=location>
            <font-awesome icon="map-marker-alt" />
            <span>{{ vendorLocation }}</span>
          </div>
        </div>
      </div>
    </aside>
  </page>
  <float-header />
  <simple-footer />
</div>
</template>

<script>
import { PATH } from '@/assets/js/fetchers'
import { mapState } from 'vuex'
import FloatHeader from '@/components/FloatHeader'
import SimpleFooter from '@/components/SimpleFooter'
import Summary from '@/components/ItemDetail/Summary'
import SimpleTab from '@/components/SimpleTab'
//import SimpleTab from './SimpleTab'
import Tab from '@/components/Tab'
import HScrollable from '@/components/HScrollable'
import Photo from '@/components/Photo'

export default {
   name:'desktopItemDetail'
  ,data:() => ({
    value:{ }
    
    ,menu:[
       { title:'Detail Barang' }
      ,{ title:'Ulasan' }
      //,{ title:'Komentar' }
      //,{ title:'Voucher Game' }
      //,{ title:'Pulsa' }
      //,{ title:'Paket Data' }
      //,{ title:'Listrik PLN' }
      //,{ title:'Tanya-Jawab' }
    ]
    ,test:{
       title:'MSI VGA RTX 2080Ti Gaming X Trio sdsdshgfh ghf hhghhfhfh gfhfhshg ghshghgfh ghfhsfhfh ddgdgfg dgdfg dgdgdgdggg'
      ,gallery:[
         'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_01.jpg'
        ,'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_02.jpg'
        ,'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_03.jpg'
        ,'img/sample/ASUS-Custom-ROG-STRIX-GeForce-GTX-1080-Ti-and-GTX-1080-Ti-Turbo-Graphics-Cards.jpg'
        ,'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_04.jpg'
        ,'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_05.jpg'
        ,'img/sample/ROG-STRIX-RTX2080TI-O11G-GAMING_06.jpg'
      ]
    }
    ,N:0
    ,titles:[
       'Detail Produk'
      ,'Ulasan'
      ,'Tanya - Jawab'
      //,'Title #1'
      //,'Title #2'
      //,'Title #3'
      //,'Title #4'
      //,'Title #5'
      //,'Title #6'
      //,'Title #7'
      //,'Title #8'
      //,'Title #9'
      //,'Title #10'
      //,'Title #11'
      //,'Title #12'
      //,'Title #13'
    ]
    ,titles2:[ 'Tambah & Kurang' ]
  })
  ,methods:{
     getRandom:(n) => Math.floor(Math.random() * (n + 1))
    ,addArray(arr) { return arr.splice(this.getRandom(arr.length), 0, 'Header to-' + (this.N = this.N + 1)) }
    ,subArray(arr) { return arr.splice(this.getRandom(arr.length - 1), 1) }
  }
  ,computed:{
     ...mapState({
      headerHeight:state => state.layout.headerHeight
    })
    ,photo() {
      return PATH.product(this.value.photo)
    }
    ,vendorName() {
      let v = this.value
      return v && (v = v.seller)? v.name : ''
    }
    ,vendorLocation() {
      let v = this.value
      return v && (v = v.seller) && (v = v.merchant)? v.address : ''
    }
    ,gallery() {
      let v = this.value
      return v && (v = v.photo)? [ v ] : [ ]
    }
  }
  ,mounted() {
    this.value = this.$route.params.args
    console.log(this.value)
  }
  ,components:{ FloatHeader, SimpleFooter, itemDetailSummary:Summary, SimpleTab, Tab, HScrollable, Photo }
}
</script>