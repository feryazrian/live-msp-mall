const V_BORDER = [ 'borderTopWidth', 'borderBottomWidth' ]
const H_BORDER = [ 'borderLeftWidth', 'borderRightWidth' ]
const V_MARGIN = [ 'marginTop', 'marginBottom' ]
const H_MARGIN = [ 'marginRight', 'marginLeft' ]

const V_OUTER = V_MARGIN.concat(V_BORDER)
const V_INNER = [ 'paddingTop', 'paddingBottom', 'borderTopWidth', 'borderBottomWidth' ]
const H_OUTER = H_MARGIN.concat(H_BORDER)
const H_INNER = [ 'paddingRight', 'paddingLeft', 'borderLeftWidth', 'borderRightWidth' ]
export const GAP_POS = [ 'static', 'relative' ]

export const calcWidth = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().width + H_OUTER.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const clientWidth = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().width - H_INNER.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const calcHeight = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().height + V_OUTER.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const clientHeight = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().height - V_INNER.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const totalWidth = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().width + H_MARGIN.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const totalHeight = e => {
  let style = getComputedStyle(e)
  return e.getBoundingClientRect().height + V_MARGIN.reduce((n, k) => n + parseFloat(style[k]), 0)
}

export const calcDescentWidth = e => {
  let style = getComputedStyle(e)
  return Array.from(e.children).reduce((n, c) => n + (!GAP_POS.find(e => e === getComputedStyle(c).position)? 0 : totalWidth(c)), 0) + H_OUTER.reduce((n, k) => n + parseFloat(style[k]), 0)
}

try { document.querySelector(':scope *') }
catch(e) {
  const SCOPE = /:scope(?![\w-])/gi;
  (function(ElementPrototype) {
    let querySelectorWithScope = polyfill(ElementPrototype.querySelector)
    ElementPrototype.querySelector = function querySelector(x) {
      return querySelectorWithScope.apply(this, arguments)
    }
    let querySelectorAllWithScope = polyfill(ElementPrototype.querySelectorAll)
    ElementPrototype.querySelectorAll = function querySelectorAll(x) {
      return querySelectorAllWithScope.apply(this, arguments)
    }
    if(ElementPrototype.matches) {
      let matchesWithScope = polyfill(ElementPrototype.matches)
      ElementPrototype.matches = function matches(x) {
        return matchesWithScope.apply(this, arguments)
      }
    }
    if (ElementPrototype.closest) {
      let closestWithScope = polyfill(ElementPrototype.closest)
      ElementPrototype.closest = function closest(x) {
        return closestWithScope.apply(this, arguments)
      }
    }
    function polyfill(v) {
      return function(x) {
        let hasScope
        if((hasScope = x && SCOPE.test(x))) {
          let attr = 'data-' + Math.floor(Math.random() * 9000000) + 1000000
          arguments[0] = x.replace(SCOPE, '[' + attr + ']')
          this.setAttribute(attr, '')
          let e = v.apply(this, arguments)
          this.removeAttribute(attr)
          return e
        } else
          return (v.apply(this, arguments))
      }
    }
  })(Element.prototype)
}

export const cachingDecorator = f => {
  let cache = new Map()
  return function() {
    let k = [].join.call(arguments)
    if(cache.has(k))
      return cache.get(k)
    let v = f.apply(this, arguments)
    console.log(v)
    cache.set(k, v)
    return v
  }
}

export const zeroFill = N => {
  if(N <= 1) return i => i
  let zeroes = n => {
    let x = ''
    for(; --n > -1; x += '0');
    return x
  }, n = N
  return function append(i) {
    let k = n
    if(i >= Math.pow(10, --k))
      return i
    if(i >= Math.pow(10, k - 1))
      return zeroes(N - k) + i
    if(i === 0)
      return zeroes(k) + i
    n -= 1
    return append(i)
  }
}

const TRANSITION = {
   'transition':'transitionend'
  ,'OTransition':'oTransitionEnd'
  ,'MozTransition':'transitionend'
  ,'WebkitTransition':'webkitTransitionEnd'
}

export const transitionName = () => {
  for(let k in TRANSITION)
    if(document.body.style[k] !== undefined) return TRANSITION[k]
}

const MSISDN_PREFIXES = [ '8', '08', '628' ]

export const arrowKeys = n => n >= 37 && 40 >= n
export const digitKeys = n => (n >= 48 && 57 >= n) || (n >= 96 && 105 >= n)

export const msisdn = (v, n = 8) => {
  let check = (v, x) => v.startsWith(x) && v.length > x.length + n && !isNaN(v)
  if(v.charAt(0) === '+')
    return !check(v, '+628')? null : v.substring(MSISDN_PREFIXES[MSISDN_PREFIXES.length - 1].length)
  for(let e of MSISDN_PREFIXES)
    if(check(v, e))
      return v.substring(e.length - 1)
  return null
}

export const regexEmail = v => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v)

export const CELL_PREFIX = {
   '817':{ operator:'xl' }
  ,'818':{ operator:'xl' }
  ,'819':{ operator:'xl' }
  ,'859':{ operator:'xl' }
  ,'877':{ operator:'xl' }
  ,'878':{ operator:'xl' }
  ,'879':{ operator:'xl' }
  ,'831':{ operator:'axis' }
  ,'832':{ operator:'axis' }
  ,'833':{ operator:'axis' }
  ,'837':{ operator:'axis' }
  ,'838':{ operator:'axis' }
  ,'896':{ operator:'three' }
  ,'897':{ operator:'three' }
  ,'898':{ operator:'three' }
  ,'899':{ operator:'three' }
  ,'895':{ operator:'three' }
  ,'814':{ operator:'indosat' }
  ,'815':{ operator:'indosat' }
  ,'816':{ operator:'indosat' }
  ,'855':{ operator:'indosat' }
  ,'856':{ operator:'indosat' }
  ,'857':{ operator:'indosat' }
  ,'858':{ operator:'indosat' }
  ,'811':{ operator:'telkomsel' }
  ,'812':{ operator:'telkomsel' }
  ,'813':{ operator:'telkomsel' }
  ,'821':{ operator:'telkomsel' }
  ,'822':{ operator:'telkomsel' }
  ,'823':{ operator:'telkomsel' }
  ,'852':{ operator:'telkomsel' }
  ,'853':{ operator:'telkomsel' }
  ,'851':{ operator:'telkomsel' }
  ,'881':{ operator:'smartfren' }
  ,'882':{ operator:'smartfren' }
  ,'883':{ operator:'smartfren' }
  ,'884':{ operator:'smartfren' }
  ,'885':{ operator:'smartfren' }
  ,'886':{ operator:'smartfren' }
  ,'887':{ operator:'smartfren' }
  ,'888':{ operator:'smartfren' }
  ,'889':{ operator:'smartfren' }
}

/*
export const CELL_PREFIX = {
   xl:[ '817', '818', '819', '859', '877', '878', '879' ]
  ,axis:[ '831', '832', '833', '837', '838' ]
  ,three:[ '896', '897', '898', '899',  '895' ]
  ,indosat:[ '814', '815', '816', '855', '856', '857', '858' ]
  ,telkomsel:[ '811', '812', '813', '821', '822', '823', '852', '853', '851' ]
  ,smartfren:[ '881', '882', '883', '884', '885', '886', '887', '888', '889' ]
}
*/