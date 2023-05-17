import React, { useLayoutEffect, useMemo } from 'react'

import useDerivedState from '../../useDerivedState'

export default function AmountPercentageEditForm(
  {amount_percentage, changedValue, setChangedValue})
{
  const [percentage, setPercentage] = useDerivedState(
    [changedValue, setChangedValue], ['percentage'],
    {defaultValue: amount_percentage?.percentage ?? 0})

  const percentageString = useMemo(
    // https://stackoverflow.com/a/32229831
    () => String(+parseFloat(percentage).toFixed(2)),
    [percentage])

  useLayoutEffect(() => {setChangedValue({percentage})}, [])

  return (
    <div>
      <input
        autoFocus
        type="text"
        value={percentageString}
        className="text-center"
        style={{width:(percentageString.length)+'ch',
                minWidth: '2ch'}}
        onChange={(e)=>{
          const trimmedPercentage =
                e.target.value.split('')
                 .filter(c=>/[0-9\.]/.test(c))
                 .join('')
          setPercentage(
            Math.min(100,Number(trimmedPercentage)))
        }}
      />%
    </div>)
}
