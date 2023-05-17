import React from 'react'

import Notification from '../Components/Notification'

export default function genericApiErrorHandler(
  error, problem, onAcknowledge, {pushModal, popModal})
{
  if (!error.response) throw error;
  const onAcknowledgeInternal = () => {
    onAcknowledge && onAcknowledge()
    popModal()
  }
  if (error.response.data.type !== 'UserError')
  {
    pushModal(
      <Notification
        title={problem}
        message={
          "Unknown"
            +(()=>{
              switch (error.response.status) {
                case 400: return ' client'
                case 500: return ' server'
                default: return ''
              }
            })()
            +" error"}
        acknowledgeLabel="Okay"
        onAcknowledge={onAcknowledgeInternal}
      />,
      {popAction:onAcknowledge}
    )
  } else {
    pushModal(
      <Notification
        title={problem}
        message={error.response.data.message}
        acknowledgeLabel="Okay"
        onAcknowledge={onAcknowledgeInternal}
      />,
      {popAction:onAcknowledge}
    )
  }
}
