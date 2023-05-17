import React, { useContext, useRef, useState } from 'react'
import { router, usePage } from '@inertiajs/react'
import axios from 'axios'

import Amount from './Amount'
import EditStamp from './EditStamp'
import EditTransaction from './Transactions/EditTransaction'
import Entity from './Entity'
import { lcm as computeLcm } from '../Maths'
import {
  FullscreenModalStackContext,
  FullscreenModalStackStateContext
} from '../Pages/Main'
import BinaryChoice from './BinaryChoice'
import genericApiErrorHandler from '../lib/genericApiErrorHandler'
import InconsistentTransaction from './InconsistentTransaction'
import useDerivedState from '../useDerivedState'

function TransactionTableRow({
  transaction, viewingTransaction, setViewingTransaction}) {
  const { id:settlement_id, full_authority } = usePage().props

  const [stackState] = useContext(
    FullscreenModalStackStateContext)

  const numFroms = transaction.froms.length
  const numTos = transaction.tos.length
  const lcm = computeLcm(numFroms, numTos)
  const stepsPerFrom = lcm / numFroms
  const stepsPerTo = lcm / numTos

  const fromsEqualShares = transaction.froms.every((from)=>
    from.amount.currency === 'share' &&
      from.amount.amount_share.share === 1)
  const tosEqualShares = transaction.tos.every((to)=>
    to.amount.currency === 'share' &&
      to.amount.amount_share.share === 1)

  const editStampRef = useRef()

  const { pushModal, popModal } = useContext(FullscreenModalStackContext)

  return Array.from(Array(lcm).keys()).map((index) => {
    const newFrom = index % stepsPerFrom === 0
    const newTo = index % stepsPerTo === 0

    const from = newFrom &&
          transaction.froms[Math.floor(index / stepsPerFrom)]
    const to = newTo &&
          transaction.tos[Math.floor(index / stepsPerTo)]

    const notifyInconsistent = () => {
      pushModal(<InconsistentTransaction/>)
    }

    const popupEditTransaction = ({pushModal}) => {
      if (viewingTransaction) return
      pushModal(<EditTransaction {...transaction}/>,
                {background: false, popAction: ()=>{
                  setViewingTransaction(false)
                }})
      setViewingTransaction(true)
    }

    return (
      <tr className="cursor-pointer"
          key={index}
          onClick={(e)=>{
            e.stopPropagation()
            popupEditTransaction({pushModal})
          }}>
        {index === 0 &&
         <td className="underline break-words border-t border-b max-w-[150px]"
             rowSpan={lcm}
             tabIndex="0"
             onKeyDown={
               (e)=>e.key === 'Enter' &&
                 popupEditTransaction({pushModal})} >
           {transaction.description.split('\n')[0]}
         </td>}
        {newFrom &&
          <>
            <td className={
              "border"+(!fromsEqualShares ? " text-left" : "")}
                rowSpan={stepsPerFrom}
                colSpan={!fromsEqualShares ? 1 : 2} >
              <Entity {...from}/>
            </td>
            { !fromsEqualShares
              && <td className="border"
                    rowSpan={stepsPerFrom}>
                  <Amount {...from.absolute_amount} />
                 </td>}
          </>}
        {index === 0 &&
         <td className="border-t border-b"
             rowSpan={lcm}
             {...(transaction.inconsistent
                  ? {onClick: (e)=>{
                    e.stopPropagation()
                    notifyInconsistent()
                  }} : {})} >
           <div className="flex flex-col whitespace-nowrap">
             <div>
               {transaction.type.charAt(0).toUpperCase()
                +transaction.type.slice(1)+" "}
             </div>
             <span className={"text-4xl h-4 overflow-clip"
                              +" leading-3 align-middle"}>
               &#10230;
             </span>
             <div>
              <Amount {...transaction.amount} />
              {transaction.inconsistent &&
                <button
                  className="text-3xl align-sub text-red-400"
                  onClick={(e)=>{
                    e.stopPropagation()
                    notifyInconsistent()
                  }} >
                  ⚠
                </button>}
             </div>
           </div>
         </td>}
        {newTo &&
          <>
            <td className={
              "border"+(!tosEqualShares ? " text-left" : "")}
                rowSpan={stepsPerTo}
                colSpan={!tosEqualShares ? 1 : 2} >
              <Entity {...to}/>
            </td>
            { !tosEqualShares
              && <td className="border"
                    rowSpan={stepsPerTo}>
                  <Amount {...to.absolute_amount} />
                 </td>}
          </>}
        {index === 0 &&
          <td className="border" rowSpan={lcm}>
            {new Date(transaction.created_at)
            .toLocaleDateString()}
          </td>}
        {index === 0 &&
        <td className="border p-1" rowSpan={lcm}
            {...(!full_authority && transaction.full_authority
                 ? {style: {filter: 'drop-shadow(0 0 10px red)'}}
                 : {})} >
          <EditStamp
            ref={editStampRef}
            options={[
              {button: '🖋', name: 'Edit',
               action: popupEditTransaction},
              ...(transaction.full_authority ? [
              {button: '🗑', name: 'Delete',
                action: ({pushModal}) => {
                  pushModal(
                    <BinaryChoice
                      message={
                      <div>
                        <p>Are you sure?</p>
                        <p>This transaction will be permanently lost.</p>
                      </div>}
                      positive="Yes" onPositive={({popModal}) => {
                        axios.delete(`/api/settlement/${settlement_id}/transaction/${transaction.id}`)
                             .then(() => {
                               router.reload({
                                 onSuccess: () => {
                                   popModal(); popModal()
                                 }
                               })
                             })
                             .catch((error)=>
                               genericApiErrorHandler(
                                 error,
                                 "Couldn't delete the transaction",
                                 ()=>{popModal()},
                                 {pushModal, popModal}
                               ))
                      }}
                      negative="No" onNegative={({popModal}) => {
                        popModal(); popModal()
                      }}
                    />
                  )
                }}
              ] : [])
            ]}
            assignedStamp={
              (stackState.transactions?.
                [transaction.id]?.apiChanges
                ?? []
              ).length > 0
                ? '🖋' : null}
            onRemoveStamp={(_,{pushModal})=>{
              popupEditTransaction({pushModal})
            }}
          />
        </td>}
      </tr>
    )
  })
}

