import './bootstrap';

import React from "react"
import { render } from "react-dom"
import { createInertiaApp } from "@inertiajs/react"

createInertiaApp({
  resolve: async name => await import(`./Pages/${name}.jsx`),
  setup({ el, App, props }) {
    render(<App {...props} />, el)
  }
})
