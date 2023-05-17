import React, { useContext, useState } from 'react'
import { Link, router, usePage } from '@inertiajs/react'

import Amount from './Amount'
import { FullscreenModalStackContext } from '../Pages/Main'

import BinaryChoice from './BinaryChoice'
import EditStamp from './EditStamp'

import InconsistentTransactions from './InconsistentTransactions'

import { amountIsNegative, negative} from '../lib/amount'

function NewSettlement() {
  const [name, setName] = useState('')

  const { popStack } = useContext(FullscreenModalStackContext)

  return (
    <div className="p-4">
      <form onSubmit={(e) => {
        e.preventDefault()
        router.post(`/api/settlements`, {name}, {onSuccess: popStack})
      }}>
        <div className="border-b pb-1">
            <p>Create settlement</p>
        </div>
        <div className="py-1 border-b w-full flex">
            <div className="grow text-left pe-2">
              <label htmlFor="newSettlementName">Name:</label>
            </div>
            <div>
            <input id="newSettlementName" type="text"
                   autoFocus
                   className="w-32"
                   onChange={(e)=>setName(e.target.value)} />
            </div>
        </div>
        <div className="pt-1">
            <button type="submit" className="underline">
              Confirm
            </button>
        </div>
      </form>
    </div>)
}

function EditSettlement({settlement_id}) {
  const settlement =
    usePage().props.settlements.find(
      settlement=>settlement.id === settlement_id)

  const [name, setName] = useState(settlement.name)

  const { popStack } = useContext(FullscreenModalStackContext)

  return (
    <div className="p-4">
      <form onSubmit={(e) => {
        e.preventDefault()
        router.post(`/api/settlement/${settlement_id}/edit`, {
          name
        }, {onSuccess: popStack})
      }}>
        <div className="border-b pb-1">
            <p>Modify settlement</p>
        </div>
        <div className="py-1 border-b w-full flex">
            <div className="grow text-left pe-2">
              <label htmlFor="editSettlementName">Name:</label>
            </div>
            <div>
            <input id="editSettlementName" type="text"
                   autoFocus
                   className="w-32" defaultValue={settlement.name}
                   onChange={(e)=>setName(e.target.value)} />
            </div>
        </div>
        <div className="pt-1">
            <button type="submit" className="underline">
              Confirm
            </button>
        </div>
      </form>
    </div>)
}

