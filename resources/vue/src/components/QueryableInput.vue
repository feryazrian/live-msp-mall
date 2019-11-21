<template>
<div class=queryableInput>
  <float-input :label=label />
</div>
</template>

<script>
import FloatInput from '@/components/FloatInput'

export default {
   name:'queryableInput'
  ,data:() => ({
  })
  ,props:{
    label:String
  }
  ,components:{ FloatInput }
}
</script>