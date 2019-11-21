<style>
#app .postalFeeCalculator{
  border:1px solid rgba(67,67,67,.25);
  position:relative;
  border-radius:3px;
  background-color:#fff
}

#app .postalFeeCalculator > *{
  padding-left:25px;
  padding-right:25px
}

#app .postalFeeCalculator > header{
  padding-top:10px;
  padding-bottom:10px;
  background-color:#e6e6e6;
  border-top-left-radius:inherit;
  border-top-right-radius:inherit
}

#app .postalFeeCalculator h4{
  display:inline;
  font-weight:400
}

#app .postalFeeCalculator > aside{
  width:100%;
  right:50%;
  border:1px solid rgba(67,67,67,.25);
  bottom:0;
  z-index:1;
  position:absolute;
  transform:translate(50%,110%);
  transition:box-shadow .75s ease-out,border .25s ease-out .75s;
  padding-left:0;
  padding-right:0;
  border-radius:5px;
  background-color:inherit
}
#app .postalFeeCalculator > aside.hidden{
  border:0;
  box-shadow:none
}

#app .postalFeeCalculator > aside > :nth-child(1){
  right:50%;
  bottom:0;
  cursor:pointer;
  padding:19px;
  position:absolute;
  transform:translate(50%,50%);
  transition:.15s ease-out;
  border-radius:50%;
  background-color:#fff
}
#app .postalFeeCalculator > aside > :nth-child(1):hover{
  background-color:#e6e6e6
}

#app .postalFeeCalculator > aside > :nth-child(1) > :only-child{
  font-size:1.25em;
  margin-top:2px;
  transition:inherit
}
#app .postalFeeCalculator > aside.hidden > :nth-child(1) > :only-child{
  transform:translate(50%,-50%) rotateZ(180deg);
  margin-top:-1px
}

#app .postalFeeCalculator > aside > :last-child{
  display:flex;
  overflow:hidden;
  transition:inherit;
  transition:.75s cubic-bezier(.87,-0.55,.27,1.55);
  flex-direction:column-reverse
}

#app .postalFeeCalculator > aside table{
  width:100%;
  margin-top:15px;
  margin-bottom:15px;
  border-collapse:collapse
}

#app .postalFeeCalculator > aside table > tr:nth-child(even){
  background-color:#f5f5f5
}

#app .postalFeeCalculator > aside table td{
  padding:7px 10px;
  font-size:.87em
}

#app .postalFeeCalculator > aside table td:nth-child(1){
  padding-left:25px
}

#app .postalFeeCalculator > aside table td:nth-child(2){
  text-align:right;
  border-left:1px dotted rgba(67,67,67,.5);
  border-right:1px dotted rgba(67,67,67,.5)
}

#app .postalFeeCalculator > aside table td:nth-child(3){
  font-size:1em;
  padding-right:25px
}
</style>

<template>
<div class=postalFeeCalculator ref=postalFeeCalculator>
  <header>
    <h4>Biaya Pengiriman</h4>
  </header>
  <main>
    <queryable-input label="Provinsi" />
  </main>
  <aside class=pop :class={hidden}>
    <div class=pop @click="hidden = !hidden">
      <font-awesome icon=chevron-down class=cntr />
    </div>
    <div :style="{ height:[ hidden? 0 : calcPanelHeight() + 'px' ] }">
      <table>
        <tr v-for="(e, i) of couriers" :key=i>
          <td>{{ e.name }}</td>
          <td>{{ e.duration }}&#160;hari</td>
          <td><sup>Rp</sup>&#160;<span style="font-weight:600">{{ e.fee.toLocaleString('id-ID') }}</span></td>
        </tr>
      </table>
    </div>
  </aside>
</div>
</template>

<script>
import QueryableInput from '@/components/QueryableInput'

export default {
   name:'postalFeeCalculator'
  ,data:() => ({
     hidden:true
    ,couriers:[
       { fee:370000, duration:'10+', name:'JNE REG' }
      ,{ fee:460000, duration:'2-4', name:'POS Paket Kilat Khusus' }
      ,{ fee:690000, duration:'6', name:'POS REG' }
    ]
  })
  ,methods:{
     calcPanelHeight() {
      let table, style = getComputedStyle(table = this.$refs.postalFeeCalculator.querySelector('aside > :last-child > table'))
      return table.getBoundingClientRect().height + [ 'marginTop', 'marginBottom', 'borderTopWidth', 'borderBottomWidth' ].reduce((n, i) => n + parseFloat(style[i]), 0)
    }
    ,show() {
      //let hidden = this.hidden
      //if(!hidden)
      //  this.calcPanelHeight()
      //this.hidden = !hidden
    }
  }
  ,mounted() {
  }
  ,components:{ QueryableInput }
}
</script>