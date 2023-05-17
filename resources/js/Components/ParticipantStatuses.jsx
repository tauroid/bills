import React from 'react'

import Amount from './Amount'

import { amountIsNegative, isZero, negative } from '../lib/amount'

export default function ParticipantStatuses(props) {
  const statuses = Object.entries(props).sort(
    ([_,statusA],[__,statusB])=>
    statusA.name === statusB.name
      ? 0 : (statusA.name.toLowerCase() > statusB.name.toLowerCase())
      ? 1 : -1
  ).map(
    ([entity_id,status], index) => {
      const amounts = Object.values(status.multiAmount)
      const borderT = index > 0 ? ' border-t' : ''
      return <tr key={entity_id}>
        <td className={
          "border-gray-300 border-r px-3 py-1" + borderT}>
          {status.name}
        </td>
        {/* TODO do this in a multi currency supporting way */}
        {isZero(amounts[0])
         ? <td colSpan="2" className={
           "border-gray-300 px-3 py-1" + borderT}>
             is even
           </td>
         : <>
             <td className={"border-gray-300 border-r px-3 py-1"
                            + borderT}>
               {amountIsNegative(amounts[0])
                ? 'is owed' : 'owes'}
             </td>
             <td className={ "border-gray-300 px-2 py-1"
                             + borderT }>
               <div className="flex justify-center">
                 <table>
                   <tbody>
                     {amounts.map((amount,index)=>
                       <tr key={index}>
                         <td className={( index < amounts.length-1
                                          ? "border-b"
                                          : "" )}>
                           <Amount {...(amountIsNegative(amount)
                                        ? negative(amount) : amount)} />
                         </td>
                       </tr>)}
                   </tbody>
                 </table>
               </div>
             </td>
           </>}
      </tr>
    })

  return <div className="w-fit flex flex-col items-center text-lg">
           Participant statuses:
           <div className="border border-gray-400 rounded-xl mt-2"
                style={{boxShadow: '0px 3px 16px -6px #333'}}>
            <table>
              <tbody>{statuses}</tbody>
            </table>
           </div>
         </div>
}
