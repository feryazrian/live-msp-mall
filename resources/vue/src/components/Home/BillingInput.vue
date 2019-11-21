<style>
#app .billingInput{
  border-radius:3px;
  padding-bottom:7px
}
#app .billingInput.phone{
  margin:5px 0 25px
}

#app .billingInput > :nth-child(1){
  padding:10px 5% 0
}
#app .billingInput.phone > :nth-child(1){
  padding:5px 1.5% 0
}

#app .billingInput > :nth-child(1)::before {
  right:0;
  bottom:1px;
  width:100%;
  content:'';
  position:absolute;
  border-bottom:1px solid #e6e6e6
}

#app .billingInput .swiper > .slider > *{
}
#app .billingInput .swiper > .slider > :not(hr){
  color:rgba(67,67,67,.17);
  transition:color .37s!important;
  font-weight:400
}

#app .billingInput > main{
  display:flex;
  padding:25px 5% 10px;
  align-items:center
}
#app .billingInput.phone > main{
  padding:15px 1.5% 10px;
  flex-direction:column
}
#app .billingInput.phone > main > *{
  width:100%
}

#app .billingInput > main > :nth-child(1),#app .billingInput > main > :nth-child(2){
  flex-basis:25%
}
#app .billingInput > main > :nth-child(1){
  position:relative
}
#app .billingInput > main > :nth-child(1) > :nth-child(2){
  top:45%;
  right:0;
  width:unset;
  height:27.5px;
  overflow:unset!important;
  position:absolute!important;
  transform:translateY(-50%)
}

#app .billingInput > main > :nth-child(2){
  position:relative;
  margin-left:25px;
  margin-right:25px
}

#app .billingInput > main > :nth-child(2) > .progressCircular{
  top:30%;
  right:0;
  position:absolute!important;
  transform:translateY(-50%)
}

#app .billingInput > main > :nth-child(2) > .cover{
  top:0;
  right:0;
  width:100%;
  height:100%;
  z-index:3;
  display:none;
  position:fixed
}
#app .billingInput > main > :nth-child(2).shown > .cover{ display:block }

#app .billingInput > main > :nth-child(2) > aside{
  top:0;
  left:0;
  z-index:3;
  display:none;
  position:absolute;
  border-radius:5px;
  flex-direction:column;
  background-color:#fff
}
#app .billingInput > main > :nth-child(2).shown > aside{
  display:inline-flex;
}
#app .billingInput > main > :nth-child(2) > aside > *{
  display:flex;
  cursor:pointer;
  padding:5px 15px;
  transition:all .15s;
  justify-content:space-between;
}
#app .billingInput > main > :nth-child(2) > aside > :hover{
  background-color:#f5f5f5
}
#app .billingInput > main > :nth-child(2) > aside > * > :nth-child(1){
}
#app .billingInput > main > :nth-child(2) > aside > * > :nth-child(2){
  width:75px;
  font-size:.87em;
  font-weight:500
}
#app .billingInput > main > :nth-child(2) > aside > * > :nth-child(2){}
#app .billingInput > main > :nth-child(2) > aside > * > *{
  display:inline-block;
  vertical-align:middle
}

#app .billingInput > main > :nth-child(3){
  align-self:flex-start
}
#app .billingInput > main > :nth-child(3) > :nth-child(1){
  color:rgba(67,67,67,.5);
  font-size:.83em
}
#app .billingInput > main > :nth-child(3) > :nth-child(2){
  font-size:1.5em;
  font-weight:700
}

#app .billingInput > main > :nth-child(4){
  flex:1 0 350px
}
#app .billingInput.phone > main > :nth-child(4){
  flex-basis:auto
}

#app .billingInput > main > :nth-child(4) > button:only-of-type{
  width:33%;
  float:right;
  border:none;
  padding:13px;
  overflow:hidden;
  position:relative;
  transition:.43s;
  border-radius:21px;
  background-color:#fafafa
}
#app .billingInput.phone > main > :nth-child(4) > button{
  width:75%;
  float:unset;
  margin:0 auto;
  display:block
}

#app .billingInput > main > :nth-child(4) > button:only-of-type::before{
  top:0;
  right:0;
  width:100%;
  height:0;
  opacity:0;
  content:'';
  position:absolute;
  transition:inherit;
  background-color:#ffc107
}
#app .billingInput > main > :nth-child(4) > button:not([disabled]):only-of-type:hover::before{
  opacity:1;
  height:100%
}
#app .billingInput > main > :nth-child(4) > button:only-of-type > :only-of-type{
  position:relative;
  transition:inherit
}
#app .billingInput > main > :nth-child(4) > button:not([disabled]):only-of-type:hover > :only-of-type{
  color:#fff;
  font-weight:500;
  transform:scale(1.25)
}
</style>

