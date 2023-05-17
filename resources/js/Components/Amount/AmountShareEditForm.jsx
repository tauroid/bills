import React, { useLayoutEffect, useMemo } from 'react'

import useDerivedState from '../../useDerivedState'

export default function AmountShareEditForm(
  {amount_share, changedValue, setChangedValue})
{
  const [share, setShare] = useDerivedState(
    [changedValue, setChangedValue], ['share'],
    {defaultValue: amount_share?.share ?? 1})

  const shareString = useMemo(() => String(share), [share])

  useLayoutEffect(() => {setChangedValue({share})}, [])

  return (
    <div>
      Share:
      <input
        autoFocus
        type="text"
        value={shareString}
        className="text-center ms-2"
        style={{width:(shareString.length)+'ch',
                minWidth: '2ch'}}
        onChange={(e)=>{
          const trimmedShare =
                e.target.value.split('')
                 .filter(c=>/[0-9]/.test(c))
                 .join('')
          setShare(Number(trimmedShare))
        }}
      />
    </div>)
}
