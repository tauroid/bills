import React, { useContext, useState } from 'react'

import { Head } from '@inertiajs/react'

import Main, { FullscreenModalStackContext } from './Main'
import EditStamp from '../Components/EditStamp'

export function ScratchComponent(props) {
  return (
    <div className="grid">
      <div style={{gridRowEnd:'l-1'}}>Hello</div>
      <div style={{gridRowEnd:'l-1'}}>Hi</div>
      <div style={{gridRowEnd:'l-1'}}>I</div>
      <div style={{gridRow:'span l-1 / span l-2'}}>Am</div>
      <div style={{gridRow:'span l-1 / span l-2'}}>A</div>
      <div style={{gridRow:'span l-1 / span l-2'}}>Table</div>
      <div style={{gridRow:'span l-2 / span l-3'}}>Here</div>
      <div style={{gridRowStart:'l-2'}}>Is</div>
      <div>A</div>
      <div style={{gridRowEnd:'l-3'}}>Thing</div>
    </div>)
}

export function Toggle({changedValue, setChangedValue}) {
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

export default function Scratch(props) {
  const [toggle, setToggle] = useState(false)
  return (
    <>
      <Head><title>Scratch</title></Head>
      <Main>
        <ScratchComponent />
        <Toggle {...{changedValue:toggle,
                     setChangedValue:setToggle}}/>
      </Main>
    </>
  )
}