<template>
<div class="billingInput elevation-2" :class={phone}>
  <h-scrollable :stuffs=header @select=onSelect @unselected=onUnselected header="title" unique="title" padding="13px 20px" initial />
  <main>
    <div>
      <float-input ref=cellNumber label="Nomor Telepon" maxlength=13 @press=onPress @input=onInput @focus="({ input:e }) => e.setSelectionRange(0, e.value.length)" />
      <photo v-if=image :image=image :title=title :viewport=true size=18 line=2 align=1 />
    </div>
    <div>
      <float-input label="Nominal" :sleep="option.length < 1" @focus=onFocus :value=value />
      <progress-circular v-if=!loaderHidden size=18 line=2 />
      <div class=cover @click="({ currentTarget:e }) => e.parentElement.classList.remove('shown')"></div>
      <aside class="elevation-5">
        <div v-for="(e, i) of option" :key=e.pulsa_code @click=select($event,i)>
          <div>{{ parse(e.pulsa_nominal) }}</div>
          <div><sup>Rp</sup>&#160;{{ e.pulsa_price.toLocaleString('id-ID') }}</div>
        </div>
      </aside>
    </div>
    <div>
      <div>Harga</div>
      <div><sup>Rp</sup>&#160;{{ price }}</div>
    </div>
    <div><button class=themeBasis @mouseenter=onEnter @mouseleave=onLeave @click=submit :disabled="!price"><div>Bayar</div></button></div>
  </main>
</div>
</template>

<script>
import { digitKeys, arrowKeys, msisdn, CELL_PREFIX } from '@/assets/js/calcs'
import { PATH, ACTION, getCredit, getMobileData } from '@/assets/js/fetchers'
import { APP as getApp } from '@/store/getters'
import HScrollable from '@/components/HScrollable'
import FloatInput from '@/components/FloatInput'
import ProgressCircular from '@/components/ProgressCircular'
import Photo from '@/components/Photo'

export default {
   name:'billingInput'
  ,data:() => ({
     index:-1
    ,image:null
    ,title:null
    ,state:null
    ,value:null
    ,price:null
    ,number:null
    ,option:[ ]
    ,header:[
       { title:'Pulsa' }
      ,{ title:'Paket Data' }
      //,{ title:'Listrik PLN' }
      //,{ title:'Voucher Game' }
    ]
    ,selectedTab:null
    ,loaderHidden:true
  })
  ,props:{ phone:Boolean }
  ,methods:{
     submit() {
      let action, n = this.selectedIndex;
      if(n === 0) {
        action = ACTION.digital.charge
      } else
      if(n === 1) {
        action = ACTION.digital.mobileData
      } else
        return;
      Promise.resolve(this.$el.insertAdjacentHTML('beforeend', `<form class=hidden id=chargeExecutor method=GET action=${ action }><input type=text name=_token value=${ this.$store.getters[ getApp.STATE.TOKEN ].data }><input type=text name=hp value=${ this.number } /><input type=text name=pulsa_code value="${ this.option[this.index].pulsa_code }" /></form>`))
        .then(() => {
          this.$refs.cellNumber.clear()
          this.reset()
          this.$el.querySelectorAll(':scope>main input[name]').forEach(e => e.setAttribute('form', 'chargeExecutor'))
          chargeExecutor.submit()
        })
    }
    ,select({ currentTarget:e }, i) {
      let v = this.option[i]
      this.value = this.parse(v.pulsa_nominal)
      this.price = this.option[this.index = i].pulsa_price.toLocaleString('id-ID')
      e.parentElement.parentElement.classList.remove('shown')
    }
    ,onSelect({ target:e }) {
      e.style.color = 'unset'
      //e.style.fontWeight = '500'
      e.style.borderBottom = '3px solid #ffca28'
      e.classList.add('selected')
      this.selectedIndex = parseInt(e.dataset.index)
      this.$refs.cellNumber.clear()
      this.reset()
    }
    ,onUnselected({ target:e }) {
      e.style.color = ''
      //e.style.fontWeight = ''
      e.style.borderBottom = ''
      e.classList.remove('selected')
    }
    ,onPress({ submit, code:i, char }) {
      submit(digitKeys(i) || i === 8 || i === 46 || i === 35 || i === 36 || arrowKeys(i))
    }
    ,reset() {
      this.image = null
      this.title = null
      this.state = null
      this.value = null
      this.price = null
      this.number = null
      this.option.splice(0)
    }
    ,onInput({ value:v }) {
      let i = msisdn(v, 1)
      this.number = '0' + i
      if(!i) {
        if(this.state !== null && this.image)
          this.reset()
      } else
      if((i = i.substring(0, 3)) !== this.state) {
        this.loaderHidden = 0
        this.state = i
        let x = null
        for(let k in CELL_PREFIX)
          if(k === i) {
            x = CELL_PREFIX[k].operator
            break;
          }
        if(x) {
          let fetch, n = this.selectedIndex;
          if(n === 0) {
            fetch = getCredit
          } else
          if(n === 1) {
            fetch = getMobileData
          } else
            return;
          this.image = PATH.digital((this.title = x) + '.png')
          fetch(x).then(data => {
            if(data.code === 200) {
              if(this.image && (data = data.items)) this.option = data
            } else
              console.log(data.message)
          }).finally(() => { this.loaderHidden = true })
        } else {
          this.loaderHidden = true
          this.reset()
        }
      }
    }
    ,onFocus({ e }) {
      e.parentElement.classList.add('shown')
    }
    ,parse(v) {
      return isNaN(v)? v : parseFloat(v).toLocaleString('id-ID')
    }
    ,onEnter({ currentTarget:e }) {
      if(!e.hasAttribute('disabled'))
        e.classList.add('elevation-3')
    }
    ,onLeave({ currentTarget:e }) {
      if(!e.hasAttribute('disabled'))
        e.classList.remove('elevation-3')
    }
  }
  ,components:{ HScrollable, FloatInput, ProgressCircular, Photo }
}
</script>