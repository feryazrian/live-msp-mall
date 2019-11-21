const G = 'GET', P = 'POST'
const PRODUCTION = true
//const TARGET = 'http://newdev.mymspmall.id'
//const TARGET = 'http://127.0.0.1:8000'
const TARGET = PRODUCTION? window.location.origin + '/' : 'http://newdev.mymspmall.id/'

export const PASSED = 'fulfilled'
export const FAILED = 'rejected'

if(!Promise.allSettled) {
  Promise.allSettled = promises => {
    return Promise.all(promises.map(x => Promise.resolve(x)
      .then(
         value => ({ status:PASSED, value })
        ,value => ({ status:FAILED, reason:value })
      )
    ))
  }
}

const DEFAULT_HANDLER = result => result.status === 200? (result.redirected? result : result.json()) : Promise.reject(result.json())
const DEFAULT_HEADERS = {
   'Content-Type':'application/json'
  ,'Accept':'application/json'
}

export const PATH = {
   image:v => TARGET + 'assets/img/v2/' + v
  ,photo:v => TARGET + 'uploads/photos/' + v
  ,option:v => TARGET + 'uploads/options/' + v
  ,season:v => TARGET + 'uploads/seasons/' + v
  ,product:v => TARGET + 'uploads/products/' + v
  ,digital:v => TARGET + 'assets/digital/' + v
  ,category:v => TARGET + 'uploads/categories/' + v
}

export const ACTION = {
   login:(usr, pwd, token) => `<form id=signin class=hidden method=POST action="/login"><input type=text name="_token" value=${ token } /><input type=hidden name=remember value=on /><input type=text name=email value="${ usr }" /><input type=password name=password value="${ pwd }" /></form>`
  ,search:TARGET + '/search'
  ,product:TARGET + '/product'
  ,digital:{
     charge:'digital/pulsa/checkout'
    ,mobileData:'digital/data/checkout'
  }
  ,register:(_ID, email, name, password, passwordConfirmation, token) => `<form id=${ _ID } class=hidden method=POST action='/register'><input type=text name=email value="${ email }" /><input type=text name=name value="${ name }" /><input type=text name=username value="${ email }" /><input type=password name=password value="${ password }" /><input type=password name=password_confirmation value="${ passwordConfirmation }" /><input type=hidden name="_token" value="${ token }" /></form>`
  ,privacyPolicy:'page/kebijakan-privasi'
  ,termCondition:'page/syarat-ketentuan'
}

export const redirectToProduct = v => {
  window.open(TARGET + 'product/' + v, '_self')
}

export const redirectGoogle = () => {
  window.open(TARGET + 'google', '_self')
}

export const redirectFacebook = () => {
  window.open(TARGET + 'facebook', '_self')
}

export const login = (username = '', password = '') => {
  return fetch(TARGET + 'api/v2/authenticate/login', {
     method:P
    ,headers:DEFAULT_HEADERS
    ,body:JSON.stringify({ username, password, client_id:1 })
  })
  .then(DEFAULT_HANDLER)
}

export const register = (email, phone, name, password, birthDate, birthPlace, gender = 1, clientID = 1) => {
  return fetch(TARGET + 'api/v2/authenticate/register', {
     method:P
    ,headers:DEFAULT_HEADERS
    ,body:JSON.stringify({
       email
      ,phone
      ,name
      ,password
      ,birth_date:birthPlace
      ,birth_place:birthDate
      ,gender
      ,client_id:clientID
    })
  })
  .then(DEFAULT_HANDLER)
}

export const vendorAuth = t => {
  return fetch(TARGET + 'api/v2/authenticate/provider?name=' + t, {
     methods:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const bannerList = () => {
  return fetch(TARGET + 'api/v2/banner/list', {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const requestOTP = (v, t = null) => {
  let body = { action:'register', user:v }
  if(t !== null) {
    if(t == 0) {
      body.type = 252
    } else
    if(t == 1)
      body.type = 120
  }
  return fetch(TARGET + 'api/v2/authenticate/otp/request', {
     method:P
    ,headers:DEFAULT_HEADERS
    ,body:JSON.stringify(body)
  })
  .then(DEFAULT_HANDLER)
}

export const verifyOTP = (user, code) => {
  return fetch(TARGET + 'api/v2/authenticate/otp/verify', {
     method:P
    ,headers:DEFAULT_HEADERS
    ,body:JSON.stringify({ action:'register', user, otp:code })
  })
  .then(DEFAULT_HANDLER)
}

export const getFlashSale = (limit = 12, page = 1) => {
  let url = new URL(TARGET + 'api/v2/product/flash-sale')
  Object.entries({ limit, page }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getCategoryHighlight = (limit = 12) => {
  let url = new URL(TARGET + 'api/v2/product/category-highlight')
  Object.entries({ limit }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getGroupByPromo = (limit = 12, page = 1) => {
  let url = new URL(TARGET + 'api/v2/product/group-buy-promo')
  Object.entries({ limit, page }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getRecommendation = (limit = 30) => {
  let url = new URL(TARGET + 'api/v2/product/recommendation')
  Object.entries({ limit }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getCategory = () => {
  return fetch(TARGET + 'api/v2/list/category', {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getSeasonalPromo = (n = 16) => {
  let url = new URL(TARGET + 'api/v2/product/seasonal-promo')
  Object.entries({ limit:n }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getFooterLinks = () => {
  return fetch(TARGET + 'api/v2/list/footer', {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getCredit = v => {
  let url = new URL(TARGET + 'api/v2/digital/pricelist')
  Object.entries({ type:'pulsa', provider:v }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getMobileData = v => {
  let url = new URL(TARGET + 'api/v2/digital/pricelist')
  Object.entries({ type:'data', provider:v }).forEach(([ k, v ]) => { url.searchParams.append(k, v) })
  return fetch(url, {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}

export const getCartCounter = () => {
  return fetch(TARGET + 'json/stats', {
     method:G
    ,headers:DEFAULT_HEADERS
  })
  .then(DEFAULT_HANDLER)
}
//export const getCookie = function(name) {
//  return (name = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"))) && name.length > 1? decodeURIComponent(name[1]) : undefined
//}