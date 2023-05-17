import React, { useMemo, useLayoutEffect } from 'react'
import useDerivedState from '../../useDerivedState'

export default function AmountGbpEditForm(
  {amount_gbp, changedValue, setChangedValue})
{
  const [pounds, setPounds] = useDerivedState(
    [changedValue, setChangedValue], ['pounds'],
    {defaultValue: amount_gbp?.pounds ?? 0})

  const [pence, setPence] = useDerivedState(
    [changedValue, setChangedValue], ['pence'],
    {defaultValue: amount_gbp?.pence ?? 0})

  const poundsString = useMemo(
    () => String(pounds), [pounds])
  const penceString = useMemo(
    () => (pence < 10 ? "0" : "")+String(pence), [pence])

  useLayoutEffect(() => {
    setChangedValue({pounds, pence})
  }, [])

  return (
    <div>
      £<input autoFocus
              type="text"
              value={poundsString}
              className="text-center me-1"
              style={{width:(poundsString.length)+'ch',
                      minWidth: '2ch'}}
              onChange={(e)=>{
                const trimmedPounds =
                      e.target.value.split('')
                       .filter(c=>/[0-9]/.test(c))
                       .join('')
                setPounds(Math.floor(trimmedPounds))
              }}/>
      .<input type="text"
              value={penceString}
              className="text-center ms-1"
              style={{width:(penceString.length)+'ch',
                      minWidth: '2ch'}}
              onChange={(e)=>{
                const trimmedPence =
                      e.target.value.replace(/^0+/,'')
                       .split('')
                       .filter(c=>/[0-9]/.test(c))
                       .join('')
                setPence(Math.floor(
                  trimmedPence.length > 2
                    ? trimmedPence.slice(0,2)
                    : trimmedPence))}}/>
    </div>
  )
}
