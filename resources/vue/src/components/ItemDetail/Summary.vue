<style scoped>
.itemDetailSummary{
}

.itemDetailSummary > *{
  display:inline-block;
  vertical-align:top
}

.itemDetailSummary > :nth-child(1){
  /*border:2px solid blue;*/
  width:25%
}

.itemDetailSummary > main{
  width:75%;
  padding-left:13px;
  font-family:'Open Sans',sans-serif;
}

.itemDetailSummary > main > header{
  margin-bottom:15px;
  padding-bottom:15px;
  border-bottom:1px solid #e0e0e0;
}

.itemDetailSummary > main > header > h2{
  margin:0;
  font-size:1.1em;
  font-weight:600;
  margin-bottom:15px;
  text-shadow:1px 1px 1px rgba(0,0,0,.1)
}

.itemDetailSummary > main > header > :nth-child(2){
  width:25%;
  cursor:pointer;
  display:inline-block;
  vertical-align:middle
}

.itemDetailSummary > main > header > :nth-child(3){
  margin-left:13px;
  font-size:.8em;
  display:inline-block;
  vertical-align:middle;
}

article > main > .discount{
  padding:0 2px;
  display:inline-block;
  position:relative
}
article > main > .discount::before{
  top:50%;
  right:0;
  width:100%;
  height:1px;
  content:'';
  position:absolute;
  background-color:rgba(67,67,67,.37)
}

article > main > .price{
  color:#ba1239;
  margin:0 0 15px;
  font-size:1.5em;
  font-weight:600
}

article > main > button{
  padding:5px 10px;
  vertical-align:middle
}

article > main > button > header{
  display:inline-block;
  margin-right:15px;
  vertical-align:middle
}

article > main > .stock{
  margin-left:20px;
  display:inline-block;
  vertical-align:middle
}

article > main > .calculator{
  margin-top:25px;
}
/*
article{
  display:flex;
}
article > main{
  margin:0 7px
}


article > main .order{
  cursor:pointer;
  display:flex;
  padding:7px;
  align-items:center;
  border-radius:31px;
  justify-content:center;
  background-color:#FFD54F;
}

article > main .order > button{
  padding:5px 17px;
}

*/
</style>

<template>
<article class=itemDetailSummary style="position:relative">
  <gallery :album=gallery :title=title :size=49.75 />
  <!-- <div></div> -->
  <main>
    <header>
      <h2>{{ title }}</h2>
      <rating :value=rating />
      <div>{{ review }}&#160;Penilaian</div>
    </header>
    <div class=discount>Rp&#160;{{ discountFormatted }}</div>
    <div class=price>Rp&#160;{{ priceFormatted }}</div>
    <button class=app__basic_theme>
      <header>Beli Sekarang</header>
      <input-digit />
    </button>
    <div class=stock>Tersedia: 23</div>
    <postal-fee-calculator class=calculator />
  </main>
</article>
</template>

<script>
import Gallery from '@/components/Gallery'
import InputDigit from '@/components/InputDigit'
import Rating from '@/components/Rating'
import PostalFeeCalculator from '@/components/PostalFeeCalculator'

export default {
   name:'itemDetailSummary'
  ,data:() => ({
    //
  })
  ,props:{
     title:String
    ,price:[ Number, String ]
    ,rating:[ Number, String ]
    ,review:[ Number, String ]
    ,discount:[ Number, String ]
    ,stock:{ type:Number }
    ,gallery:{ type:Array }
  }
  ,mounted() {
  }
  ,computed:{
     priceFormatted() {
      let v = this.price
      return v? v.toLocaleString('id-ID') : ''
    }
    ,discountFormatted() {
      let v = this.discount
      return v? v.toLocaleString('id-ID') : ''
    }
  }
  ,components:{ Gallery, InputDigit, Rating, PostalFeeCalculator }
}
</script>