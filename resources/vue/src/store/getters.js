export const XS = reset({ n:0, name:'xs', maxWidth:487 })
export const SM = reset({ n:1, name:'sm', maxWidth:763 })
export const MD = reset({ n:2, name:'md', maxWidth:925 })
export const LG = reset({ n:3, name:'lg', maxWidth:1229 })
export const XL = reset({ n:4, name:'xl', maxWidth:1887 })

function reset(o) {
  for(let k in o) {
    let v = o[k]
    delete (o[k])
    Object.defineProperty(o, k, { configurable:false, get() { return v } })
  }
  return o
}

export const BREAKPOINT = 'getBreakpoint'
export const USR = {
   NAME:'getUsr_Name'
  ,USERNAME:'getUsr_Username'
  ,PROFILE_PICTURE:'getUsr_ProfilePicture'
  ,MERCHANT:'getUsr_Merchant'
  ,IS_ADMIN:'getUsr_RoleID'
}
export const APP = {
   STATE:{ TOKEN:'getApp_StateToken' }
  ,STATUS:'getApp_Status'
  ,FROZEN:'isAppFrozen'
  ,ROUTER:{ FROZEN:'isRouterFrozen' }
  ,HEADER:{ HEIGHT:'getLayout_Height' }
  ,MESSAGE:{
     INFO:'getMessage_Info'
    ,ERROR:'getMessage_Error'
    ,WARNING:'getMessage_Warning'
  }
}
//export const CLIENT = {
//  SCREEN_STATUS:'getClientScreenStatus'
//}