import React, { forwardRef, useContext, useEffect, useImperativeHandle, useLayoutEffect, useRef, useState } from 'react'

import { FullscreenModalStackContext,
       FullscreenModalStackStateContext} from '../Pages/Main'
import { FullscreenModalFrameContext, FullscreenModalShadowContext } from './FullscreenModalStack'

const Stamp = forwardRef(
  function Stamp({button, name, action, anchor, stampId}, ref) {
    const buttonRef = useRef()
    const stampRef = useRef()

    const stackContext = useContext(FullscreenModalStackContext)
    const [stackState, setStackState] = useContext(FullscreenModalStackStateContext)

    useLayoutEffect(() => {
      const bcr = buttonRef.current.getBoundingClientRect()
      const stampBcr = stampRef.current.getBoundingClientRect()
      buttonRef.current.style.left = `${anchor.x - bcr['width'] + stampBcr['width']/2}px`
      buttonRef.current.style.top = `${anchor.y - bcr['height']/2}px`
    }, [])

    const shadow = useContext(FullscreenModalShadowContext)

    useImperativeHandle(ref, () => ({
      focus() { buttonRef.current.focus() }
    }))

    return (
      <button ref={buttonRef}
              className="absolute flex gap-3 items-center"
              onClick={(e)=>{
                e.stopPropagation()
                setStackState(stackState =>
                  ({...stackState,
                    stamps: {
                      ...stackState.stamps,
                      [stampId]: button
                    }}))
                action(
                  {...stackContext,
                   setStamp: (stamp) => {
                     setStackState(stackState =>
                       ({...stackState,
                         stamps: {
                           ...stackState.stamps,
                           [stampId]: stamp}}))
                   }
                  })
              }}>
        <span className="text-white"
              {...(shadow ? {style:{textShadow:'0 0 7px black'}} : {})}
        >{name}</span>
        <div ref={stampRef}
             className={"bg-white w-8 h-8 rounded-2xl "
                        +"flex justify-center items-center"}
             {...(shadow ? {style:{boxShadow:'0 0 50px black'}} : {})} >
          {button}
        </div>
      </button>
    )
  }
)

function Stamps({options, anchor, stamp, stampId}) {
  const modalFrameBcr =
        useContext(FullscreenModalFrameContext)
        .current.getBoundingClientRect()

  anchor = {
    x: anchor.x - modalFrameBcr['left'],
    y: anchor.y - modalFrameBcr['top']
  }

  const firstStampRef = useRef()

  useEffect(() => {
    firstStampRef.current.focus()
  }, [])

  return (
    <div>
      {options.map((option, index) => {
        const ydiff = (-(options.length-1)/2 + index) * 50
        const x = anchor.x - ydiff*ydiff/200 - 60

        return (<Stamp
                  {...(index === 0 ? {ref:firstStampRef} : {})}
                  key={index}
                  {...option}
                  anchor={{
                    x, y: anchor.y + ydiff
                  }}
                  stampId={stampId} />)
      })}
      <button
        className="bg-white text-3xl w-8 h-8 rounded-2xl absolute"
        style={{left: `calc(${anchor.x}px - 1rem)`,
                top: `calc(${anchor.y}px - 1rem)`,
                boxShadow: '0 0 11px 10px white'}}>
        <div className="absolute w-8 h-8 leading-8 top-0 left-0">💮</div>
        {stamp && <div className="absolute w-8 h-8 leading-8 top-0 left-0">{stamp}</div>}
      </button>
    </div>)
}

export default forwardRef(function EditStamp({
  options, onRemoveStamp, assignedStamp,
  disabled, showSingleOption}, ref) {
  const stackContext
        = useContext(FullscreenModalStackContext)
  const { pushModal } = stackContext

  const stampId = useRef(null)
  if (stampId.current === null) {
    stampId.current = Symbol()
  }

  const [stackState, setStackState]
      = useContext(FullscreenModalStackStateContext)
  const stamp =
        assignedStamp !== undefined
        ? assignedStamp
        : stackState.stamps?.[stampId.current]
  const setStamp = (newStamp) => {
    if (newStamp === null) {
      onRemoveStamp?.call(null, stamp, stackContext)
    }
    setStackState(stackState => ({
      ...stackState,
      stamps: {
        ...stackState.stamps,
        [stampId.current]: newStamp
      }
    }))
  }

  useEffect(() => () => {
    setStackState(stackState => {
      const newStackState = {...stackState}
      delete newStackState[stampId.current]
      return newStackState
    })
  }, [])

  const buttonRef = useRef()

  const onClick = (e) => {
    e?.stopPropagation()
    if (stamp) {
      setStamp(null)
      return
    }
    if (options.length === 1 && !showSingleOption) {
      setStamp(options[0].button)
      options[0].action({...stackContext, setStamp})
    } else {
      const bcr = buttonRef.current.getBoundingClientRect()
      const anchor = {
        x: (bcr['left']+bcr['right'])/2,
        y: (bcr['top']+bcr['bottom'])/2
      }
      pushModal(
        <Stamps {...{options, anchor}}
                stamp={stamp}
                stampId={stampId.current} />,
        {background: false})
    }
  }

  useImperativeHandle(ref, () => ({
    click: onClick,
    setStamp,
    stamp() { return stamp }
  }))

  return (
    <button
      ref={buttonRef}
      onKeyDown={(e)=>{
        if (e.key === 'Enter') {
          e.stopPropagation()
        }
      }}
      {...{disabled}}
      {...(disabled ? {} : {onClick} )}
      className="text-3xl w-8 h-8 relative">
      <div className={ "absolute w-8 h-8 flex justify-center"
                       +" items-center top-0 left-0" }>
        <div className="leading-8"
             {...(disabled
                  ? { style: {filter: 'opacity(25%)'} }
                  : {})} >
          💮
        </div>
      </div>
      {stamp &&
       <div className="absolute w-8 h-8 flex justify-center items-center top-0 left-0">
         <div className="leading-8">
           {stamp}
         </div>
       </div>}
    </button>
  )
})
