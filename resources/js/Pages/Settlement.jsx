import React from 'react'
import { Head } from '@inertiajs/react'

import SettlementComponent from '../Components/Settlement'
import Main from './Main'

export default function Settlement(props) {
  return (
    <>
      <Head><title>{props.name}</title></Head>
      <Main><SettlementComponent {...props} /></Main>
    </>
  )
}