function SettlementListEntry({settlement, permissionsButton}) {
  const {pushModal} = useContext(FullscreenModalStackContext)

  const goToSettlement=()=>{
    router.visit(`/settlement/${settlement.id}`)
  }

  const user_entity_id = usePage().props.user_entity_id

  const ownStatus = settlement.statuses.find(
    ({id})=>id === user_entity_id)

  // TODO support more currencies
  const ownAmount = ownStatus &&
        Object.values(ownStatus.multiAmount)[0]

  return (
     <tr className="h-full cursor-pointer"
         onClick={goToSettlement}
         onKeyDown={(e)=>e.key === 'Enter' && goToSettlement()}
         tabIndex="0"
     >
      <td className="border px-2 underline">
        {settlement.name}
      </td>
      <td className="border px-2"
          {...(settlement.inconsistent_transactions ?
               {onClick: (e)=>{
                 e.stopPropagation()
                 pushModal(<InconsistentTransactions/>)
               }} : {})} >
        {settlement.outstanding
         ? <>
             <div>
               <Amount {...settlement.outstanding} />
               {" "} to settle
             </div>
             {ownStatus &&
             <div>
               You {
                 amountIsNegative(ownAmount)
                   ? <>
                       {' are owed '}
                       <Amount {...negative(ownAmount)}/>
                     </>
                   : <>
                       {' owe '} <Amount {...ownAmount}/>
                     </>
               }
             </div>}
           </>
         : (settlement.multiple_currencies
            ? "Multiple currencies"
            : "Finished")
        }
        {settlement.inconsistent_transactions
         && <>
              <button
                className="text-3xl align-sub text-red-400"
                onClick={(e)=>{
                  e.stopPropagation()
                  pushModal(<InconsistentTransactions/>)
                }} >
                ⚠
              </button>
              {" "}
            </>}
      </td>
      <td className="border pl-6 pr-2 text-left">
        <ul className="list-disc">
          {settlement.participants.map((participant) =>
            <li key={participant.id}>
              {participant.name}
            </li>)}
        </ul>
      </td>
      <td className="border px-2">
        {new Date(settlement.created_at).toLocaleDateString()}
      </td>
       <td className="relative border px-2 h-full"
           {...(settlement.owner_data ?
                {style: {filter: 'drop-shadow(0 0 10px purple)'}}
                : {})}
       >
         <div className="flex items-center justify-center"
              {...(!settlement.owner_data && settlement.authority ?
                   {style: {border: '2px solid #F006',
                            width: '42px', height: '42px',
                            borderRadius: '21px'}} : {})}>
         <EditStamp
           disabled={!settlement.owner_data}
           assignedStamp={null}
           options={settlement.owner_data ? [
             {button: '🖋',
              name: 'Edit',
              action: ({pushModal, setStamp}) => {
                pushModal(
                  <EditSettlement {...{
                    settlement_id: settlement.id}}/>,
                  {popAction: ()=>setStamp(null)})
              }},
             ...(settlement.owner_data ? [permissionsButton] : []),
             {button: '🗑',
              name: 'Delete',
              action: ({popModal, popStack}) => {
                pushModal(
                  <BinaryChoice
                    message={
                      <div>
                        <p>Are you sure?</p>
                        <p>This settlement will be
                          permanently lost</p>
                      </div>}
                    positive="Yes" onPositive={()=>{
                      router.delete(`/api/settlement/${settlement.id}`)
                      popStack()
                    }}
                    negative="No" onNegative={popModal}
                  />
                )
              }}
           ] : []}/>
         </div>
       </td>
    </tr>
  )
}

export default function Settlements(props) {
  const { pushModal } = useContext(FullscreenModalStackContext)

  const settlements = props.settlements.map((settlement) =>
    <SettlementListEntry key={settlement.id} {...{
      settlement,
      permissionsButton:
      props.ownerUI?.permissionsButton(settlement.id)
    }}/>
   )

  const popupNewSettlement = (e)=>{
    e.stopPropagation()
    pushModal(<NewSettlement/>)
  }

  return <div className="grow flex flex-col">
           <Link href={`/home`}>Home</Link>
             <h1 className="text-4xl font-bold mt-8">Settlements</h1>
           <div className="grow flex-col justify-center items-center">
             <div className={
               "border" +(settlements.length > 0
                          ? " border-b-0 rounded-t"
                          : " rounded")}>
               <table className="border-hidden">
                 <tbody>
                   <tr className="cursor-pointer"
                       onClick={popupNewSettlement}
                       onKeyDown={(e)=>
                         e.key === 'Enter'
                           && popupNewSettlement()}
                       tabIndex="0">
                     <td className="px-2 border border-b-0 underline">
                       Create new settlement:
                     </td>
                     <td className="p-1 border border-b-0">
                       <button
                         className="text-3xl"
                         onClick={popupNewSettlement}>
                         💮
                       </button>
                     </td>
                   </tr>
                 </tbody>
               </table>
             </div>
             {settlements.length > 0 &&
              <div className="border rounded">
               <table className="border-hidden">
                 <thead>
                   <tr>
                     <th className="border rounded-tl">Name</th>
                     <th className="border">Amount</th>
                     <th className="border">Participants</th>
                     <th className="border">Created</th>
                     <th className="border rounded-tr">Action</th>
                   </tr>
                 </thead>
                 <tbody>{settlements}</tbody>
               </table>
             </div>}
         </div>
         </div>
}
