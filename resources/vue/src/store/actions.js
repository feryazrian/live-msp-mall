export const USR = { ALL:'dispatchUsr_setAll' }
export const APP = {
   STATE:{ TOKEN:'dispatchApp_setStateToken' }
  ,CHANGE:'dispatchApp_setStatus'
  ,FREEZE:'dispatchApp_setFrozenStatus'
  ,ROUTER:{ FREEZE:'dispatchRouter_setFrozenStatus' }
  ,HEADER:{ HEIGHT:'dispatchLayout_setHeight' }
  ,MESSAGE:{
     INFO:'dispatchMessage_setInfo'
    ,ERROR:'dispatchMessage_setError'
    ,WARNING:'dispatchMessage_setWarning'
  }
}
export const CLIENT = {
  RESOLUTION:'dispatchClient_setResolution'
}