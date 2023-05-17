// DEPRECATED
import React, { useContext, useState } from 'react'

import ApiSearchBox from './ApiSearchBox'
import EditStamp from './EditStamp'
import { FullscreenModalStackContext } from '../Pages/Main'

export default function ApiCombobox(
  {url, apiContext, initialItem, onChange, stackStateKey, noStamp})
{
  const { pushModal, popModal } = useContext(FullscreenModalStackContext)

  const [name, setName] = useState(
    initialItem ? initialItem.name : <div className="h-4"/>)

  const [stamp, setStamp] = useState(null)

  const onClick = () => {
    if (!noStamp) setStamp(stamp)
    pushModal(
      <ApiSearchBox {...{url, apiContext, onSelect: (item) => {
        setName(item.name)
        onChange && onChange(item)
        if (!noStamp) {
          if (item.id !== initialItem.id) setStamp('🖋')
          else setStamp(null)
        }
        popModal()
      }, stackStateKey}}/>,
      {background: false, stopPropagation: false})
  }

  return (
    <div className="flex">
      <button className="w-full" onClick={onClick}>
        {name}
      </button>
      {!noStamp &&
       <EditStamp
         assignedStamp={stamp}
         onRemoveStamp={onClick}
         options={[{button:'🖋',name:'Edit',action:onClick}]}/>}
    </div>
  )
}
