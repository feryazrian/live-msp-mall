//export const SET_DIMEN = 'mutation_setDimen'
//export const SET_AGENT_MODEL = 'setAgentModel'
//export const SET_DOC_READINESS = 'mutation_setDocReadiness'
export const APP = {
   STATE:{ TOKEN:'mutateApp_setStateToken' }
  ,CHANGE:'mutateApp_setStatus'
  ,FREEZE:'mutateApp_setFrozenStatus'
  ,ROUTER:{ FREEZE:'mutateRouter_setFrozenStatus' }
  ,HEADER:{ HEIGHT:'mutateLayout_setHeight' }
  ,MESSAGE:{
     INFO:'mutateMessage_setInfo'
    ,ERROR:'mutateMessage_setError'
    ,WARNING:'mutateMessage_setWarning'
  }
}
//export const APP_SET_FROZEN = 'mutation_setFrozenApp'
//export const APP_SET_BUFFER = 'mutation_setBufferApp'
//export const APP_FREEZE_ROUTER = 'mutation_setFrozenRouter'
export const USR = { ALL:'mutateUsr_setAll' }
//export const SET_USR = 'mutation_setUr'
export const SET_HEADER_HEIGHT = 'mutation_setHeaderHeight'
export const CLIENT = {
   TYPE:'mutateClient_setType'
  ,RESOLUTION:'mutateClient_setResolution'
}