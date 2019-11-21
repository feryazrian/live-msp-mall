<style>
#app figure.photo{
  /*width:100%*/
}

#app figure.photo > .R,#app figure.photo > .L,#app figure.photo > .M{
  top:50%;
  position:absolute
}
#app figure.photo > .R{
  right:0;
  transform:translateY(-50%)
}
#app figure.photo > .L{
  left:0;
  transform:translateY(-50%)
}
#app figure.photo > .M{
  right:50%;
  transform:translate(50%, -50%)
}

#app figure.photo > img{
  width:100%;
  height:100%;
  opacity:1;
  transform:scale(1);
  transition:1.5s opacity, .5s transform;
  object-fit:cover
}
#app figure.photo > img.shown{
  opacity:0;
  transform:scale(1.75)
}
</style>

<template>
<figure class=photo style="
 margin:0
;overflow:hidden
;position:relative
">
  <div :class="[ align > 0? 'R' : (align < 0? 'L' : 'M') ]" :data-test=align>
    <progress-circular v-if=shown :size=size :line=line />
  </div>
  <transition name=zoomin>
  <img :src=image :alt=title @load=onReady :class={shown} />
  </transition>
</figure>
</template>

<script>
import ProgressCircular from '@/components/ProgressCircular'

export default {
   name:'photo'
  ,data:() => ({ shown:1 })
  ,methods:{ onReady() {
      this.shown = undefined
      this.$emit('ready')
    }
  }
  ,props:{
     size:{ type:[ Number, String ], default:50 }
    ,line:{ type:[ Number, String ], default:3 }
    ,align:{ type:[ Number, String ], default:0 }
    ,image:String
    ,title:String
  }
  ,components:{ ProgressCircular }
  ,mounted() {
    this.$watch('image', v => {
      if(!v)
        this.shown = 1
    })
  }
}
</script>