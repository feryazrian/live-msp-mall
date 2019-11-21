<style scoped>
.gallery{
  /* border:3px solid red; */
  position:relative;
}

.gallery > main{
  /* border:1px solid blue; */
  width:100%;
  cursor:pointer
}

.gallery > main > img{
  width:100%;
  height:100%;
  object-fit:cover
}

.gallery > .slider{
  display:flex;
  overflow:hidden;
  position:relative;
  margin-top:13px
}

.gallery > .slider > *{
  cursor:pointer;
  position:relative
}

 .gallery > .slider > * > img
,.gallery > .slider > ::after{
  border-radius:3px
}

.gallery > .slider > * > img{
  width:75px;/*Must be equal to default value in order calculating the full width of this component*/
  height:75px;
  display:block;
  position:relative;
  object-fit:cover
}

.gallery > .slider > ::after{
  /* border:1px solid rgba(0,0,0,.25);
  padding:3px; */
  opacity:0;
  content:'';
  transition:.15s ease-out;
  background-color:rgba(255,255,255,.75)
}

.gallery > .slider > .active::after{
  opacity:1
}

.gallery > .slider > :not(:last-child){
  margin-right:13px/*Must be equal to default value in order calculating the full width of this component*/
}

.gallery > .float{
  top:0;
  left:0;
  width:100%;
  height:100%;
  z-index:1;
  position:fixed
}

.gallery > .float > img{
  
}
</style>

<template>
<div class="gallery" :style="{ width:`${ width }px` }">
  <main :style="{ height:`${ width }px` }" @click=enter>
    <img :src=album[i] :alt="`${ title } - ${ i + 1 }`" />
  </main>
  <div ref="slider" class="slider">
    <div class="fullHigher" v-for="(e, i) of album" :key=i @click="check($event, i)">
      <img :src=e :alt="`${ title } - ${ i + 1 }`" :style="{ width:`${ size }px`, height:`${ size }px` }" />
    </div>
  </div>
  <div v-if=focus :class="[ 'float', focus? 'focus' : '' ]" @click=leave>
    <img class="center" :src=prev.firstElementChild.src :alt=prev.firstElementChild.src />
  </div>
</div>
</template>

<script>
export default {
   name:'gallery'
  ,data:() => ({
      i:0
     ,prev:null
     ,focus:null
     ,width:null
  })
  ,props:{
     size:{ type:Number }
    ,album:{ type:Array }
    ,title:{ type:String }
  }
  ,methods:{
     check(e, i) {
      this.prev.classList.remove('active');
     (this.prev = e.currentTarget).classList.add('active')
      this.i = i
    }
    ,enter() {
      this.focus = true
      document.documentElement.classList.add('blur')
    }
    ,leave() {
      this.focus = null
      document.documentElement.classList.remove('blur')
    }
    ,extractName:path => {
      let i, j
      return(j = path.lastIndexOf('.')) > 0
        && ((i = path.lastIndexOf('/', j - 1)) > 0 || (i = path.lastIndexOf('\\', j - 1)) > 0)? path.substring(i + 1, j) : path
    }
    ,setWidth(v) {
      let n = (n = v) && v > 0? n : 75
      this.width = (n * 4) + (13 * 3)
    }
  }
  ,computed:{
  }
  ,watch:{
    size(v) {/*Must be equal to default value in order calculating the full width of this component*/
      this.setWidth(v)
    }
  }
  ,mounted() {
    this.setWidth(this.size);
   (this.prev = this.$refs.slider.firstElementChild).classList.add('active')
  }
}
</script>