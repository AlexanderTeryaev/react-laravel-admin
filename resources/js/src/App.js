import React, { Fragment } from 'react'
import store from './services/store';
import { Provider } from 'react-redux';
import  Navigation from './Route';
import ReduxToastr from 'react-redux-toastr';
import 'react-redux-toastr/lib/css/react-redux-toastr.min.css'
const App = props => {
  return (
    <Fragment>
      <Provider store={store}>
        <Navigation/>
        <ReduxToastr position="top-right" />
      </Provider>
    </Fragment>
  )
}

export default App;