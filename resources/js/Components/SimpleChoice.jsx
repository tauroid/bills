import React, { useEffect, useRef } from 'react'

export default function SimpleChoice(
  {items, onSelect, prompt}) {
  const firstChoiceRef = useRef()

  useEffect(() => {
    firstChoiceRef.current.focus()
  }, [])

  let separatorIndex = 0
  return (
    <div className="flex flex-col">
      {prompt && <div className="p-3 border-b">{prompt}</div>}
      {items.map((item,index) => {
        if (item.separator) {
          const sepKey = 'sep-'+separatorIndex
          ++separatorIndex
          return <div key={sepKey} className="border-b-4 border-gray-300 rounded mx-3 my-1"/>
        } else {
          return (
            <button
              key={item.id}
              {...(index === 0 ? {ref:firstChoiceRef} : {})}
              onClick={()=>{onSelect(item)}}
              className={"p-2"+(
                index < items.length-1
                  ? " border-b" : ""
              )+(
                index > 0 && items[index-1].separator
                  ? " border-t" : "")}>
              {item.name}
            </button> )
        }})}
    </div>
  )
}
