<style>
#app .phoneSimpleFooter{
  padding:25px 15px
}
#app .phoneSimpleFooter > :nth-child(1){
  font-size:.73em;
  text-align:center
}
#app .phoneSimpleFooter .dot{
  color:#333;
  font-size:1em
}
</style>

<template>
<div class=phoneSimpleFooter style="background-color:rgb(230,230,230)">
  <div>Copyright&#160;2019&#160;<span class=dot>·</span>&#160;MSPMall</div>
</div>
</template>

<script>
export default {
  name:'phoneSimpleFooter'
}
</script>