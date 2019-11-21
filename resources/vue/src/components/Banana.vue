<style>
#app .banana{
  height:39px;
  margin:0 auto;
  display:flex;
  align-items:center;
  border-radius:21px;
  background-color:#fff
}
#app .banana:not(.phone){
  width:100%
}

#app .banana > input,#app .banana > button{
  height:100%;
  border:0
}

#app .banana > input{
  flex-grow:1;
  min-width:0%;
  /*border-right:0;*/
  padding-left:13px;
  padding-right:7px;
  background-color:#fff;
  border-top-left-radius:inherit;
  border-bottom-left-radius:inherit
}
#app .banana > input::after{}

#app .banana > button{
  flex:0 0 39px;
  padding:0;
  position:relative;
  transition:.75s cubic-bezier(.63,-.39,.43,1.39);
  background-color:transparent;
  border-top-right-radius:inherit;
  border-bottom-right-radius:inherit
}
#app .banana:not(.phone) > button{
  border:0
}

 #app .banana:not(.phone) > button
,#app .banana:not(.phone) > button.shrink{ flex-basis:113px }

#app .banana > button.shrink{ flex-basis:35px }

#app .banana > button::before{
  top:0;
  right:0;
  width:0;
  height:100%;
  opacity:0;
  content:'';
  position:absolute;
  transition:inherit;
  border-radius:inherit;
}
#app .banana:not(.phone) > button::before{
  opacity:1;
  width:100%
}

#app .banana > button.shrink::before{ border-radius:0 17px 17px 0 }

#app .banana > button > :only-child{
  transition:inherit
}
#app .banana:not(.phone) > button > :only-child{ transform:rotateZ(-360deg) }

#app .banana > button > :only-child::before{
  padding:17px;
  transition:inherit;
  border-radius:50%
}
#app .banana:not(.phone) > button > :only-child::before{
  opacity:0;
  transform:translate(50%,-50%) scale(3)
}

#app .banana > button.shrink > :only-child::before{
  padding:15px
}

#app .banana > button > :only-child > :only-child{
  position:relative;
  font-size:.9em;
  transition:inherit
}
#app .banana:not(.phone) > button > :only-child > :only-child{
  font-size:1.25em
}
</style>

<template>
<div>
  <form method=GET class=banana ref=banana :style={border} :action=action>
    <input type=text name=keyword :placeholder=label />
    <button :class="[ cssClass, { shrink:!!color } ]">
      <div class=cntrLowest>
        <slot></slot>
      </div>
    </button>
  </form>
</div>
</template>

<script>
import { ACTION } from '@/assets/js/fetchers'

export default {
   name:'banana'
  ,props:{
     color:String
    ,label:{ type:String, default:'' }
    ,cssClass:String
  }
  ,computed:{
     border() { return !this.color? '' : `2px solid ${ this.color }` }
    ,action() { return ACTION.search }
  }
}
</script>