import React, { useContext, useRef } from 'react'

import { Link, router } from '@inertiajs/react'

import BinaryChoice from './BinaryChoice'
import EditStamp from './EditStamp'
import { FullscreenModalStackContext } from '../Pages/Main'

export default function LinkedUsers(props) {
  const { pushModal, popModal, popStack } =
        useContext(FullscreenModalStackContext)

  const users = props.users.map((user) =>
    <tr key={user.id}>
      <td className="border-r">
        {user.name}
      </td>
      <td>
        <EditStamp
          showSingleOption
          options={[
            {button: '💥', name: 'Unlink', action: () => {
              pushModal(
                <BinaryChoice
                  message={ "Are you sure? You will have to"
                            +" coordinate with this user to"
                            +" relink your accounts"}
                  positive="Yes"
                  onPositive={()=>{
                    router.delete(`/api/linked-users/${user.id}`)
                    popStack()
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

  const linkUrlRef = useRef()

  return <div className="flex flex-col items-center">
           {users.length > 0 ?
            <>
              Linked users:
              <div className="border rounded mt-2">
                <table><tbody>{users}</tbody></table>
              </div>
            </>
            : 'No linked users'}
           <div className="flex items-center gap-2 mt-4">
           {props.linkingUri ?
            <>
              Linking URL:
              <input
                ref={linkUrlRef}
                className="w-32 border-2 rounded"
                type="text" readOnly
                value={location.origin + props.linkingUri}
              />
              <button
                onClick={()=>{
                  navigator.clipboard.writeText(
                    linkUrlRef.current.value
                  )
                  linkUrlRef.current.focus()
                  linkUrlRef.current.select()
                }}
                className="w-12 h-12 border rounded-xl text-3xl hover:border-blue-300">
                📋
              </button>
            </> :
            <>
              Click to get (time-limited) linking URL:
              <Link href={`/api/linking-uri`} method="post"
                    as="button"
                    className="w-12 h-12 border rounded-xl text-3xl hover:border-blue-300">
                📡
              </Link>
            </>}
           </div>
           <div className="mt-4 w-72">
              Send your linking URL to another
              user to link your accounts
           </div>

         </div>
}
