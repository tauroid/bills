import React, { useContext, useEffect, useLayoutEffect, useMemo, useRef, useState } from 'react'

import { FullscreenModalStackStateContext } from '../Pages/Main'

function Results({ data, onSelect }) {
  return (
    <div className="bg-white rounded"
         onClick={(e)=>e.stopPropagation()}>
      <div className="border-b">Results</div>
      {data.map(item =>
        <div key={item.id}>
          <button className="w-full" onClick={(e)=>{
            e.stopPropagation()
            onSelect(item)
          }}>{item.name}</button>
        </div>)}
    </div>
  )
}

function Search({ getResults, getResultsSynchronous, setData,
                  searchTerm, setSearchTerm }) {
  const onChangeSearchTerm = getResultsSynchronous
        ? (searchTerm) => setData(getResultsSynchronous(searchTerm))
        : (searchTerm) => getResults(searchTerm, setData)

  if (getResultsSynchronous) {
    useLayoutEffect(() => onChangeSearchTerm(searchTerm), [])
  } else {
    useEffect(() => onChangeSearchTerm(searchTerm), [])
  }

  return (
    <div className="bg-white rounded"
         onClick={(e)=>e.stopPropagation()}>
      <p>Search</p>
      <p>
        <input className="m-1 border" onChange={
          (e)=>{
            setSearchTerm(e.target.value)
            onChangeSearchTerm(e.target.value)
          }}
         value={searchTerm}
         autoFocus />
      </p>
    </div>
  )
}

export default function SearchBox(
  { getResults, getResultsSynchronous,
    onSelect })
{
  const [stackState, setStackState] = useContext(
    FullscreenModalStackStateContext)

  const stackStateKey = useRef(null)
  if (stackStateKey.current === null) {
    stackStateKey.current = Symbol()
  }

  const data = useMemo(
    () => stackState[stackStateKey.current]?.data ?? [],
    [stackState[stackStateKey.current]?.data])

  const setData = (data) => {
    setStackState(
      stackState => ({
        ...stackState,
        [stackStateKey.current]: {
          ...stackState[stackStateKey.current],
          data
        }
      }))
  }

  const searchTerm = useMemo(
    () => stackState[stackStateKey.current]?.searchTerm ?? '',
    [stackState[stackStateKey.current]?.searchTerm])

  const setSearchTerm = (searchTerm) => {
    setStackState(stackState => ({
      ...stackState,
      [stackStateKey.current]: {
        ...stackState[stackStateKey.current],
        searchTerm
      }}))
  }

  useEffect(() => () => {
    setStackState(stackState => {
      const newStackState = {...stackState}
      delete newStackState[stackStateKey.current]
      return newStackState
    })
  }, [])

  return (
    <div className="h-full flex flex-col justify-around m-auto">
      <Results {...{data, onSelect}} />
      <Search {...{getResults, getResultsSynchronous, setData,
                   searchTerm, setSearchTerm}} />
    </div>
  )
}
