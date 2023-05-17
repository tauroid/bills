// DEPRECATED
import React, { useMemo, useState, useContext, useRef } from 'react'
import { router } from '@inertiajs/react'

import { FullscreenModalStackContext } from '../Pages/Main'

import EditStamp from './EditStamp'

export default function ApiComboTable(
  {ApiSearchBoxComponent, searchUrl, apiContext,
   tableItems, stackStateKey,
   NewItemCells,
   changes, setChanges,
   editableIds, EditableCells,
   numExtraColumns
   }) {
  const { pushModal, popModal } = useContext(FullscreenModalStackContext)

  const [ editing, setEditing ] = useState(tableItems.map(_=>false))

  const newItemStampRef = useRef()

  const itemStampRefs = tableItems.map(()=>useRef())

  const rowContents =
    tableItems.map(({id,name,ExtraCells,extraData}, index)=>(
      editing[index]
      ? <EditableCells
          data={{id,name,extraData}}
          changedValue={changes.edit?.[id]}
          setChangedValue={(value)=>setChanges({
            ...changes,
            edit: {
              ...(changes.edit ?? {}),
              [id]: value
            }
          })}
        />
      : <>
          <td className="text-left pe-2">{name}</td>
          {ExtraCells &&
           <ExtraCells {...extraData}
                       editStampRef={itemStampRefs[index]}
                       enableEditing={()=>{
                          const newEditing = [...editing]
                          newEditing[index] = true
                          setEditing(newEditing)
                       }}
           />}
        </>
    ))

  const [newItemValue, setNewItemValue] = useState({})

  return (
    <div>
      <table className={"w-full"}>
        <tbody>

          {rowContents.map((contents,index) => {
            const id = tableItems[index].id
            return (
              <tr key={id}>
                {contents}
                <td className="text-right">
                  <EditStamp
                    ref={itemStampRefs[index]}
                    options={[
                      {button: '🗑', name: 'Delete',
                      action: ({popModal}) => {
                        setChanges?.call(null, {
                          ...changes,
                          delete: Array.from(
                            new Set([...(changes.delete ?? []), id]))
                        })
                        editableIds?.includes(id) && popModal()
                      }},
                      ...(editableIds?.includes(id) ? [
                        {button: '🖋', name: 'Edit',
                        action: ({popModal, setStamp}) => {
                          const newEditing = [...editing]
                          newEditing[index] = true
                          setEditing(newEditing)
                          popModal()
                        }}
                      ] : [])
                    ]}
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
          <tr>
            <NewItemCells {...{newItemValue, setNewItemValue, newItemStampRef}} />
            <td>
              <EditStamp
                ref={newItemStampRef}
                options={[
                  {button: <span className="text-green-500">
                              '✯'
                            </span>,
                    name: 'Add',
                    action: ({setStamp}) => {
                      pushModal(
                      <ApiSearchBoxComponent
                        url={searchUrl}
                        onSelect={({id})=>{
                          router.post(
                            addItemUrl, {
                              ...apiContext,
                              [addItemKey]: id},
                            {onSuccess: ({data})=>{
                              setStamp(null)
                              popModal()
                            }
                            })
                        }}
                        {...{stackStateKey}} />,
                      {background: false,
                        popAction: ()=>setStamp(null)})
                    }}
                ]}
              />
            </td>

          </tr>

        </tbody>
      </table>
    </div>
  )
}
