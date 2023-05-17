import React, { createContext, useEffect, useRef, useState } from 'react'

import { Link } from '@inertiajs/react'

import FullscreenModalStack from '../Components/FullscreenModalStack'

export const FullscreenModalStackContext = createContext(null)
export const FullscreenModalStackStateContext = createContext(null)

export default function Main(props) {
  const [modalStack, setModalStack] = useState([])

  const pushModal = (content, options, replaceTop = false) => {
    const defaultOptions = {
      background: true,
      stopPropagation: true,
      stackState: false,
      popAction: null,
      disablePopOnClickOutside: false
    }
    const id = Symbol()
    setModalStack(modalStack => [
      ...(replaceTop ? modalStack.slice(0,-1) : modalStack),
      {
        id,
        content: content,
        ...defaultOptions,
        ...options
      }])
    return id
  }

  const removeModal = (id) => {
    setModalStack(modalStack => {
      const index = modalStack.findIndex(data => data.id === id)
      if (index === -1) return modalStack
      else {
        const newModalStack = [...modalStack]
        newModalStack.splice(index,1)
        return newModalStack
      }
    })
  }

  const popModal = ({skipPopAction} = {skipPopAction: false}) => {
    setModalStack(modalStack => {
      const top = modalStack[modalStack.length-1]
      if (!skipPopAction) {
        top.popAction?.call(null)
      }
      return modalStack.slice(0,-1)
    })
  }

  const popStack = () => {
    for (const top of [...modalStack].reverse()) {
      top.popAction?.call()
    }
    setModalStack([])
  }

  const mainRef = useRef()

  useEffect(() => {
    const handleEscape = (e) => {
      if (e.key === 'Escape') {
        popModal()
      }
    }

    window.addEventListener('keydown', handleEscape)

    return () => window.removeEventListener(
      'keydown', handleEscape)
  }, [])

  return (
    <FullscreenModalStackContext.Provider value={{pushModal, popModal, popStack, removeModal}}>
      <FullscreenModalStackStateContext.Provider value={useState({})}>
        <main ref={mainRef}
              className={
                "relative flex flex-col py-4 "
                +(modalStack.length > 0
                  ? "overflow-hidden h-screen"
                  : "overflow-scroll")}>
          <div className="grow flex flex-col mx-auto px-8">
            <Link href={`/logout`} method="post" as="button"
                  className="underline">
              Logout
            </Link>
            {props.children}
          </div>
        </main>
        {modalStack.length > 0 &&
         <FullscreenModalStack {...{
           modalStack, popModal, parentElement: mainRef}} />}
      </FullscreenModalStackStateContext.Provider>
    </FullscreenModalStackContext.Provider>
  )
}
