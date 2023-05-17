import React, { useContext } from 'react'

import { FullscreenModalStackContext } from '../Pages/Main'
import Notification from './Notification'

export default function InconsistentTransaction() {
  const {popModal} = useContext(FullscreenModalStackContext)

  return (
    <Notification
        title="Inconsistent amounts"
        message={"The incoming, outgoing and total"
                  +" amounts aren't equal. This"
                  +" means some money is unaccounted"
                  +" for, or has appeared out of"
                  +" nowhere. Check your records"
                  +" carefully."}
        acknowledgeLabel="Okay"
        onAcknowledge={popModal}
      />)
}
