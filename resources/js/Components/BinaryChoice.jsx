import React, { useEffect, useRef } from 'react'
import { useContext } from 'react'

import { FullscreenModalStackContext } from '../Pages/Main'

export default function BinaryChoice({
  message, positive, onPositive, negative, onNegative
}) {
  const stackContext = useContext(
    FullscreenModalStackContext)

  const no = useRef()

  useEffect(() => {
    no.current.focus()
  }, [])

  return (
    <div className="flex flex-col w-72">
      <div className={ "grow w-full flex items-center"
                       +" justify-center border-b p-2" }>
        {message}
      </div>
      <div className="flex">
        <button ref={no}
                className={
                  "border-r grow flex items-center rounded-bl"
                    +" justify-center p-2 overflow-clip"
                    +" focus:bg-red-100"}
                onClick={()=>onNegative(stackContext)} >
          {negative}
        </button>
        <button className={ "grow flex items-center"
                            +" justify-center p-2"
                            +" focus:bg-red-100"}
             onClick={()=>onPositive(stackContext)}>
          {positive}
        </button>
      </div>
    </div>
  )
}
