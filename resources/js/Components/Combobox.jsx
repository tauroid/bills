import React, { forwardRef, useContext, useImperativeHandle, useState } from 'react'

import SearchBox from './SearchBox'
import SimpleChoice from './SimpleChoice'
import EditStamp from './EditStamp'
import { FullscreenModalStackContext } from '../Pages/Main'

export default forwardRef(function Combobox(
  {items, getSearchResults, getSearchResultsSynchronous,
   initialItem, changedItem, onChange, noStamp,
   emptyText, prompt, disabled}, ref)
{
  const { pushModal, popModal } = useContext(FullscreenModalStackContext)

  const [item, setItem] = changedItem !== undefined
        ? [changedItem ?? initialItem, onChange]
        : useState(initialItem)

  const stamp = item?.id !== initialItem?.id ? '🖋' : null

  const onSelect = (item) => {
    setItem(item)
    changedItem === undefined && onChange && onChange(item)
    popModal({skipPopAction: true})
  }

  const onClick = (popAction) => {
    items
      ? pushModal(<SimpleChoice {...{items, onSelect, prompt}}/>,
                  {popAction})
      : pushModal(
        <SearchBox {...{
          getResults: getSearchResults,
          getResultsSynchronous: getSearchResultsSynchronous,
          onSelect}}/>,
        {background: false, stopPropagation: false,
         popAction})
  }

  useImperativeHandle(ref, () => ({
    onActivate: (popAction)=>{
      if (!item) {
        onClick(popAction)
      }
    },
  }))

  const contents =
        <div className="h-fit" style={{textAlign: 'inherit'}}>
          {item?.name ?? emptyText}
        </div>

  return (
    <div className="flex gap-2 items-center">
      {disabled ? contents :
       <button ref={ref}
               onKeyDown={(e)=>{
                 if (e.key === 'Enter') {
                   e.stopPropagation()
                 }
               }}
               className="grow"
               style={{textAlign: 'inherit'}}
               onClick={()=>onClick()}>
         {contents}
       </button>}
      {!noStamp &&
       <EditStamp
         {...{disabled}}
         assignedStamp={stamp}
         onRemoveStamp={()=>onClick()}
         options={[{button:'🖋',name:'Edit',action:()=>onClick()}]}/>}
    </div>
  )
})
