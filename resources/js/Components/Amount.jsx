import React from 'react'

export default function Amount(props) {
  if (!props.currency) return "Amount not set"
  return {
    gbp: (amount) => {
      const amount_gbp = amount.amount_gbp
      const pounds =
            (amount_gbp.pence < 0 && amount_gbp.pounds === 0)
            ? '-0' : amount_gbp.pounds
      const pence = Math.abs(amount_gbp.pence)
      return `£${pounds}.${('00'+pence).slice(-2)}`
    }
  }[props.currency](props)
}
