import React from 'react'

import { Head } from '@inertiajs/react'

import Main from './Main'
import OtherUsersComponent from '../Components/OtherUsers'

export default function OtherUsers(props) {
  return (
    <>
      <Head><title>Other users</title></Head>
      <Main>
        <OtherUsersComponent {...props} />
      </Main>
    </>
  )
}
