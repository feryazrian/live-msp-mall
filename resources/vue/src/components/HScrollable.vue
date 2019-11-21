<style>
#app .hScrollable{
  /*border:1px dotted red;*/
  /*padding:10px 25px;*/
  /*background-color:#fafafa*/
}
/*
.hScrollable::before{
  right:0;
  width:100%;
  bottom:1px;
  height:1px;
  content:'';
  position:absolute;
  background-color:gainsboro
}
*/
#app .hScrollable > :nth-child(1){
  /*border:1px solid red;*/
  padding:0 1px;
  overflow:hidden;
  position:relative;
  transition:inherit
}

#app .hScrollable > :nth-child(1)::after{
  top:0;
  right:0;
  width:100%;
  height:100%;
  content:'';
  display:none;
  position:absolute;
  background:linear-gradient(to right,#fff,rgba(255,255,255,.43),#fff)
}
#app .hScrollable > :nth-child(1).hidden::after{
  display:block
}

#app .hScrollable .swiper{
  /*border:1px solid blue;*/

  overflow:hidden;
  position:relative
  /*transition:inherit;*/
  /*padding-bottom:1px*/
}

#app .hScrollable .swiper > .slider{
  /*border:1px solid red;*/
  display:inline-flex
}

#app .hScrollable .swiper > .slider > .o-slider-enter,
#app .hScrollable .swiper > .slider > .o-slider-leave-to{
  opacity:0
}

#app .hScrollable .swiper > .slider > .o-slider-enter{
  transform:translate(0,43px)
}

#app .hScrollable .swiper > .slider > .o-slider-leave-to{
  transform:translate(0,0)
}

#app .hScrollable .swiper > .slider > .o-slider-leave-to:nth-child(1){
  transform:translate(-43px,0)
}
/* .hScrollable .swiper > .slider > .o-slider-enter-to{
  transition:1s ease-out
}*/
#app .hScrollable .swiper > .slider > .o-slider-enter-active,
#app .hScrollable .swiper > .slider > .o-slider-leave-active{
  z-index:1
}

#app .hScrollable .swiper > .slider > .o-slider-enter-active{
  transition-delay:.25s;
  transition-timing-function:cubic-bezier(.68,2.34,.43,.53)
}
#app .hScrollable .swiper > .slider > .o-slider-leave-active{
  position:absolute;
  transition-timing-function:cubic-bezier(.57,-0.52,.33,.31)
}
#app .hScrollable .swiper > .slider > .o-slider-leave-active:nth-child(1){
  transition-timing-function:cubic-bezier(.87,-1.51,.33,.31)
}

/*
.hScrollable .swiper > .slider.hidden{ display:inline-flex }
.hScrollable .swiper > .slider.hidden > *{ flex-shrink:0 }
*/
#app .hScrollable .swiper > .slider > :not(hr){
  /*border:1px dotted blue;*/
  /*background-color:#fafafa;*/
  cursor:pointer;
  /*padding:13px;*/
  transition:1s;
  flex-shrink:0
  /*display:inline-block;
  vertical-align:middle*/
}
/*
.hScrollable .swiper > .slider > :nth-child(1){
  margin-left:0!important
}
.hScrollable .swiper > .slider > :last-of-type:not(hr){
  margin-right:0!important
}
*/

#app .hScrollable > :nth-child(1) > .shadow{
  top:0;
  right:50%;
  width:100%;
  height:100%;
  position:absolute;
  transform:translateX(50%);
  transition:inherit;
  pointer-events:none
}

#app .hScrollable > :nth-child(1) > .shadow::before,
#app .hScrollable > :nth-child(1) > .shadow::after{
  top:0;
  width:7%;
  height:100%;
  opacity:0;
  content:'';
  position:absolute;
  transition:inherit
}

#app .hScrollable > :nth-child(1) > .shadow::before{
  left:0;
  transform:translateX(-100%);
  background:linear-gradient(to right,#fff,transparent)
}

#app .hScrollable > :nth-child(1) > .shadow::after{
  right:0;
  transform:translateX(100%);
  background:linear-gradient(to right,transparent,#fff)
}

#app .hScrollable > :nth-child(1) > .shadow.L::before,
#app .hScrollable > :nth-child(1) > .shadow.R::after{
  opacity:1;
  transform:translateX(0)
}

#app .hScrollable .swiper > .slider > hr{
  margin:0;
  border:0;
  height:3px;
  bottom:0;
  /*bottom:-1px;*/
  padding:0;
  position:absolute;
  /*transition:.5s cubic-bezier(.23,1,.32,1);*/
  border-radius:3px;
  background-color:#ffb300
}

