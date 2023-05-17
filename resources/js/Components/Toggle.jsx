import React from 'react'

export default function Toggle({changedValue, setChangedValue}) {
  return (
    <button onClick={()=>setChangedValue(!changedValue)}>
      <svg width="30" height="20">
        <path className={
          (changedValue
           ? "fill-black"
           : "fill-gray-200")
            +" stroke-gray-400"}
          d="M 10 1 a 9 9 0 0 0 0 18 l 10 0 a 9 9 0 0 0 0 -18 Z"
        />
        <circle className={changedValue ? "fill-white" : "fill-black"}
                cx={changedValue ? "20" : "10"} cy="10" r="6"/>
      </svg>
    </button>
  )
}
