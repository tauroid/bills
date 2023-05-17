import React, { useContext, useEffect, useLayoutEffect, useRef } from 'react'

import Amount from '../Amount'
import AmountEditForm from '../Amount/AmountEditForm'
import BinaryChoice from '../BinaryChoice'
import Combobox from '../Combobox'
import EditStamp from '../EditStamp'
import Entity from '../Entity'
import { FullscreenModalShadowContext } from '../FullscreenModalStack'
import {
  FullscreenModalStackContext,
  FullscreenModalStackStateContext
} from '../../Pages/Main'
import Table from '../Table'

import createAddTransactionObject from '../../lib/addTransaction'
import createEditTransactionObject from '../../lib/editTransaction'
import genericApiErrorHandler from '../../lib/genericApiErrorHandler'
import useDerivedState, {setAtPath} from '../../useDerivedState'

import {
  ExtraCells,
  NewItemCellsInactive,
  EditableCells
} from './AttributionTable'

import { router, usePage } from '@inertiajs/react'
import axios from 'axios'
import InconsistentTransaction from '../InconsistentTransaction'

export default function EditTransaction({id}) {
  const { pushModal, popModal } = useContext(
    FullscreenModalStackContext)

  const settlement_id = usePage().props.id
  const transaction = usePage().props.transactions.find(
    transaction=>transaction.id === id)

  const [stackState, setStackState] =
        useContext(FullscreenModalStackStateContext)

  const transactionTypeItem = ({
    'payment': {id: 'payment', name: 'Payment'},
    'service': {id: 'service', name: 'Service'}
  })[transaction?.type]

  const attributionsChangesToApiChanges = (attributionsChanges,fromsOrTos) => {
    return [
      ...(attributionsChanges.newItems?.map(item =>
        [[fromsOrTos, 'new'], item]) ?? []),
      ...(attributionsChanges.edit
          ? Object.entries(attributionsChanges.edit).flatMap(
            ([id,{party, amount}]) => [
              ...(party ? [[[fromsOrTos,id,'entity_id'], party.id]] : []),
              ...(amount
                  ? Object.entries(amount).map(
                    ([currencyAmountKey, currencyAmount]) =>
                    [[fromsOrTos,id,'amount',currencyAmountKey],
                     currencyAmount])
                  : [])
            ])
          : []),
      ...(attributionsChanges.delete?.map(id =>
        [[fromsOrTos,id], null]) ?? [])
    ]
  }

  const setApiChangesInStackState = stackState => {
    let {changedDescription,
         fromsChanges,
         changedType,
         editingAmount,changedAmount,
         tosChanges} = stackState.transactions?.[id]
    changedDescription ??= transaction?.description ?? ''
    fromsChanges ??= {}
    changedType ??= transactionTypeItem
    editingAmount ??= false; changedAmount ??= {}
    tosChanges ??= {}
    return setAtPath(
      stackState, ['transactions',id,'apiChanges'], () => [
        ...(changedDescription !== (transaction?.description ?? '')
            ? [['description', changedDescription]] : []),
        ...(attributionsChangesToApiChanges(
          fromsChanges, 'froms')),
        ...(changedType?.id !== transaction?.type
            ? [['type', changedType.id]] : []),
        ...((editingAmount
             && Object.keys(changedAmount).length > 0)
            ? [['amount', changedAmount]] : []),
        ...(attributionsChangesToApiChanges(
          tosChanges,'tos')),
      ])
  }

  const stackStateLens = [stackState, setStackState]
  const apiChangesCallback =
        () => setStackState(setApiChangesInStackState)

  const useTransactionState =
        (key, defaultValue, updateApiChanges = true) => useDerivedState(
          stackStateLens, ['transactions', id, key],
          {defaultValue,
           callback: updateApiChanges && apiChangesCallback})

  const apiChanges = stackState.transactions?.[id]?.apiChanges

  const [changedDescription, setChangedDescription] =
        useTransactionState(
          'changedDescription', transaction?.description ?? '')

  const [fromsChanges, setFromsChanges] =
        useTransactionState('fromsChanges', {})

  const [changedType, setChangedType] =
        useTransactionState('changedType', transactionTypeItem)

  const [changedAmount, setChangedAmount] =
        useTransactionState('changedAmount', {})

  const [tosChanges, setTosChanges] =
        useTransactionState('tosChanges', {})

  const [editingAmount, setEditingAmount] =
        useTransactionState('editingAmount', false)

  const [fromsState, setFromsState] =
        useTransactionState('fromsState', {}, false)

  const [tosState, setTosState] =
        useTransactionState('tosState', {}, false)

  const [chosenCurrency, setChosenCurrency] =
        useTransactionState('chosenCurrency', null, false)

  const amountStampRef = useRef()

  const shadow = useContext(FullscreenModalShadowContext)

  const shadowStyle = shadow
        ? "relative after:shadow-[0_0_100px_black]"
    +" after:content-[''] after:z-[-1] after:absolute"
        + " after:top-0 after:bottom-0 after:left-0 after:right-0"
        : ""

  const [confirm, setConfirm] =
        useTransactionState('confirm', false, false)
  const [discard, setDiscard] =
        useTransactionState('discard', false, false)

  const full_authority =
        transaction?.full_authority ?? id === 'new'

  const onConfirm = () => {
    if (id !== 'new' && (apiChanges?.length ?? 0) === 0) return
    setConfirm(true)
    if (id === 'new') {
      axios.post(
        `/api/settlement/${settlement_id}/transactions`,
        createAddTransactionObject(
          stackState.transactions?.[id])
      ).then(() => {
        router.reload()
        setStackState(stackState => ({
          ...stackState,
          transactions: {
            ...(stackState.transactions ?? {}),
            [id]: {}
          }
        }))
        popModal()
      }).catch(error => {
        genericApiErrorHandler(
          error,
          "Couldn't create the transaction",
          ()=>{setConfirm(false)},
          {pushModal, popModal})
      })
    } else {
      pushModal(
        <BinaryChoice
          {...{
            message:
            <div>
              <p>Are you sure?</p>
              <p>This transaction will be overwritten.</p>
            </div>,
            positive: 'Yes', onPositive: ({popModal}) => {
              axios.post(
                `/api/settlement/${settlement_id}/transaction/${transaction.id}/edit`,
                createEditTransactionObject(
                  stackState.transactions?.[id])
              ).then(() => {
                router.reload({
                  onSuccess: () => {
                    setStackState(stackState => ({
                      ...stackState,
                      transactions: {
                        ...(stackState.transactions ?? {}),
                        [id]: {}
                      }
                    }))
                    popModal()
                  }
                })
              }).catch(error => {
                popModal()
                genericApiErrorHandler(
                  error,
                  "Couldn't edit the transaction",
                  ()=>{setConfirm(false)},
                  {pushModal, popModal})
              })
            },
            negative: 'No', onNegative: ({popModal}) => {
              setConfirm(false)
              popModal({skipPopAction:true})
            }
          }}/>,
        {popAction:()=>{setConfirm(false)}}
      )
    }
  }

  const onDiscard = () => {
    if ((apiChanges?.length ?? 0) === 0) {
      popModal()
      return
    }
    setDiscard(true)
    pushModal(
      <BinaryChoice
        {...{
          message:
          <div>
            <p>Are you sure?</p>
            <p>{
              id === 'new'
                ? 'This transaction will not be created.'
                : 'All changes to this transaction'
                +' will be lost.'
            }</p>
          </div>,
          positive: 'Yes', onPositive: ({popModal}) => {
            setStackState(stackState => ({
              ...stackState,
              transactions: {
                ...(stackState.transactions ?? {}),
                [id]: {}
              }
            }))
            popModal({skipPopAction:true})
            popModal()
          },
          negative: 'No', onNegative: ({popModal}) => {
            setDiscard(false)
            popModal({skipPopAction:true})
          }
        }}/>,
      {popAction:()=>{setDiscard(false)}}
    )
  }

  const descriptionRef = useRef()

  useLayoutEffect(()=> {
    descriptionRef.current.style.height = '0px'
    descriptionRef.current.style.height =
      Math.max(
        50, Math.min(150,descriptionRef.current.scrollHeight)
      ) + 3 + 'px'
  }, [changedDescription])

  const discardRef = useRef()

  useEffect(() => {
    discardRef.current.focus()
    discardRef.current.blur()
  }, [])

  return (
    <div className="flex flex-col rounded m-auto p-8 pt-20"
         onKeyDown={(e)=>{
           if (!confirm && e.key === 'Enter') {
             e.stopPropagation()
             onConfirm()
           }
         }} >

      <div className={ "flex flex-col rounded-t border-b"
                     +" bg-white "+shadowStyle}
           onClick={(e)=>e.stopPropagation()} >
        <div className={"relative flex h-7 "+shadowStyle} >
          {transaction?.inconsistent &&
           <button className={"absolute text-4xl text-red-400 bg-white"
                              +" flex items-center justify-center"}
                   style={{borderRadius:'20px',top:'-20px',left:'-20px',
                           boxShadow: '0 0 10px black',
                           width:'40px', height:'40px',
                           zIndex: '2'}}
                   onClick={(e)=>{
                     e.stopPropagation()
                     pushModal(<InconsistentTransaction/>)
                   }} >
             <div className="relative" style={{top: '-3px'}}>
               ⚠
             </div>
           </button>
          }
          <div className={"relative grow flex items-center"
                          +" justify-center"} >
            <div
              ref={discardRef}
              tabIndex="0"
              className={
                "absolute flex flex-col justify-around"
                  +" items-center"
                  +" gap-2 cursor-pointer"
                  +" font-bold text-white"}
              style={{top: '-57px', zIndex: '1'}}
              onClick={(e)=>{
                e.stopPropagation()
                onDiscard()
              }}
              onKeyDown={(e)=>{
                if (e.key === 'Enter') {
                  e.stopPropagation()
                  onDiscard()
                }
              }}
            >

              Discard{id !== 'new' && ' changes'}
              <div className={ "bg-white w-12 h-12 rounded-3xl"
                               +" text-3xl leading-12" }
                   style={{boxShadow: '0 0 10px black',
                           paddingTop: '7px'}}
              >
                🗑
              </div>
            </div>
          </div>
          <div className={ "relative grow flex items-center"
                           +" justify-center" } >
            <div
              tabIndex="0"
              className={
                "absolute flex flex-col justify-around"
                  +" items-center"
                  +" gap-2"
                  +" font-bold text-white"
                  +( (apiChanges?.length ?? 0) === 0
                     && id !== 'new'
                     ? "" : " cursor-pointer")}
              style={{top: '-57px', zIndex: '1'}}
              onClick={(e)=>{
                 e.stopPropagation()
                 onConfirm()
               }}
              onKeyDown={(e)=>{
                if (e.key === 'Enter') {
                  e.stopPropagation()
                  onConfirm()
                }
              }}
            >
              <span style={((apiChanges?.length ?? 0) === 0
                            && id !== 'new'
                            ? {opacity: '30%'} : {})}>
                Confirm
              </span>
              <div className={ "bg-white w-12 h-12 rounded-3xl"
                               +" text-3xl leading-12" }
                   style={{boxShadow: '0 0 10px black',
                           paddingTop: '7px'}}
              >
                <span style={((apiChanges?.length ?? 0) === 0
                              && id !== 'new'
                               ? {opacity: '30%'} : {})}>
                  📮
                </span>
              </div>
            </div>
          </div>
        </div>
        <textarea
          ref={descriptionRef}
          className={ "m-4 placeholder:text-center border rounded"
                    +" resize-none p-2"}
          onChange={(e)=>setChangedDescription(e.target.value)}
          onKeyDown={(e)=>e.stopPropagation()}
          placeholder="Description"
          maxLength="10000"
          value={changedDescription}
        />
      </div>
      <div className="flex items-start">
        <div onClick={(e)=>e.stopPropagation()}
             className={shadowStyle}>
          <Table
            tableItems={transaction?.froms.map(
              ({id,name,associatedUserName,
                amount,absolute_amount})=>
              ({id, name:<Entity {...{
                id, name, associatedUserName
              }}/>, ExtraCells,
                extraData: {
                  party:{id,name},amount,absolute_amount,
                  chosenCurrency, setChosenCurrency,
                  partyMustBeUser: !full_authority
                }}))
                        ?? []}
            editableIds={
              transaction?.froms
                          .filter(({can_edit})=>can_edit)
                          .map(({id})=>id)
                ?? []}
            {...{EditableCells, NewItemCellsInactive}}
            newItemExtraData={
              {chosenCurrency, setChosenCurrency,
               partyMustBeUser: !full_authority}}
            newItemCellsInactiveExtraData={full_authority}
            changes={fromsChanges}
            setChanges={setFromsChanges}
            tableState={fromsState}
            setTableState={setFromsState}
          />
        </div>
        <div className={"flex flex-col p-4 border-x bg-white"
                        +" items-center "+shadowStyle}
             onClick={(e)=>e.stopPropagation()}>
          <Combobox {...{
            disabled: !full_authority,
            items:[{id:'payment',name:'Payment'},
                   {id:'service',name:'Service'}],
            initialItem: transactionTypeItem,
            changedItem: changedType ?? null,
            onChange: setChangedType,
            emptyText: 'Pick transaction type'
          }}/>
          <span className={"text-4xl h-4 overflow-clip my-2"
                           +" leading-3 align-middle"}>
            &#10230;
          </span>
          <div className="flex gap-2 items-center">
            {editingAmount
             ? <AmountEditForm
                  amount={transaction?.amount}
                  onDenyCurrency={()=>setEditingAmount(false)}
                  {...{chosenCurrency, setChosenCurrency}}
                  changedValue={changedAmount}
                  setChangedValue={setChangedAmount}
                  onlyAbsolute/>
             : (!full_authority ?
                <Amount {...transaction?.amount}/> :
                <button onClick={()=>amountStampRef.current.click()}
                        onKeyDown={(e)=>{
                          if (e.key === 'Enter') {
                            e.stopPropagation()
                          }
                        }}>
                  <Amount {...transaction?.amount} />
                </button> )}
            <EditStamp
              ref={amountStampRef}
              disabled={!full_authority}
              options={[
                {button: '🖋', name: 'Edit', action: () => {
                  setEditingAmount(true)
                }}
              ]}
              assignedStamp={editingAmount ? '🖋' : null}
              onRemoveStamp={()=>setEditingAmount(false)}/>
          </div>
        </div>
        <div onClick={(e)=>e.stopPropagation()}
             className={shadowStyle}>
          <Table
            tableItems={
              transaction?.tos.map(
                ({id,name,associatedUserName,
                  amount,absolute_amount})=>
                ({id, name:<Entity {...{
                  id, name, associatedUserName
                }}/>, ExtraCells,
                  extraData: {
                    party:{id,name}, amount, absolute_amount,
                    chosenCurrency, setChosenCurrency,
                    partyMustBeUser: !full_authority
                  }}))
                ?? []}
            editableIds={
              transaction?.tos
                          .filter(({can_edit})=>can_edit)
                          .map(({id})=>id)
                ?? []}
            {...{EditableCells, NewItemCellsInactive}}
            newItemExtraData={
              {chosenCurrency, setChosenCurrency,
               partyMustBeUser: !full_authority}}
            newItemCellsInactiveExtraData={full_authority}
            changes={tosChanges}
            setChanges={setTosChanges}
            tableState={tosState}
            setTableState={setTosState}
          />
        </div>
      </div>
    </div>
  )
}
