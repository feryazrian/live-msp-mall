<style>
.scrollableSlide{
  /*border:1px dotted blue;*/
  background-color:lightgray;
  cursor:pointer
}
</style>

<template>
<div class=scrollableSlide @click=action>
  <slot v-bind:slotdata="slotdata"></slot>
</div>
</template>

<script>
export default {
   name:'scrollableSlide'
  ,data:() => ({
  })
  ,methods:{
    action(e) {
      this.$emit('select', e)
    }
  }
}
</script>