#app .hScrollable > :nth-child(1) > button{
  top:50%;
  border:0;
  opacity:0;
  padding:15px;
  position:absolute;
  visibility:hidden;
  border-radius:50%;
  background-color:#fff;
  transition-duration:.15s;
  transition-timing-function:linear
}
#app .hScrollable > :nth-child(1).scroll > button.active:hover{
  opacity:1
}
#app .hScrollable > :nth-child(1).scroll > button.active{
  opacity:.43;
  visibility:visible
}

#app .hScrollable > :nth-child(1) > button:nth-of-type(1){
  left:0;
  transform:translate(-100%,-50%) rotateZ(180deg)
}

#app .hScrollable > :nth-child(1).scroll > button.active:nth-of-type(1){
  transform:translate(25%,-50%) rotateZ(180deg)
}

#app .hScrollable > :nth-child(1) > button:nth-of-type(1)::before{
  transform:rotateZ(180deg)
}

#app .hScrollable > :nth-child(1) > button:nth-of-type(2){
  right:0;
  transform:translate(100%,-50%)
}

#app .hScrollable > :nth-child(1).scroll > button.active:nth-of-type(2){
  transform:translate(-25%,-50%)
}

#app .hScrollable > :nth-child(1) > button > :only-child{
  margin-right:-1px
}
</style>

<template>
<div class=hScrollable
style="position:relative;transition:.25s ease-out">
  <div @mouseleave=mouseLeave>
    <div class=swiper>
      <transition-group tag=div class="slider" name=o-slider @enter=onStuffEnter @leave=onStuffLeave>
        <div v-for="(e, i) of stuffs" :key=e[unique] :style="{ width:width + 'px', marginRight:margin + 'px', marginLeft:margin + 'px', padding }" @press=select($event,e,i) :data-index=i>
          <slot :motion=motion :stuff=e :index=i>{{ e[header] }}</slot>
        </div>
        <hr v-if=cursor key=N />
      </transition-group>
    </div>
    <div v-if=shadow class=shadow></div>
    <button class="popLowest arrow" v-if=arrows @click=prev>
      <font-awesome class=cntr icon=chevron-right />
    </button>
    <button class="popLowest arrow" v-if=arrows @click=next>
      <font-awesome class=cntr icon=chevron-right />
    </button>
  </div>
</div>
</template>

<script>
//import { TweenLite, Bounce, Elastic } from 'gsap'
import { calcDescentWidth, calcHeight, transitionName } from '@/assets/js/calcs'

