// DEPRECATED
import React, { useContext, useEffect, useMemo } from 'react'

import { FullscreenModalStackStateContext } from '../Pages/Main'

function Results({ data, onSelect }) {
  return (
    <div className="bg-white mt-16"
         onClick={(e)=>e.stopPropagation()}>
      Results slip
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

function Search({ url, apiContext, setData,
                  searchTerm, setSearchTerm }) {
  const fetchWithSearchTerm = (searchTerm) => {
    axios.post(url, {searchTerm, ...apiContext})
         .then(({data}) => setData(data))
  }

  useEffect(() => fetchWithSearchTerm(searchTerm), [])

  return (
    <div className="bg-white mb-16"
         onClick={(e)=>e.stopPropagation()}>
      <p>Search slip</p>
      <p>
        <input onChange={
          (e)=>{
            setSearchTerm(e.target.value)
            fetchWithSearchTerm(e.target.value)}}
         value={searchTerm} />
      </p>
    </div>
  )
}

export default function ApiSearchBox(
  { url, apiContext, onSelect, stackStateKey })
{
  const [stackState, setStackState] = useContext(
    FullscreenModalStackStateContext)

  const data = useMemo(() => stackState[stackStateKey]?.data ?? [],
                       [stackState[stackStateKey]?.data])

  const setData = (data) => {
    setStackState(
      stackState => ({
        ...stackState,
        [stackStateKey]: {
          ...stackState[stackStateKey],
          data
        }
      }))
  }

  const searchTerm = useMemo(() => stackState[stackStateKey]?.searchTerm ?? '',
                             [stackState[stackStateKey]?.searchTerm])

  const setSearchTerm = (searchTerm) => {
    setStackState(stackState => ({
      ...stackState,
      [stackStateKey]: {
        ...stackState[stackStateKey],
        searchTerm
      }}))
  }

  return (
    <div className="h-full flex flex-col justify-between">
      <Results {...{data, onSelect}} />
      <Search {...{url, apiContext, setData,
                   searchTerm, setSearchTerm}} />
    </div>
  )
}
