export function amountIsNegative(amount) {
  return {
    gbp: (({amount_gbp: {pounds, pence}})=>(
      pounds < 0 || pence < 0)
    )(amount)
  }[amount.currency]
}

export function negative(amount) {
  return {
    gbp: (({amount_gbp: {pounds, pence}})=>( {
      ...amount,
      amount_gbp: {pounds:-pounds, pence: -pence}
    } ))(amount)
  }[amount.currency]
}

export function isZero(amount) {
  return {
    gbp: (({amount_gbp: {pounds, pence}})=>(
      pounds === 0 && pence === 0
    ))(amount)
  }[amount.currency]
}
