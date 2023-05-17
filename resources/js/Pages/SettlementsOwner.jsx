import { Head } from '@inertiajs/react'
import React from 'react'

import Main from './Main'

import SettlementsComponent from '../Components/Settlements'
import ownerUI from '../PrivilegedUI/SettlementsOwner'

export default function SettlementsOwner(props) {
  return (
    <>
      <Head><title>Settlements</title></Head>
      <Main><SettlementsComponent {...props} ownerUI={ownerUI}/></Main>
    </>
  )
}
