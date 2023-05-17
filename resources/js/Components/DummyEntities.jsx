import React, { useContext, useState } from 'react'

import { useForm, Link, router } from '@inertiajs/react'

import { FullscreenModalStackContext } from '../Pages/Main'
import EditStamp from './EditStamp'
import Entity from './Entity'
import BinaryChoice from './BinaryChoice'

function AddPlaceholder() {
  const [name, setName] = useState('')

  const { popStack } = useContext(FullscreenModalStackContext)

  return (
    <div className="p-4">
      <form onSubmit={(e) => {
        e.preventDefault()
        router.post(`/api/dummy-entities`, {
          name
        }, {onSuccess: popStack})
      }}>
        <div className="border-b pb-1">
            <p>Create placeholder</p>
        </div>
        <div className="py-1 border-b w-full flex">
            <div className="grow text-left pe-2">
            <label htmlFor="addPlaceholderName">Name:</label>
            </div>
            <div>
            <input id="addPlaceholderName" type="text"
                    autoFocus
                    className="w-32"
                    onChange={(e)=>setName(e.target.value)} />
            </div>
        </div>
        <div className="pt-1">
            <button className="underline" type="submit">
              Confirm
            </button>
        </div>
      </form>
    </div>)
}

export default function DummyEntities(props) {
  const { pushModal, popModal, popStack } =
        useContext(FullscreenModalStackContext)

  function openAttachMenu(entity) {
    pushModal(
      <ul>
        {props.users.map((user, index)=>
          <li key={user.id} className={
            "p-2"+(index > 0 ? " border-t" : "")}>
            <Link method="post" as="button"
                  href={`/api/entity-attached-to-dummy/${entity.id}`
                        +`/${user.entityId}`}
                  onClick={popStack}>
              {user.name}
            </Link>
          </li>
        )}
        {entity.associatedUserName &&
         <li key="delete" className="p-2 border-t">
           <Link method="delete" as="button"
                 href={`/api/entity-attached-to-dummy/${entity.id}`}
                 onClick={popStack}>
             ❌
           </Link>
         </li>}
      </ul>)
  }

  const entities = props.entities.map((entity) =>
    <tr key={entity.id}>
      <td className="text-left">
        <Entity {...entity}/>
      </td>
      <td className="border-l">
      <EditStamp
        assignedStamp={null}
        showSingleOption={props.users.length === 0}
        options={[
          ...(props.users.length > 0 ? [{
            button: <span style={{textShadow:'1px 1px 1px black'}}>🪪</span>,
            name: 'Real user',
            action: ()=>openAttachMenu(entity)}] : []),
          {button: '🗑', name: 'Delete placeholder', action: ()=>{
            pushModal(
              <BinaryChoice
                message={
                  <div>
                    <p className="mb-2">
                      Are you sure?
                    </p>
                    <p className="mb-2">
                      You will lose control
                      of this placeholder, but it will
                      remain visible in all the transactions
                      it's currently part of.
                    </p>
                    {entity.associatedUserName &&
                     <p>
                       You might have meant to click the 'X'
                       under 'Real user', which deassociates
                       the current real user from this
                       placeholder.
                     </p>}
                  </div>
                }
                positive="Yes"
                onPositive={()=>{
                  router.delete(
                    `/api/dummy-entities/${entity.id}`,
                    {onSuccess: popStack}
                  )
                }}
                negative="No"
                onNegative={popModal}
              />
            )
          }}
        ]}
      />
      </td>
    </tr>)

  const { setData, post } = useForm({name: ''})

  function submit(e) {
    e.preventDefault()
    post('/api/dummy-entities')
  }

   return <div className="flex flex-col items-center">
           Placeholder users:
           <div className="border rounded mt-2">
             <table>
               <tbody>
                 {entities.length > 0 ? entities
                  : <tr>
                      <td colSpan="2">
                        <div className="max-w-xs">
                          <p className="my-2">
                            You don't have any placeholders!
                          </p>
                          <p className="mb-2">
                            You'll need a placeholder for any
                            person or organisation who doesn't
                            have an account on this website,
                            including e.g. restaurants, utility
                            companies you've paid money to.
                          </p>
                          <p className="mb-2">
                            If they do make an account, you can
                            always associate it to the
                            placeholder later.
                          </p>
                        </div>
                      </td>
                    </tr>}
                 <tr className="border-t">
                   <td className="cursor-pointer"
                       onClick={()=>{
                     pushModal(<AddPlaceholder/>)
                   }}>
                     <span className="underline">New</span>
                   </td>
                   <td className="border-l">
                     <EditStamp
                       options={[
                         {button: null, name: 'Add',
                          action: ()=>{
                            pushModal(<AddPlaceholder/>)
                          }}
                       ]}
                     />
                   </td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
}
