<style scoped>
.itemDetailReview{
  padding:5px 0 25px;
}

.itemDetailReview > :nth-child(1){
  flex:0 0 50px;
  margin-right:15px;
  border-radius:50%
}

.itemDetailReview > :last-child{
  flex-grow:1
}

.itemDetailReview header > :nth-child(1){
  display:flex
}

.itemDetailReview header > :nth-child(1) > :nth-child(1){
  flex:0 0 150px
}

.itemDetailReview header > :nth-child(1) > :nth-child(2){
  flex:0 0 75px;
  font-size:.875em;
  margin-left:10px
}

.itemDetailReview header > :nth-child(1) > :nth-child(3){
  flex-grow:1;
  font-size:.75em;
  text-align:right
}

.itemDetailReview header > :nth-child(2){
  font-size:.87em;
  font-weight:600
}

.itemDetailReview > :last-child > main{
  font-size:.875em;
  margin-top:15px
}
</style>

<template>
<div class=itemDetailReview
style="display:flex;align-items:flex-start">
  <photo src="img/sample/GTX_1080Ti.png" alt=Test />
  <section>
    <header>
      <div>
        <rating :value=rating />
        <div>{{ rating }}</div>
        <div v-html=formattedDate>{{ formattedDate }}</div>
      </div>
      <div>{{ reviewer }}</div>
    </header>
    <main>
      <div>{{ text }}</div>
    </main>
  </section>
</div>
</template>

<script>
import Photo from '@/components/Photo'
import Rating from '@/components/obsolete/Rating'
import { MONTHS_ID } from '@/assets/js/locale'

export default {
   name:'itemDetailReview'
  ,data:() => ({
  })
  ,props:{
     reviewer:String
    ,rating:Number
    ,text:String
    ,date:Date
  }
  ,computed:{
     formattedDate() {
      let date = this.date
      return `${ date.getDate().toString().padStart(2, '0') }&#160;${ MONTHS_ID[date.getMonth()] }&#160;${ date.getFullYear() }&#160;<sup style="margin-left:5px">${ date.getHours().toString().padStart(2, '0') }:${ date.getMinutes().toString().padStart(2, '0') }</sup>`
    }
  }
  ,components:{ Photo, Rating }
}
</script>