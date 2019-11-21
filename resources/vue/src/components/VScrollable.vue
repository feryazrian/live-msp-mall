<style>
.vScrollable{
  border-radius:3px;
  background-color:#fff
}
.vScrollable > :nth-child(1){
  height:100%;
  overflow:hidden
}
.vScrollable > :nth-child(1) > ul{
  padding:7px 0
}

.vScrollable > :nth-child(1) > ul > li{
  margin:0 17px;
  cursor:pointer;
  padding:11px 0
}

.vScrollable > :nth-child(1) > ul > li:not(:last-child){
  border-bottom:1px solid rgba(67,67,67,.17);
}

.vScrollable > :nth-child(1) > ul > li > :only-child{
  cursor:pointer;
}
</style>

<template>
<div class=vScrollable>
  <div :style={height}>
    <ul>
      <li v-for="(e, i) of stuffs" :key=e[unique] @press=select($event,e,i)>
        <slot :stuff=e :index=i>{{ e[header] }}</slot>
      </li>
    </ul>
  </div>
</div>
</template>

<script>
export default {
   name:'vScrollable'
  ,data:() => ({
     swiper:null
    ,recent:-1
  })
  ,props:{
     stuffs:Array
    ,unique:{ type:String, required:true }
    ,header:{ type:String, default:'header' }
    ,height:String
  }
  ,watch:{
     async stuffs() {
      await new Promise(finish => {
        setTimeout(() => finish(this.swiper.refresh()), 5)
      })
    }
  }
  ,methods:{
     select({ currentTarget:e }, v, i) {
      if(this.recent == i) return;
      if(i < 0) {
        this.recent = -1
      } else {
        let j
        if((j = this.recent) < 0) {
        } else {
          this.$emit('unselected', { target:e.parentElement.children[j], value:this.stuffs[j], index:j })
        }
        this.$emit('select', { target:e, value:v, index:i })
        this.recent = i
      }
    }
  }
  ,mounted() {
    let e = this.$el.querySelector(':scope>:nth-child(1)')
    this.swiper = new IScroll(e, { tap:'press', scrollX:false, mouseWheel:true, bounceTime:250 })
  }
  ,beforeDestroy() {
    let v
    if((v = this.swiper)) v.destroy()
  }
}
</script>