export default function Transactions(props) {
  const { pushModal } = useContext(
    FullscreenModalStackContext)
  const [stackState, setStackState] = useContext(
    FullscreenModalStackStateContext)

  const [viewingTransaction, setViewingTransaction] =
        useDerivedState([stackState, setStackState],
                        ['viewingTransaction'],
                        {defaultValue: false})

  const transactions = props.transactions.map(transaction =>
    <TransactionTableRow
      key={transaction.id}
      {...{transaction,
           viewingTransaction,
           setViewingTransaction}}/>
  )

  const creatingTransaction =
        (stackState.transactions
         ?.['new']?.apiChanges?.length
        ?? 0) > 0

  const newItemSymbol = (
    <span className="text-green-500">
      '✯'
    </span>)

  const spawnNewTransactionForm = () => {
    pushModal(
      <EditTransaction id={'new'} />,
      {background: false}
    )
  }

  return <div className="w-fit flex flex-col items-center">
           <div className={ "border rounded-t"
                            +(transactions.length > 0
                              ? " border-b-0 rounded-t"
                              : " rounded")}>
             <table className="border-hidden">
               <tbody>
                 <tr>
                   <td className="px-2 border cursor-pointer underline"
                       colSpan={6}
                       onClickCapture={(e)=>{
                         e.stopPropagation()
                         spawnNewTransactionForm()
                       }} >
                     Create new transaction:
                   </td>
                   <td className="p-1 border"
                       onClickCapture={(e)=>{
                         e.stopPropagation()
                         spawnNewTransactionForm()
                       }} >
                     <EditStamp
                       assignedStamp={
                         creatingTransaction
                           ? newItemSymbol : null}
                     />
                   </td>
                 </tr>
               </tbody>
             </table>
           </div>
           {transactions.length > 0 &&
            <div className="border rounded">
             <table className="border-hidden">
               <thead>
                 <tr>
                   <th className="border">Description</th>
                   <th className="border" colSpan="2">From</th>
                   <th className="border">Type / Amount</th>
                   <th className="border" colSpan="2">To</th>
                   <th className="border">Created</th>
                   <th className="border">Action</th>
                 </tr>
               </thead>
               <tbody>
                 {transactions}
               </tbody>
             </table>
           </div>}
         </div>
}
