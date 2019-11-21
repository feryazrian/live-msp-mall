<style scoped>
#app .billingInput{
  margin:0 auto;
  padding-top:10px;
  box-shadow:0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12);
  padding-bottom:15px
}
#app .billingInput:not(.phone) {
  border-radius:5px;
  box-shadow:1px 1px 2px 0 rgba(0,0,0,.13),0 1px 2px 0 rgba(0,0,0,.17)
}

#app .billingInput > *{
  padding-left:5%;
  padding-right:5%
}

#app .billingInput > header{
  border-bottom:1px solid rgba(0,0,0,.05);
}

#app .billingInput > header > *{
  /*border:1px solid blue;*/
  color:rgba(0,0,0,.27);
  padding:10px 3%;
  display:inline-block;
  transition:.15s ease-out;
  text-shadow:none;
  border-bottom:2px solid transparent
}
#app .billingInput > header > .focus,.billingInput > header > .enter{
  color:inherit
}
#app .billingInput > header > .focus{
  text-shadow:inherit;
  border-bottom-color:#ffca28
}

#app .billingInput > header h4{
  cursor:pointer;
  display:inline;
  transition:.15s ease-out;
  font-weight:500
}

#app .billingInput > main{
  /*border:1px solid red;*/
  margin-top:15px
}

#app .billingInput > main > .input{
  font-size:.9em;
  margin-left:2%;
  margin-right:2%
}
#app .billingInput:not(.phone) > main > .input{
  width:29%;
  display:inline-block
}

#app .billingInput > main > button{
  
} 
#app .billingInput:not(.phone) > main > button{
  width:21%;
  float:right
}

#app .billingInput > main > button{
  width:50%;
  border:0;
  margin:0 auto;
  display:block;
  padding:9px;
  overflow:hidden;
  position:relative;
  font-size:1.3em!important;
  transition:.43s;
  border-radius:21px;
  background-color:#fafafa
}
#app .billingInput > main > button::before{
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
#app .billingInput > main > button:hover{
  box-shadow:0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12)
}
#app .billingInput > main > button:hover::before{
  opacity:1;
  height:100%
}
#app .billingInput > main > button > :only-of-type{ position:relative }
</style>

<template>
<div class="billingInput" ref=billingInput style="
 position:relative
;border-radius:5px
;background-color:#fff
">
  <header>
    <div v-for="(e, i) of types" :key=i>
      <h4 @click=focus @mouseenter=enter @mouseleave=leave>{{ e.title }}</h4>
    </div>
  </header>
  <main>
    <float-input class=input label="Nomor" />
    <float-input class=input label="Nominal" disabled />
    <button class=themeBasis><span>Bayar</span></button>
  </main>
</div>
</template>

<script>
import FloatInput from '@/components/FloatInput'

export default {
   name:'billingInput'
  ,data:() => ({
     state:null
    ,types:[
       { title:'Pulsa' }
      ,{ title:'Paket Data' }
      ,{ title:'Listrik PLN' }
      ,{ title:'Voucher Game' }
    ]
  })
  ,methods:{
     focus({ currentTarget:e }) {
      this.state.classList.remove('focus');
     (this.state = e.parentElement).classList.add('focus')
    }
    ,enter({ currentTarget:e }) {
      e.parentElement.classList.add('enter')
    }
    ,leave({ currentTarget:e }) {
      e.parentElement.classList.remove('enter')
    }
  }
  ,mounted() {
    (this.state = this.$refs.billingInput.querySelector('header').firstElementChild).classList.add('focus')
  }
  ,components:{ FloatInput }
}
</script>