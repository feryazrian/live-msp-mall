<style scoped>
.starRating{
  width:100%;
  padding-bottom:7px
}

.starRating > :nth-child(1){
  position:relative;
  padding-top:10px;
  padding-bottom:10px
}

.starRating > :nth-child(1)::before{
  width:100%;
  /*height:2px;*/
  border-top:2px dotted rgba(67,67,67,.27);
  /*background-color:rgba(67,67,67,.17)*/
}

.starRating > :only-child::before,.starRating > :only-child > hr::after{
  border-radius:3px
}

.starRating > :nth-child(1) > hr{
  width:0;
  margin:0;
  position:relative;
  transition:.3s ease-out;
  border-style:none
}

.starRating > :nth-child(1) > hr::after{
  top:50%;
  left:0;
  width:100%;
  height:2px;
  content:'';
  position:absolute;
  transform:translateY(-50%);
  /*box-shadow:0 1px 1px 0 rgba(0,0,0,.13);*/
  background-color:#fdd835
}

.starRating > :nth-child(1) > i{
  top:50%;
  padding:4px;
  position:absolute;
  transform:translate(-50%,-50%);
  border-radius:50%;
  background-color:#d9d9d9
}

.starRating > :nth-child(1) > i.blink{
  /*box-shadow:1px 1px 1px 0 rgba(0,0,0,0.13),0 0 2px 0 rgba(0,0,0,.1);*/
  background-color:#ffc107
}

.starRating > :nth-child(1) > i:nth-child(2){ left:20% }
.starRating > :nth-child(1) > i:nth-child(3){ left:40% }
.starRating > :nth-child(1) > i:nth-child(4){ left:60% }
.starRating > :nth-child(1) > i:nth-child(5){ left:80% }

.starRating > :nth-child(1) > :last-child{
  top:50%;
  right:0;
  color:#B0BEC5;
  position:absolute;
  font-size:.75em;
  transform:translate(75%,-50%)
}

.starRating > :nth-child(1) > :last-child.blink{
  color:#f9a825;
  font-size:1.15em
}

.starRating > :last-child{
  right:50%;
  width:45px;
  bottom:0;
  padding:2px 7px;
  color:rgba(67,67,67,.67);
  text-shadow:1px 1px 2px rgba(0,0,0,.27);
  opacity:0;
  position:absolute;
  font-size:1em;
  transform:translate(50%,100%) scale(0);
  text-align:center;
  transition:.3s cubic-bezier(.67,-0.88,.4,1.91);
  box-shadow:0 3px 1px -2px rgba(0,0,0,0.2),0 2px 2px 0 rgba(0,0,0,0.14),0 1px 5px 0 rgba(0,0,0,0.12);
  border-radius:3px;
  background-color:#fdd835
}
.starRating:hover > :last-child.allow{
  opacity:1;
  transform:translate(50%,100%) scale(1)
}

.starRating > :last-child.allow::before{
  top:0;
  right:50%;
  border:5px solid transparent;
  content:'';
  position:absolute;
  transform:translate(50%,-100%);
  border-bottom-color:#fdd835
}
/*
.starRating .ratingShown-enter-active,.starRating .ratingShown-leave-active{
}

.starRating .ratingShown-enter,.starRating .ratingShown-leave-to{
  opacity:0;
  transform:translate(50%,115%) scale(0)
}*/
</style>

<template>
<div class=starRating
style="position:relative;padding-right:15px!important">
  <div class="cntrLowest">
    <hr :style="{ width:`${ value > 5? 100 : value * 20 }%` }" />
    <i :class="{ blink:value >= 1 }"></i>
    <i :class="{ blink:value >= 2 }"></i>
    <i :class="{ blink:value >= 3 }"></i>
    <i :class="{ blink:value >= 4 }"></i>
    <font-awesome icon="star" :class="{ blink:value == 5 }" />
  </div>
  <aside :class="{ allow:value > 0 }">{{ value.toFixed(1) }}</aside>
</div>
</template>

<script>
export default {
   name:'rating'
  ,props:{
    value:{ type:Number }
  }
}
</script>