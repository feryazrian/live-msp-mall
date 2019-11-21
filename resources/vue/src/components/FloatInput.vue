<style scoped>
.floatInput{
  margin-top:7px;
  margin-bottom:7px
}
.floatInput.sleep,.floatInput.sleep input{
  cursor:not-allowed
}

.floatInput > :nth-child(1){
  /*border:1px solid green;*/
  position:relative;
  border-bottom:1px solid rgba(67,67,67,.17)
}
.floatInput.sleep > :nth-child(1){
  border-color:rgba(67,67,67,.23);
  border-bottom-style:dotted
}
.floatInput > :nth-child(1)::after{
  width:0;
  right:50%;
  height:3px;
  bottom:0;
  opacity:0;
  content:'';
  border-radius:3px;
  position:absolute;
  transform:translate(50%,50%);
  box-shadow:0 1px 1px 0 rgba(0,0,0,.1);
  background-color:#ffb300
}
.floatInput.focus > :nth-child(1)::after{
  width:100%;
  opacity:1
}

.floatInput input{
  border:0;
  width:100%;
  padding:7px 0
}
.floatInput.focus input{
  caret-color:#ffb300
}

.floatInput label{
  /*border:1px solid cyan;*/
  top:50%;
  left:0;
  color:rgba(0,0,0,.43);
  position:absolute;
  max-width:100%;
  transform:translateY(-50%);
  text-shadow:none;
  pointer-events:none;
  transform-origin:left top
}
.floatInput[disabled] label{
  color:rgba(0,0,0,.23)
}
.floatInput label.focus{
  max-width:133.25%;
  transform:translateY(-1.65em) scale(.75)
}
#app .floatInput.sleep > label{
  color:#d7d7d7
}
.floatInput.focus label{
  color:#ff8f00
}

.floatInput > :nth-child(1)::after{
  transition:.35s ease-out
}
.floatInput label{
  transition:.35s cubic-bezier(.25,.8,.5,1)
}
</style>

<template>
<div :class="[ 'floatInput', { focus, sleep } ]"
style="position:relative;padding-top:.5em;padding-bottom:.5em">
  <div>
    <input class=app__basic_theme :type=type v-model=input :name=name @focus=onFocus @blur="focus = null" @keydown=onPress :disabled=sleep :maxlength=maxlength :autocomplete="autocomplete? 'on' : 'off'" />
  </div>
  <label v-if=label :class="[ 'ellips', { focus:focus || input } ]">{{ label }}</label>
</div>
</template>

<script>
export default {
   name:'floatInput'
  ,data:() => ({
     input:null
    ,focus:null
  })
  ,props:{
     name:String
    ,type:{ type:String, default:'text' }
    ,label:String
    ,value:String
    ,sleep:Boolean
    ,maxlength:[ Number, String ]
    ,autocomplete:{ type:Boolean, default:false }
  }
  ,methods:{
     async onPress(e) {
      let b = true
      let submit = v => { b = v }
      await Promise.resolve(this.$emit('press', { submit, code:e.keyCode, char:e.key, text:this.input, n:this.input.length }))
      if(!b)
        e.preventDefault()
    }
    ,clear() { this.input = '' }
    ,onFocus({ currentTarget:e }) {
      this.focus = true
      this.$emit('focus', { e:this.$el, input:e })
    }
  }
  ,mounted() {
    this.input = ''
    this.$watch('input', (i, j)  => { this.$emit('input', { value:i, n:(!i? 0 : i.length) - (!j? 0 : j.length) }) })
    this.$watch('value', v => { this.input = this.value })
  }
}
</script>