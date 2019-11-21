<template>
<figure class=photo
style=width:100%;position:relative;overflow:hidden;margin:0>
  <img class=cntr :src=src :alt=alt @load=calc />
</figure>
</template>

<script>
export default {
   name:'photo'
  ,props:{
     src:{ type:String, required:true }
    ,alt:{ type:String, required:true }
    ,stylus:Object
  }
  ,computed:{
     height() {
      let v = this.stylus
      return v && (v = v.height)? v : null
    }
  }
  ,methods:{
     calc({ currentTarget:e }) {
      //let { naturalWidth:w = 0, naturalHeight:h = 0 } = e
      let x, { width:w, height:h } = (x = e.parentElement).getBoundingClientRect()
      const H = this.height
      if(H) h = H
      x.style.height = h + 'px'
      e.setAttribute(w > h? 'height' : 'width', '100%')
    }
    ,refresh() {
      this.calc(this.$el.querySelector('img'))
    }
  }
}
</script>