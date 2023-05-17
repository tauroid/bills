import React, { useEffect } from 'react'

import fountainPen from '../../images/fountain-pen.svg'
import inkwell from '../../images/inkwell.svg'
import inkwellWithPen from '../../images/inkwell-with-pen.svg'

export default function EditPen({editing, setEditing}) {
  useEffect(() => {
    if (!editing) {
      document.body.style.cursor = 'auto'
    } else {
      document.body.style.cursor =
        `url(${fountainPen}) 0 60, text`
    }
  }, [editing])

  return (
    <div className="absolute bottom-8 right-8" >
      {!editing
       ? <img src={inkwellWithPen}
              onClick={(e) => {
                setEditing(true)
              }}/>
       : <img src={inkwell} onClick={()=>setEditing(false)} />}
    </div>
  )
}
