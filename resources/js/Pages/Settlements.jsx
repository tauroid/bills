import { Head } from '@inertiajs/react'
import React from 'react'

import SettlementsComponent from '../Components/Settlements'
import Main from './Main'

export default function Settlements(props) {
  return (
    <>
      <Head><title>Settlements</title></Head>
      <Main><SettlementsComponent {...props} /></Main>
    </>
  )
}
