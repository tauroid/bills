import React from 'react'

export default function Entity({
  id, name, associatedUserName
}) {
  return (
    associatedUserName ?
      (associatedUserName === name
       ? <>
           {name}{" "}
           <span className="font-bold text-purple-400">
             (=)
           </span>
         </>
       : <>
           {name}{" "}
           <span className="font-bold">
             ({associatedUserName})
           </span>
         </>)
    : name)
}
