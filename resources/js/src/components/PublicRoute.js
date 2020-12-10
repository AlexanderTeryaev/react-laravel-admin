import React, { Fragment } from 'react'
import { Route, Redirect } from 'react-router-dom'

const isAuthenticated = () => {
  var currentUser = JSON.parse(localStorage.getItem('currentUser'))
  if (currentUser) {
    return true
  } else return false
}

const PublicRoute = ({ component: Component, ...rest }) => (
  <Route
    {...rest}
    render={props =>
      isAuthenticated() ? (
        <Redirect to={{ pathname: '/dashboard' }} />
      ) : (
        <Component {...props} />
      )
    }
  />
)

export default PublicRoute
