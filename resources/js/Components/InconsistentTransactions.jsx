import React, { useContext } from 'react'

import { FullscreenModalStackContext } from '../Pages/Main'
import Notification from './Notification'

export default function InconsistentTransactions() {
  const {popModal} = useContext(FullscreenModalStackContext)

  return (
    <Notification
        title="Inconsistent transactions"
        message={"Some transactions have inconsistent"
                 +" incomings, outgoings or totals."
                 +" This means that the participant statuses"
                 +" should be considered inaccurate until"
                 +" the problems are resolved."}
        acknowledgeLabel="Okay"
        onAcknowledge={popModal}
      />)
}
