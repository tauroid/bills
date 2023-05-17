import React, { forwardRef, useLayoutEffect } from 'react'
import { usePage } from '@inertiajs/react'

import useDerivedState from '../../useDerivedState'
import Amount from '../Amount'
import AmountEditForm from '../Amount/AmountEditForm'
import Combobox from '../Combobox'
import Entity from '../Entity'

export function ExtraCells(
  {absolute_amount, enableEditing, disabled})
{
  const amount = <Amount {...absolute_amount}/>
  return <td className="border-l px-2">
           {disabled ? amount :
            <button onClick={()=>{enableEditing()}}
                    onKeyDown={(e)=>{
                      if (e.key === 'Enter') {
                        e.stopPropagation()
                      }
                    }}
            >
             {amount}
            </button>}
         </td>
}

export function NewItemCellsInactive(
  {activateNewItem, extraData: full_authority}) {
  return (
  <>
    <td colSpan={2} onClick={activateNewItem}
        className="cursor-pointer pe-1">
      {full_authority ? 'Add new party' : 'Add amount'}
    </td>
  </>)
}

export const EditableCells = forwardRef(
  function EditableCells({
    data: {extraData:{
      party,amount,
      chosenCurrency, setChosenCurrency,
      partyMustBeUser
    }},
    changedValue, setChangedValue,
    onFailInit
  }, ref) {

    // TODO could be more direct
    const participants =
          Object.values(usePage().props.statuses)
                .map(({id,name})=>({id,name}))
    const linked_users = usePage().props.linked_users_entities
    const dummy_entities = usePage().props.dummy_entities

    const { user_entity_id, user_name } = usePage().props

    const candidate_participants = usePage().props.full_authority
          ? [{id: user_entity_id, name: user_name},
             ...participants, ...linked_users, ...dummy_entities]
          : participants

    const [changedParty, setChangedParty] =
          useDerivedState(
            [changedValue, setChangedValue], ['party'])

    const [changedAmount, setChangedAmount] =
          useDerivedState(
            [changedValue, setChangedValue], ['amount'])

    useLayoutEffect(() => {
      partyMustBeUser &&
        setChangedParty({id: user_entity_id, name: user_name})
    }, [])

    return (
      <>
        <td className="text-left pe-2">
          {partyMustBeUser
           ? changedParty && <Entity {...changedParty}/>
           : <Combobox
               ref={ref}
               emptyText="Selecting"
               noStamp={true}
               getSearchResultsSynchronous={(searchTerm)=>{
                 return candidate_participants
                   .filter(
                   ({id,name}, index)=>
                   name.toLowerCase().includes(
                     searchTerm.toLowerCase())
                     && candidate_participants.findIndex(
                       ({id:id_})=>id===id_) === index)
                   .map(item => ({
                     id:item.id,name:<Entity {...item}/>}))
               }}
               onChange={setChangedParty}
               changedItem={changedParty ?? null}
               initialItem={party}
             />}

        </td>
        <td className="border-l ps-2">
          <AmountEditForm
            {...{amount, changedValue:changedAmount,
                 setChangedValue:setChangedAmount,
                 chosenCurrency, setChosenCurrency,
                 onDenyCurrency: onFailInit}} />
        </td>
      </>)
})
