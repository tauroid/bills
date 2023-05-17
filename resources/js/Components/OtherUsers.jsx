import React from 'react'

import { Link } from '@inertiajs/react'

import DummyEntities from '../Components/DummyEntities'
import LinkedUsers from '../Components/LinkedUsers'

export default function OtherUsers(props) {
  return (
    <div className="grow flex flex-col items-center">
      <Link href={`/home`}>Home</Link>
      <div className="grow flex flex-col gap-16 justify-center">
        <LinkedUsers users={props.linkedUsers} linkingUri={props.linkingUri} />
        <DummyEntities users={props.linkedUsers} entities={props.dummyEntities} />
      </div>
      <Link href={`/settlements`}>Settlements</Link>
    </div>
  )
}
