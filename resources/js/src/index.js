import React from 'react'
import ReactDOM from 'react-dom'
import App from './App'
import './index.css'

// ReactDOM.render(
//   <App />,
//   document.getElementById('root')
// )

if (document.getElementById('app')) {
  ReactDOM.render(<App />, document.getElementById('app'));
}
