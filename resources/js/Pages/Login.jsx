import React from "react"
import { Head, Link, useForm } from '@inertiajs/react'

import BillsHeader from "../Components/BillsHeader"

export default function Login(props) {
  const { data, setData, post } = useForm({
    email: '',
    password: '',
  })

  function submit(e) {
    e.preventDefault()
    post(`/login`)
  }

  return (
    <>
      <Head><title>Login</title></Head>
      <main className="main">
        <div className="m-auto w-fit flex">
          <div className="border-r pe-3">Login</div>
          <div className="ps-3">
            <Link href={`/register`}>Sign up</Link>
          </div>
        </div>
        <div className={ "grow flex flex-col"
                         +" justify-center" }>
          <BillsHeader />
          <div className="mt-3">
            <form onSubmit={submit}>
              <p><input className="text-center my-1" type="email" value={data.email} onChange={e => setData('email',e.target.value)} placeholder="Email" /></p>
              {props.errors?.email && <p>{props.errors.email}</p>}
              <p><input className="text-center my-1 mb-3" type="password" value={data.password} onChange={e => setData('password',e.target.value)} placeholder="Password" /></p>
              {props.errors?.password && <p>{props.errors.password}</p>}
              <p>
                <input
                  type="submit"
                  className={
                    "border border-gray-500"
                    +" rounded-lg px-2 py-1" }
                  value="Log in"/>
              </p>
            </form>
          </div>
        </div>
      </main>
    </>
  )
}
