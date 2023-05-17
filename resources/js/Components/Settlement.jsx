import React, { useContext } from 'react'
import { Link } from '@inertiajs/react'
import ParticipantStatuses from './ParticipantStatuses'
import Transactions from './Transactions'

export default function Settlement(props) {
  return <div className="grow flex flex-col">
           <Link href={`/home`}>Home</Link>
           <Link href={`/settlements`}>Settlements</Link>
           <div className="my-8">
             <h1 className="text-4xl font-bold">{props.name}</h1>
           </div>
           <div className="mb-16 grow flex-col justify-center gap-16 items-center">
             {Object.keys(props.statuses).length > 0 &&
              <ParticipantStatuses {...props.statuses} />}
             <Transactions transactions={props.transactions} />
           </div>
         </div>
}
