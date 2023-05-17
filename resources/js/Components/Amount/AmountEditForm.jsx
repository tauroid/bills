import React, { useCallback, useContext, useLayoutEffect } from 'react'

import AmountGbpEditForm from './AmountGbpEditForm'
import AmountPercentageEditForm from './AmountPercentageEditForm'
import AmountShareEditForm from './AmountShareEditForm'
import SimpleChoice from '../SimpleChoice'
import {
  FullscreenModalStackContext,
  FullscreenModalStackStateContext
} from '../../Pages/Main'
import useDerivedState from '../../useDerivedState'

const absoluteCurrencies = [
  'gbp'
]

const currencyDescription = {
  gbp:  'GBP (£)',
  percentage: 'Proportion (%)',
  share: 'Share'
}

const currencyItem = (id) => ( {
  id, name: currencyDescription[id]
} )

const currencyEditForm = {
  'gbp': AmountGbpEditForm,
  'percentage': AmountPercentageEditForm,
  'share': AmountShareEditForm
}

const currencyEditFormInstance =
  (id, amount, changedValue, setChangedValue) => {
    const amount_id = 'amount_'+id
    const [currencyAmount, setCurrencyAmount] = useDerivedState(
      [changedValue, setChangedValue], [amount_id],
      {callback: () => setChangedValue(
        changedValue => ({...changedValue, currency: id})),
       reactiveDependencies: [id]})
    return React.createElement(currencyEditForm[id], {
      [amount_id]: amount?.[amount_id],
      changedValue: currencyAmount,
      setChangedValue: setCurrencyAmount
    })
}

export default function AmountEditForm(
  {amount, changedValue, setChangedValue,
   chosenCurrency, setChosenCurrency,
   onDenyCurrency, onlyAbsolute}) {

  const { pushModal, popModal, removeModal } = useContext(
    FullscreenModalStackContext)

  const currencyIsAbsolute = (currency) =>
        !['percentage','share'].includes(currency)

  useLayoutEffect(() =>{
    const currency =
      changedValue?.currency
      ?? amount?.currency
      ?? (onlyAbsolute ? chosenCurrency : 'share')

    if (currency && !amount && !changedValue) {
      setChangedValue({currency: currency})
    }
    if (!chosenCurrency && currencyIsAbsolute(currency)) {
      setChosenCurrency(currency)
    }

    if (!currency) {
      if (onlyAbsolute && absoluteCurrencies.length === 1) {
        setChosenCurrency(absoluteCurrencies[0])
        setChangedValue({currency: absoluteCurrencies[0]})
      } else {
        const modalId = pushModal(
          <SimpleChoice
            items={[
              ...absoluteCurrencies.map(currencyItem),
              ...(onlyAbsolute ? [] : [
                {separator: true},
                currencyItem('percentage'),
                currencyItem('share')
              ])]}
            prompt="Select type:"
            onSelect={(item)=>{
              if (currencyIsAbsolute(item.id)) {
                setChosenCurrency(item.id)
              }
              setChangedValue({currency: item.id})
              popModal({skipPopAction: true})
            }}/>,
          {popAction: onDenyCurrency})

        return () => {
          removeModal(modalId)
        }
      }
    }
  }, [])

  const currency =
        changedValue?.currency
        ?? amount?.currency

  if (!currency) {
    useCallback(()=>{/* hahaheehee */}, [currency])
    return "Select currency"
  }

  return (
    <div className="flex items-center whitespace-nowrap">
      {currencyEditFormInstance(
        currency, amount, changedValue, setChangedValue)}
      <button
        onKeyDown={(e)=>{
          if (e.key === 'Enter') {
            e.stopPropagation()
          }
        }}
        onClick={()=>{
        pushModal(
          <SimpleChoice
            items={[
              ...(chosenCurrency
                  ? [currencyItem(chosenCurrency)]
                  : absoluteCurrencies.map(currencyItem)),
              ...(onlyAbsolute ? [] : [
                {separator: true},
                {id: 'percentage', name: 'Proportion (%)'},
                {id: 'share', name: 'Share'}
              ])]}
            prompt="Select type:"
            onSelect={(item)=>{
              setChangedValue({currency: item.id})
              popModal()
            }}/>)
      }} className="ms-1 border-gray-500 border rounded px-2">
        ⋮
      </button>
    </div>);
}
