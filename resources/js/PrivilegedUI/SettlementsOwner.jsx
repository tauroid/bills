import React, { forwardRef, useContext, useMemo, useState } from 'react'
import axios from 'axios'
import { router, useForm, usePage } from '@inertiajs/react'

import { FullscreenModalStackContext } from '../Pages/Main'
import BinaryChoice from '../Components/BinaryChoice'
import Combobox from '../Components/Combobox'
import Table from '../Components/Table'
import { FullscreenModalShadowContext } from '../Components/FullscreenModalStack'

function ChangeOwner({settlement_id}) {
  const user_id = usePage().props.user_id
  const user_name = usePage().props.user_name

  const [selectedNewOwner, setSelectedNewOwner] =
        useState(user_id)

  const changingOwner = useMemo(
    () => selectedNewOwner !== user_id,
    [selectedNewOwner, user_id]
  )

  const [confirmed, setConfirmed] = useState(false)

  const { setData, post } = useForm({})

  const { pushModal, popModal, popStack } =
        useContext(FullscreenModalStackContext)

  return (
    <>
      <div className={ "absolute flex flex-col justify-around"
                       +" items-center w-full"
                       +" gap-2 cursor-pointer"
                       +" font-bold text-white"}
           style={{top: '-57px', zIndex: '1'}}
           onClickCapture={(e)=>{
             e.stopPropagation()
             if (!changingOwner) return
             pushModal(
               <BinaryChoice
                 message={"Are you sure? This is irreversible"
                         +" without the cooperation of the new"
                         +" owner!"}
                 positive="Yes"
                 onPositive={()=>{
                   post(`/api/settlement/${settlement_id}/assign-ownership`)
                   popStack()
                 }}
                 negative="No"
                 onNegative={popModal}
               />
             )
           }}
      >
        Change owner
        <div className={ "bg-white w-12 h-12 rounded-3xl"
                         +" text-3xl leading-12" }
             style={{boxShadow: '0 0 10px black',
                     paddingTop: '6px'}}
        >
          📮
        </div>
      </div>
      <div className="border p-1 m-4 mt-8">
        <Combobox
          initialItem={{id:user_id,name:user_name}}
          getSearchResults={(searchTerm, setData) => {
            axios.get(`/api/settlement/${settlement_id}/ownership-candidates`,
                      {params: {searchTerm}})
                 .then((response) => {setData(response.data)})
          }}
          onChange={({id}) => {
            setSelectedNewOwner(id)
            setData('user_id', id)
            setConfirmed(false)
          }}
        />
      </div>
    </>
  )
}

const SelectAdmin = forwardRef(
  function SelectAdmin({changedValue,setChangedValue,
                        data:{extraData:settlement_id,
                              allNewItems}}, ref)
  {
    return (
      <td className="text-left">
        <Combobox
          ref={ref}
          emptyText="Select admin"
          getSearchResults={(searchTerm, setData) => {
            axios.get(`/api/settlement/${settlement_id}/admin-candidates`,
                      {params:{searchTerm}})
                .then(response => {
                  const itemsById = Object.fromEntries(
                    response.data.map(item=>[item.id,item]))
                  for (const {id} of allNewItems) {
                    delete itemsById[id]
                  }
                  setData(Object.values(itemsById))})
          }}
          onChange={setChangedValue}
          noStamp={true}
        />
      </td>)
  }
)

function EditAdmins({settlement_id}) {
  const owner_data = usePage().props.settlements.find(
    settlement => settlement.id === settlement_id
  ).owner_data

  const [changes, setChanges] = useState({})
  const [tableState, setTableState] = useState({})

  return (
    <>
      <div className={ "absolute flex flex-col justify-around"
                       +" items-center w-full"
                       +" gap-2 cursor-pointer"
                       +" font-bold text-white"}
           style={{top: '-57px', zIndex: '1'}}
           onClickCapture={(e)=>{
             e.stopPropagation()
             axios.post(`/api/batch`,[
               ...(changes.delete ? [{
                 url: `/api/settlement/${settlement_id}/revoke-admin-privileges`,
                 type: 'POST',
                 body: JSON.stringify(
                   {admin_ids: changes.delete})
               }] : []),
               ...(changes.newItems ? [{
                 url: `/api/settlement/${settlement_id}/grant-admin-privileges`,
                 type: 'POST',
                 body: JSON.stringify(
                   {new_admin_ids: changes.newItems.map(
                     ({id})=>id)}
                 )
               }] : [])
             ]).then(() => {
               router.reload({
                 onSuccess: () => {
                   setChanges({})
                   setTableState({})
                 }
               })
             })
           }}>
        Update admins
        <div className={ "bg-white w-12 h-12 rounded-3xl"
                         +" text-3xl leading-12" }
             style={{boxShadow: '0 0 10px black',
                     paddingTop: '6px'}}
        >
          📮
        </div>
      </div>
      <div className="rounded overflow-clip">
      <Table
        tableItems={owner_data.admins}
        EditableCells={SelectAdmin}
        newItemExtraData={settlement_id}
        deletableIds={owner_data.admins.map(({id})=>id)}
        {...{changes,setChanges}}
        {...{tableState, setTableState}}
        NewItemCellsInactive={
          ({activateNewItem})=>
          <td className="text-left">
            <button className="text-left"
                    onClick={activateNewItem}>
              Select admin
            </button>
          </td>}
      />
      </div>
    </>)
}

function SettlementPermissionsModal({settlement_id}) {
  const shadow = useContext(FullscreenModalShadowContext)

  return (
    <div className={ "flex flex-col h-full justify-around"
                    +" items-center"}>
      <div className="relative bg-white rounded w-fit"
           {...(shadow ? {style: {
             boxShadow: '0 0 100px black'
           }} : {})}
           onClick={(e)=>e.stopPropagation()}>
        <ChangeOwner {...{settlement_id}} />
      </div>
      <div className="relative rounded w-fit"
           {...(shadow ? {style: {
             boxShadow: '0 0 100px black'
           }} : {})}
           onClick={(e)=>e.stopPropagation()}>
        <EditAdmins {...{settlement_id}} />
      </div>
    </div>
  )
}

const permissionsButton = (settlement_id) => ({
  button:'🔑',
  name: 'Permissions',
  action: ({pushModal, setStamp}) => {
    pushModal(
      <SettlementPermissionsModal
        {...{settlement_id}} />,
      {stackState: true,
       background: false,
       popAction: ()=>setStamp(null)})
  }
})

export default { permissionsButton }
