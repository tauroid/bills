import React from "react"
import { Head, Link, useForm } from '@inertiajs/react'

import BillsHeader from "../Components/BillsHeader"

export default function Signup(props) {
  const { data, setData, post } = useForm({
    email: '',
    name: '',
    password: '',
    password_confirmation: '',
  })

  function submit(e) {
    e.preventDefault()
    post(`/register`)
  }

  return (
    <>
      <Head><title>Sign up</title></Head>
      <main>
        <div className="m-auto w-fit flex">
          <div className="pe-3">
            <Link href={`/login`}>Login</Link>
          </div>
          <div className="border-l ps-3">Sign up</div>
        </div>
        <div className="h-full flex-col justify-center">
          <BillsHeader />
          <div className="mt-3">
            <form onSubmit={submit}>
              <p>
                <input className="text-center my-1"
                        type="email"
                        value={data.email}
                        onChange={e => setData('email',
                                               e.target.value)}
                        placeholder="Email" />
              </p>
              {props.errors?.email && <p>{props.errors.email}</p>}
              <p>
                <input className="text-center my-1"
                       type="text"
                       value={data.name}
                       onChange={e => setData('name',
                                              e.target.value)}
                       placeholder="Name" />
              </p>
              {props.errors?.name && <p>{props.errors.name}</p>}
              <p>
                <input className="text-center my-1"
                       type="password"
                       value={data.password}
                       onChange={e => setData('password',
                                              e.target.value)}
                       placeholder="Password" />
              </p>
              {props.errors?.password &&
               <p>{props.errors.password}</p>}
              <p>
                <input className="text-center my-1 mb-3"
                       type="password"
                       value={data.password_confirmation}
                       onChange={
                         e => setData('password_confirmation',
                                      e.target.value)}
                       placeholder="Confirm password" />
              </p>
              {props.errors?.password_confirmation &&
               <p>{props.errors.password_confirmation}</p>}
              <p>
                <input
                  type="submit"
                  className={
                    "border border-gray-500"
                    +" rounded-lg px-2 py-1" }
                  value="Sign up"/>
              </p>
            </form>
          </div>
        </div>
      </main>
    </>
  )
}
