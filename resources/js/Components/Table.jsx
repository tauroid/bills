import React, { useState, useContext, useRef, useEffect, useMemo, useLayoutEffect } from 'react'

import { FullscreenModalStackContext } from '../Pages/Main'
import useDerivedState from '../useDerivedState'

import EditStamp from './EditStamp'

export default function Table(
  {tableItems,
   NewItemCellsInactive,
   newItemExtraData,
   newItemCellsInactiveExtraData,
   tableState:externalTableState,
   setTableState:setExternalTableState,
   changes, setChanges,
   editableIds, EditableCells,
   deletableIds
   }) {
  const { pushModal, popModal } =
        useContext(FullscreenModalStackContext)

  const [tableState, setTableState] = externalTableState
        ? useDerivedState(
          [externalTableState, setExternalTableState], [])
        : useState({})

  const setChangesFromEditCells = (editing, editValues) => {
    setChanges(changes => ({
      ...changes,
      edit: Object.fromEntries(
        editValues.filter((item,index) =>
          item !== null && editing[index]
        ))
    }))
  }

  const editingInitial = useRef(tableItems.map(_=>false))
  const [ editing, setEditing ] = useDerivedState(
    [tableState, setTableState], ['editing'],
    {callback:
     (editing, tableState) => setChangesFromEditCells(
       editing, tableState.editValues ?? editValues),
     defaultValue: editingInitial.current})

  const editValuesInitial = useRef(tableItems.map(_=>null))
  const [ editValues, setEditValues ] = useDerivedState(
    [tableState, setTableState], ['editValues'],
    {callback:
      (editValues, tableState) => setChangesFromEditCells(
        tableState.editing ?? editing, editValues),
      defaultValue: editValuesInitial.current})


  const itemStampRefs = useRef(tableItems.map(()=>null))

  const rowContents =
    tableItems.map(({id,name,ExtraCells,extraData}, index)=>(
      editing[index]
      ? <EditableCells
          data={{id,name,extraData}}
          changedValue={editValues[index]?.[1]}
          setChangedValue={(value)=>{
            setEditValues(editValues => {
              if (typeof value === 'function') {
                value = value(editValues[index]?.[1])
              }
              const newEditValues = [...editValues]
              newEditValues[index] = [id,value]
              return newEditValues
            })
          }}
        />
      : <>
          <td className="text-left pe-2">
            {!editableIds?.includes(id)
             ? name
             : <button onClick={()=>{
               if (!editableIds.includes(id)) return
               const newEditing = [...editing]
               newEditing[index] = true
               setEditing(newEditing)
               setChanges?.call(null, changes => ({
                 ...changes,
                 delete: changes.delete?.filter(
                   deleteId => id !== deleteId)
               }))
             }}>
                 {name}
               </button>
            }

          </td>
          {ExtraCells &&
           <ExtraCells
             {...extraData}
             disabled={!editableIds.includes(id)}
             enableEditing={()=>{
               const newEditing = [...editing]
               newEditing[index] = true
               setEditing(newEditing)
               setChanges?.call(null, changes => ({
                 ...changes,
                 delete: changes.delete?.filter(
                   deleteId => id !== deleteId)
               }))
             }}
           />}
        </>
    ))

  const setChangesFromNewItems =
    (newItemValues, newItemsActive) => {
      setChanges(changes => ({
        ...changes,
        newItems:
        newItemValues.filter((_, index) => newItemsActive[index])
      }))
    }

  const newItemValuesInitial = useRef([{}])
  const [newItemValues, setNewItemValues] = useDerivedState(
    [tableState, setTableState], ['newItemValues'],
    {callback:
      (newItemValues, tableState) => setChangesFromNewItems(
        newItemValues, tableState.newItemsActive ?? newItemsActive),
      defaultValue: newItemValuesInitial.current})

  const newItemsActiveInitial = useRef([false])
  const [newItemsActive, setNewItemsActive] = useDerivedState(
    [tableState, setTableState], ['newItemsActive'],
    {callback:
      (newItemsActive, tableState) => setChangesFromNewItems(
        tableState.newItemValues ?? newItemValues, newItemsActive),
      defaultValue: newItemsActiveInitial.current})

  const newItemSymbol = (
    <span className="text-green-500">
      '✯'
    </span>)

  const newItemsJustActivated = useRef([false])
  const newItemsOnActivateRefs = useRef([null])

  const newItemStampRefs = useRef([null])

  const lastVisibleItem = useMemo(() => {
    const index = [...newItemsActive].reverse().findIndex(
      value => value)//Object.keys(value).length > 0)
    if (index < 0) return 0
    else return newItemsActive.length-index
  }, [newItemsActive])

  useEffect(() => {
    newItemsActive.forEach((_,index) => {
      if (newItemsJustActivated.current[index]
          && newItemsOnActivateRefs.current[index])
      {
        newItemsJustActivated.current[index] = false
        newItemsOnActivateRefs.current[index].onActivate(
          ()=>{
            setNewItemsActive(newItemsActive => {
              const newNewItemsActive = [...newItemsActive]
              newNewItemsActive[index] = false
              return newNewItemsActive
            })
            newItemStampRefs.current[index].setStamp(null)
          }
        )
      }
    })
  })

  return (
    <div className="relative pb-2">
      <div className="px-4 pt-4 bg-white">
      <table className="w-full">
        <tbody>
          {rowContents.map((contents,index) => {
            const id = tableItems[index].id
            return (
              <tr key={id}>
                {contents}
                <td className="text-right">
                  <EditStamp
                    ref={el=>itemStampRefs.current[index]=el}
                    disabled={!editableIds?.includes(id)
                              && !deletableIds?.includes(id)}
                    options={[
                      ...(editableIds?.includes(id) ? [
                        {button: '🖋', name: 'Edit',
                        action: ({popModal}) => {
                          const newEditing = [...editing]
                          newEditing[index] = true
                          setEditing(newEditing)
                          popModal()
                        }}
                      ] : []),
                      ...((editableIds?.includes(id)
                           || deletableIds?.includes(id)) ? [
                        {button: '🗑', name: 'Delete',
                        action: ({popModal}) => {
                          setChanges?.call(null, {
                            ...changes,
                            delete: Array.from(
                              new Set(
                                [...(changes.delete ?? []), id]))
                          })
                          editableIds?.includes(id)
                            && popModal()
                        }}
                      ] : [])
                    ]}
                    assignedStamp={
                      (()=>{
                        if (changes.delete &&
                            changes.delete.indexOf(id) !== -1) {
                          return '🗑'
                        } else if (editing[index]) {
                          return '🖋'
                        } else return null
                      })()
                    }
                    onRemoveStamp={() => {
                      const newEditing = [...editing]
                      newEditing[index] = false
                      setEditing(newEditing)
                      setChanges?.call(null, {
                        ...changes,
                        delete: changes.delete?.filter(
                          deleteId => id !== deleteId)
                      })
                    }}/>
                </td>
              </tr>)})}
            {newItemValues.map((newItemValue, index) => {
              if (index > lastVisibleItem) return null

              const setNewItemSomething = (setArray) => (value) => {
                setArray(fromArray => {
                  if (typeof value === 'function') {
                    value = value(fromArray[index])
                  }
                  const newArray = [...fromArray]
                  newArray[index] = value
                  return newArray
                })
              }
              const setNewItemActive = setNewItemSomething(
                setNewItemsActive)
              const setNewItemActiveAndUpdateTable = (active) => {
                if (index === newItemsActive.length-1 && active) {
                  setNewItemValues(
                    newItemValues =>[...newItemValues, {}])
                  setNewItemsActive(newItemsActive => [
                    ...(newItemsActive.slice(0,-1)),
                    true, false
                  ])
                  newItemsJustActivated.current.push(false)
                  newItemsOnActivateRefs.current.push(null)
                  newItemStampRefs.current.push(null)
                } else {
                  setNewItemActive(active)
                }
              }
              const setNewItemValue = setNewItemSomething(
                setNewItemValues)
              return (
                <tr key={"new-"+index}>
                  {(NewItemCellsInactive && !newItemsActive[index])
                   ? <NewItemCellsInactive
                       {...{activateNewItem: () => {
                         setNewItemActiveAndUpdateTable(true)
                         newItemsJustActivated.current[index] = true
                       },
                            extraData: newItemCellsInactiveExtraData}}/>
                   : <EditableCells
                       ref={el=>newItemsOnActivateRefs.current[index]=el}
                       {...{changedValue:newItemValue,
                            setChangedValue:(value) => {
                              setNewItemValue(value)
                              if (!newItemsActive[index]) {
                                setNewItemActiveAndUpdateTable(true)
                              }
                            },
                            onFailInit: () =>{
                              setNewItemActive(false)
                              setNewItemValue({})
                            }}}
                       data={{extraData: newItemExtraData,
                              allNewItems: newItemValues.filter(
                                (_,index)=>newItemsActive[index])
                             }}/>}
                  <td className="text-right">
                    <EditStamp
                      ref={el=>newItemStampRefs.current[index]=el}
                      options={[
                        {button: newItemSymbol,
                         name: 'Add',
                         action: () => {
                           setNewItemActiveAndUpdateTable(true)
                           newItemsJustActivated.current[index] = true
                         }}
                      ]}
                      assignedStamp={
                        newItemsActive[index]
                          ? newItemSymbol : null}
                      onRemoveStamp={
                        ()=>{
                          setNewItemActiveAndUpdateTable(false)
                          setNewItemValue({})
                        }}
                    />
                </td>
                </tr>
              )})}

        </tbody>
      </table>
      <div className="h-14"/>
      </div>
      <div className="h-16 absolute rounded w-full bottom-0"
           style={{background: 'linear-gradient(180deg, #CCC, white 30%, #444)',
                   boxShadow: '0 0 20px'}}
      />
    </div>
  )
}
