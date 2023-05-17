import React, { createContext, useContext, useState } from 'react'

export const FullscreenModalFrameContext = createContext(null)
export const FullscreenModalShadowContext = createContext(false)

import { FullscreenModalStackStateContext } from '../Pages/Main'

function StackFrame({modalContent, background, child,
                     createStackState, disablePopOnClickOutside,
                     popModal, index, modalStackLength}) {
  const container = (modalContent) => (
    <div className="p-8 m-auto">
      <div className="bg-white w-fit rounded"
           style={index === modalStackLength - 1 ? {}
                  : {boxShadow: '0 0 100px black'}}
           onClick={(e)=>e.stopPropagation()} >
        {modalContent}
      </div>
    </div>
  )

  const content = (
    <>
      {background ? container(modalContent) : modalContent}
      {child}
    </>
  )

  return (
    <div className={
      "position-modal flex flex-col"
        +"  w-full h-screen top-0"
        +(index === modalStackLength - 1
          ? " overflow-scroll" : " overflow-hidden")}
         style={{...(
           index === modalStackLength - 1
             ? {background: 'rgba(0,0,0,0.7)',
                backdropFilter: 'blur(3px)'}
           : {}),
                 zIndex: (index+1)*10}}
         onClick={(e)=> {
           e.stopPropagation()
           disablePopOnClickOutside || popModal()
         }} >
      {(content => (
        createStackState
          ? <FullscreenModalStackStateContext.Provider
              value={useState({})} >
              {content}
            </FullscreenModalStackStateContext.Provider>
        : content))((content =>
        background
          ? content
          : <FullscreenModalShadowContext.Provider
              value={index < modalStackLength-1}>
              {content}
            </FullscreenModalShadowContext.Provider>
        )(content))}
    </div>)
}

export default function FullscreenModalStack({modalStack, popModal, parentElement}) {
  const [stackState] = useContext(FullscreenModalStackStateContext)

  return (
    <FullscreenModalFrameContext.Provider value={parentElement}>
    {[...modalStack].reverse().reduce(
      (child,
       {content:modalContent,
        background,
        stackState:createStackState,
        disablePopOnClickOutside},
        index) =>
      <StackFrame {...{
        modalContent, background, child, createStackState,
        disablePopOnClickOutside, popModal,
        index: modalStack.length - index - 1,
        modalStackLength: modalStack.length}} />,
      null)}
    </FullscreenModalFrameContext.Provider>
  )
}
