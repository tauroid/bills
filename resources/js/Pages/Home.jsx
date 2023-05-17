import React from 'react'

import { Head } from '@inertiajs/react'

import Main from './Main'
import HomeComponent from '../Components/Home'

export default function Home(props) {
  return (
    <>
      <Head><title>Home</title></Head>
      <Main>
        <HomeComponent {...props} />
      </Main>
    </>
  )
}