export default {
   name:'hScrollable'
  ,data:() => ({
     spaceX:null
    //,length:null
    ,swiper:null
    ,scroll:null
    ,motion:null
    ,recent:-1
    ,edited:0
  })
  ,props:{
     width:[ Number, String ]
    ,stuffs:Array
    ,header:{ type:String, default:'header' }
    ,unique:{ type:String, required:true }
    ,margin:{ type:Number, default:0 }
    ,cursor:{ type:Boolean, default:true }
    ,arrows:{ type:Boolean, default:true }
    ,shadow:{ type:Boolean, default:true }
    ,padding:[ String ]
    ,initial:[ String, Number ]
    //,selectedClass:String
  }
  ,methods:{
     async onStuffEnter(e, finish) {
      let x, v
      if(this.stuffs.length === 1) {
        v = e.parentElement.style
        v.display = ''
        v['height'] = ''
      }
      e.addEventListener(x = transitionName(), ((that, mode) => async function callback() {
        e.removeEventListener(x, callback)
        await that.lastAction(mode)
        finish()
      })(this, (v = this.motion)? true : null))
      let style = e.parentElement.style
      //style.width = parseFloat(style.width) + (this.stuffs.length > 1? await this.stuffWidth(e) : 0) + 'px'
      style.width = parseFloat(style.width) + this.stuffWidth(e) + 'px'
      if(v) {
        this.edited = this.edited + 1
      } else {
        //await Promise.resolve((v = this.swiper).refresh())
        await new Promise(finish => {
          setTimeout(() => finish(this.swiper.refresh()), 0)
        })
        this.toggleArrows()
      }
    }
    ,async onStuffLeave(e, finish) {
      let x, v
      //if(e.dataset.index > 0) e.style.left = (e.previousElementSibling.getBoundingClientRect().right + parseFloat(getComputedStyle(e).marginLeft) - ((x = e.parentElement).getBoundingClientRect().left + parseFloat(getComputedStyle(x).borderLeftWidth))) + 'px'
      if(this.stuffs.length === 0) {
        x = getComputedStyle(v = e.parentElement)
        v.style.display = 'flex'
        v.style['height'] = parseFloat(x.borderTopWidth) + parseFloat(x.borderBottomWidth) + calcHeight(e) + 'px'
      }
      e.addEventListener(x = transitionName(), ((that, mode) => async function callback() {
        e.removeEventListener(x, callback)
        let style = e.parentElement.style
        //- (that.stuffs.length > 0 && e.dataset.index < 1? parseFloat(getComputedStyle(e.nextElementSibling).marginLeft) : 0)
        style.width = parseFloat(style.width) - that.stuffWidth(e) + 'px'
        if(that.motion) {
          if(1 > that.edited) {
            that.edited = null
            that.swiper.wrapper.parentElement.classList.add('hidden')
          }
        } else {
          await new Promise(finish => {
            setTimeout(() => finish(that.swiper.refresh()), 0)
          })
          that.toggleArrows()
        }
        await that.lastAction(mode)
        if(that.stuffs.length === 0) style.height = 0
        finish()
      })(this, (v = this.motion)? true : null))
      if(v)
        this.edited = this.edited + 1
    }
    ,stuffWidth(e, S = 'matrix') {
      let i = getComputedStyle(e), j, n = e.getBoundingClientRect().width / parseFloat((j = i.transform).substring(n = j.indexOf(S) + S.length + 1, j.indexOf(',', n)))
      //if(0 >= (j = e.dataset.index)) {
      //  return n + parseFloat(i.marginRight) + parseFloat(getComputedStyle(e.nextElementSibling).marginLeft)
      //} else
      //if(j >= this.stuffs.length - 1) {
      //  return n + parseFloat(i.marginLeft) + parseFloat(getComputedStyle(e.previousElementSibling).marginRight)
      //} else
      return n + parseFloat(i.marginRight) + parseFloat(i.marginLeft)
    }
    ,async lastAction(i) {
      if(true === i && (this.edited = this.edited - 1) < 1) {
        i = this.swiper.wrapper.parentElement
        if(!this.motion) {
          await new Promise(finish => {
            setTimeout(() => finish(this.swiper.refresh()), 1000)
          })
          this.toggleArrows(i)
          i.classList.remove('hidden')
        } else {
          this.edited = null
          i.classList.add('hidden')
        }
      }
    }
    ,async onStuffsDelete() {
      //TweenLite.to(e, 7, { onCompleteScope:this, onCompleteParams:[ this.motion? 1 : 0 ], async onComplete(i) {
      //  if(1 === i && (this.edited = this.edited - 1) < 1) {
      //    i = this.slider.wrapper.parentElement
      //    if(this.motion) {
      //      this.edited = null
      //      i.classList.add('hidden')
      //      console.log('Waiting for refreshing....')
      //    }
      //    else
      //      new Promise(finish => {
      //        setTimeout(() => finish(this.slider.refresh()), 1000)
      //      }).then(() => {
      //        this.toggleArrows(i)
      //        i.classList.remove('hidden')
      //        console.log('Updated! ' + this.edited)
      //      })
      //  }
      //  let style = e.parentElement.style
      //  style.width = await new Promise(finish => {
      //    let i = getComputedStyle(e), R = parseFloat(i.marginRight), L = parseFloat(i.marginLeft)
      //    finish(parseFloat(style.width) - e.getBoundingClientRect().width - ((i = e.dataset.index) == 0? 2 * R : i == this.stuffs.length? 2 * L : R + L))
      //  }) + 'px'
      //  finish()
      //}, opacity:0, transform:'translateY(30px)', ease:Back.easeIn })
    }
    ,async onStuffsUpdate() {
      //let style = e.parentElement.style
      //style.width = await new Promise(finish => {
      //  let i = getComputedStyle(e), R = parseFloat(i.marginRight), L = parseFloat(i.marginLeft)
      //  finish(parseFloat(style.width) + e.getBoundingClientRect().width + ((i = e.dataset.index) == 0? 2 * R : i == this.stuffs.length - 1? 2 * L : R + L))
      //}) + 'px'
      //TweenLite.to(e, 7, { onCompleteScope:this, onCompleteParams:[ this.motion? 1 : 0 ], async onComplete(i) {
      //  if(1 === i && (this.edited = this.edited - 1) < 1) {
      //    i = this.slider.wrapper.parentElement
      //    if(this.motion) {
      //      this.edited = null
      //      i.classList.add('hidden')
      //      console.log('Waiting for refreshing....')
      //    }
      //    else
      //      new Promise(finish => {
      //        setTimeout(() => finish(this.slider.refresh()), 1000)
      //      }).then(() => {
      //        this.toggleArrows(i)
      //        i.classList.remove('hidden')
      //        console.log('Updated! ' + this.edited)
      //      })
      //      //await Promise.resolve(this.slider.refresh())
      //      //this.toggleArrows(e)
      //      //e.classList.remove('hidden')
      //      //console.log('Updated! ' + this.edited)
      //  }
      //  finish()
      //}, opacity:1, transform:'translateY(0)', ease:Elastic.easeOut.config(1,.2) })
      ////this.spaceX = this.slider.wrapper.getBoundingClientRect().width / 2.
      //if(!this.motion) {
      //  await Promise.resolve(this.slider.refresh())
      //  this.toggleArrows(this.slider.wrapper.parentElement)
      //} else
      //  this.edited = this.edited + 1

      //e = await new Promise(finish => {
      //  let i = getComputedStyle(e), R = parseFloat(i.marginRight), L = parseFloat(i.marginLeft)
      //  finish(this.length + e.getBoundingClientRect().width + ((i = e.dataset.index) == 0? 2 * R : i == this.stuffs.length - 1? 2 * L : R + L))
      //}).then(n => {
      //  this.length = n
      //  return e.parentElement
      //}).then(e => {
      //  this.slider.refresh()
      //  this.spaceX = (e = e.parentElement).getBoundingClientRect().width / 2.
      //  return e.parentElement
      //})
      //this.toggleArrows(e)
    }
    ,select({ currentTarget:e }, v, i) {
      if(!this.cursor || this.recent == i) return;
      if(i < 0) {
        //e.lastElementChild.style.width = 0
        this.recent = -1
      } else {
        let j
        if((j = this.recent) < 0) {
        } else {
          this.$emit('unselected', { target:e.parentElement.children[j], value:this.stuffs[j], index:j })
          // let x
          // if((x = this.selectedClass)) {
          //   let i
          //   if((i = this.recent) > -1)
          //     e.parentElement.children[i].classList.remove(x)
          //   e.classList.add(x)
          // }
        }
        //let sum = e => (e = e.previousElementSibling)? totalWidth(e) + sum(e) : 0
        //TweenLite.to(e.parentElement.lastElementChild, .5, {
        //   ease:i > 0 && i < this.stuffs.length - 1? Elastic.easeOut : Bounce.easeOut
        //  ,left:sum(e) + parseFloat(getComputedStyle(e).marginLeft)
        //  ,width:e.getBoundingClientRect().width
        //})

        //let x = e.lastElementChild
        //let n = (e = e.children).length - 1
        //if(i > n) return;
        ////if(this.recent < 0) {
        ////  x.style.left = parseFloat(getComputedStyle(e[i]).marginLeft) + 'px'
        ////  x.style.width = e[i].getBoundingClientRect().width + 'px'
        ////} else {
        //  let k = 0
        //  for(let j = -1; ++j < i; k += totalWidth(e[j]));
        //  TweenLite.to(x, .75, {
        //     ease:i > 0 && i < n - 1? Elastic.easeOut : Bounce.easeOut
        //    ,left:k + parseFloat(getComputedStyle(e[i]).marginLeft)
        //    ,width:e[i].getBoundingClientRect().width
        //  })
        ////}
        this.$emit('select', { target:e, value:v, index:i })
        this.recent = i
      }
      //console.log('cursor: ' + this.recent)

      //if(!this.cursor || this.recent == i) return;
      //if(!e /*|| i < 0*/) {
      //  //(e = this.$el.querySelector('.swiper .slider > hr').style).width = e.left = 0
      //  //this.recent = -1
      //}
      //else {
      //  let c = e.children
      //  if(this.recent < 0) {
      //    e = e.querySelector('hr').style
      //    e.left = parseFloat(getComputedStyle(c[i]).marginLeft) + 'px'
      //    e.width = c[0].getBoundingClientRect().width + 'px'
      //  } else {
      //    let n = 0
      //    for(let j = -1; ++j < i; n += totalWidth(c[j]));
      //    TweenLite.to(e.querySelector('hr'), .75, {
      //       ease:i > 0 && i < (c.length - 1) -1? Elastic.easeOut : Bounce.easeOut
      //      ,left:n + parseFloat(getComputedStyle(c[i]).marginLeft)
      //      ,width:c[i].getBoundingClientRect().width
      //    })
      //  }
      //  this.recent = i
      //}
    }
    ,async update(e = this.swiper.scroller) {
      this.spaceX = this.swiper.wrapper.getBoundingClientRect().width / 2.
      await Promise.resolve(e.style.width = calcDescentWidth(e) + 'px')
      await new Promise(finish => {
        setTimeout(() => finish(this.swiper.refresh()), 0)
      })
      this.toggleArrows()
      //if(!e)
      //  e = await Promise.resolve(this.$el.querySelector('.swiper > .slider'))
      //e.style.width = (this.length = calcDescentWidth(e)) + 'px'
      //this.length = calcDescentWidth(e)
      //this.spaceX = (e = e.parentElement).getBoundingClientRect().width / 2.
      //this.slider.refresh()
      //this.toggleArrows(e.parentElement)
    }
    ,mouseEnter({ currentTarget:e }) {
      if(this.scroll = this.arrows && this.swiper.maxScrollX < 0)
        e.classList.add('scroll')
    }
    ,mouseLeave({ currentTarget:e }) {
      if(this.scroll)
        e.classList.remove('scroll')
    }
    ,async next({ currentTarget:e }) {
      let x = this.swiper.maxScrollX
      let N = this.spaceX, n = this.swiper.x - N
      //this.swiper.scrollBy(x < n? -N : Math.round(-N - Math.ceil(n - x)), 0, 500)
      if(this.check())
        await new Promise(finish => {
          setTimeout(() => finish(this.swiper.refresh()), 0)
        })
      this.swiper.scrollBy(x < n? -N : -N - (n - x), 0, 500)
      this.toggleArrows(e.parentElement)
    }
    ,async prev({ currentTarget:e }) {
      let N = this.spaceX, n = this.swiper.x + N
      if(this.check())
        await new Promise(finish => {
          setTimeout(() => finish(this.swiper.refresh()), 0)
        })
      this.swiper.scrollBy(n < 0? N : N - n, 0, 500)
      this.toggleArrows(e.parentElement)
    }
    ,toggleArrows(e = this.$el.firstElementChild) {
      let R, L
      if(this.shadow) {//Math.round(clientWidth(e) - this.length)
        let x = this.swiper.x, c = e.querySelector('.shadow').classList
        c[R = x > this.swiper.maxScrollX? 'add' : 'remove']('R')
        c[L = x > -1? 'remove' : 'add']('L')
      }
      if(this.arrows) {
        e.querySelector('button:nth-of-type(1).arrow').classList[L]('active')
        e.querySelector('button:nth-of-type(2).arrow').classList[R]('active')
      }
    }
    ,check() {
      return this.swiper.maxScrollX < 0 && Math.abs(this.swiper.scrollerWidth - Math.round(parseFloat(this.swiper.scroller.style.width))) > 1
    }
  }
  //,watch:{
  //   '$store.state.agent.ready'() {
  ,mounted() {
    let e = this.$el.querySelector(':scope>:first-child>.swiper')
    this.swiper = new IScroll(e, { tap:'press', scrollX:true, scrollY:false, bounceTime:250, probeType:2 });
    (e = e.parentElement).addEventListener('mousemove', (mouseEnter => function callback(e) {
      const E = e.currentTarget
      E.removeEventListener('mousemove', callback)
      E.addEventListener('mouseenter', mouseEnter)
      mouseEnter(e)
    })(this.mouseEnter))
    this.swiper.on('scrollStart', () => {
      this.swiper.on('scroll', (that => async function scroll() {
        that.swiper.off('scroll', scroll)
        that.toggleArrows(e)
        if(that.check()) {
          that.edited = null
          that.swiper.wrapper.parentElement.classList.add('hidden')
        }
      })(this))
      this.motion = !this.motion
    })
    this.swiper.on('scrollEnd', async() => {
      let e = this.swiper.wrapper.parentElement
      if(this.edited == null) {
        await new Promise(finish => {
          setTimeout(() => finish(this.swiper.refresh()), 1000)
        })
        e.classList.remove('hidden')
        this.toggleArrows(e)
        this.edited = 0
      } else
      if(this.edited > 0) {
        e.classList.add('hidden')
      } else
        this.toggleArrows(e)
      this.motion = false
    })
    this.update()
    let i
    try {
      i = (i = this.initial) === ''? 0 : parseInt(i)
    } catch(e) { i = null }
    if(i > -1) {
      this.select({ currentTarget:this.swiper.scroller.children[i] }, this.stuffs[i], i)
    }
    //this.select(slider, 0)
    //slider.classList.remove('hidden')
  }
  //}
  ,beforeDestroy() {
    let v
    if((v = this.swiper)) v.destroy()
  }
}
</script>