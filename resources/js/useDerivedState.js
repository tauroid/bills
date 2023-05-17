import { useCallback } from 'react'

export function getAtPath(obj, path) {
  try {
    return path.reduce((obj, index) => obj[index], obj)
  } catch {
    return undefined
  }
}

export function setAtPath(obj, path, newValue) {
  if (path.length === 0) return newValue(obj)
  else {
    if (!obj) obj = {}
    const index = path[0]
    const replacement =
      setAtPath(obj[index], path.slice(1), newValue)

    if (Array.isArray(obj)) {
      const newArray = [...obj]
      newArray[index] = replacement
      return newArray
    } else {
      return {...obj, [index]: replacement}
    }
  }
}

export default function useDerivedState(
  [state, setState], path,
  {callback, defaultValue, reactiveDependencies} = {}) {
  return [
    getAtPath(state, path) ?? defaultValue,
    useCallback(newValue => {
      setState(state => setAtPath(state, path, value => {
        if (typeof newValue === 'function') {
          value = newValue(value ?? defaultValue)
        } else {
          value = newValue
        }
        callback && callback(value, state)
        return value
      }))
    }, reactiveDependencies)
  ]
}
