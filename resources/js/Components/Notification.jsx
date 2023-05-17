import React from 'react'

export default function Notification(
  {title, message, acknowledgeLabel, onAcknowledge}) {
  return (
    <div className="flex flex-col w-72 p-4 pb-0">
      <div className="text-xl font-bold mb-3">
        {title}
      </div>
      <div className="mb-3">
        {message}
      </div>
      <div className="border-t p-3 cursor-pointer"
           onClick={onAcknowledge}>
        {acknowledgeLabel}
      </div>
    </div>)
}
