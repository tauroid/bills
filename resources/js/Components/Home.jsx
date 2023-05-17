import React from 'react'
import { Link } from '@inertiajs/react'

import DummyEntities from '../Components/DummyEntities'
import LinkedUsers from '../Components/LinkedUsers'

export default function Home(props) {
  return (
    <div className="grow flex flex-col items-center">
      <h1 className="text-4xl font-bold mt-8">Home</h1>
      <h2 className="text-2xl font-bold mt-8">Welcome, {props.user_name}</h2>
      <div className="grow flex flex-col justify-center items-center">
        <div className="p-8 border-b">
        <LinkedUsers users={props.linkedUsers} linkingUri={props.linkingUri} />
        </div>
        <div className="p-8">
        <DummyEntities users={props.linkedUsers} entities={props.dummyEntities} />
        </div>
      </div>
      <Link href={`/settlements`}>Settlements</Link>
    </div>)
}
