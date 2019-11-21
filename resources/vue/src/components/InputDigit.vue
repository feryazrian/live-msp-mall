<style scoped>
.inputDigit{
  /* border:1px solid rgba(0,0,0,.25); */
  border:1px solid #fff;
  display:inline-block;
  padding:3px;
  position:relative;
  font-size:0;
  border-radius:25px
}

.inputDigit > *{
  font-size:21px;
  vertical-align:middle;
}

input{
  width:75px;
  margin:0 7px;
  padding:5px 0;
  text-align:center;
  background-color:transparent
  /* border-bottom:1px solid #ddd */
}

button{
  /* border:3px solid #ddd; */
  border:3px solid rgba(255,255,255,.5);
  cursor:pointer;
  padding:17px;
  position:relative;
  font-family:'Roboto Mono',monospace;
  border-radius:50%;
  background-color:#eee
}

button:nth-child(1)::after{
  content:'-'
}

button:last-child::after{
  content:'+'
}
</style>

<template>
<div class=inputDigit>
  <button class=cntrHigher @click=subs></button>
  <input type=text v-model.number=digit @input=check />
  <button class=cntrHigher @click=plus></button>
</div>
</template>

<script>
export default {
   name:'inputDigit'
  ,data:() => ({
    digit:0
  })
  ,methods:{
     plus() {
      this.digit += 1
    }
    ,subs() {
      if(this.digit > 1) this.digit -= 1
    }
    ,check(e) {
      console.log(e)
    }
  }
}
</script>