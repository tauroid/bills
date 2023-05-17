import { Head } from '@inertiajs/react'
import React from 'react'

export default function SettlementOwner(props) {
  return (
    <>
      <Head><title>Settlement</title></Head>
      <Main><SettlementComponent {...props} /></Main>
    </>
  )
}
