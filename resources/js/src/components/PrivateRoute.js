import React from 'react'
import {  Route,  Redirect } from 'react-router-dom'
import {TopBar, SideMenu} from '.'

import { useState } from 'react'

const isAuthenticated = () => {
  var currentUser = JSON.parse(localStorage.getItem('currentUser'))
  if (currentUser) {
    return true
  } else return false
}

const PrivateRoute = ({ component: Component, path, ...rest }) => {
  const [drawerStatus, openDrawer] = useState(false)
  const toggleDrawer = () => {
    openDrawer(!drawerStatus)
  }
  return (
    <Route
      {...rest}
      render={props =>
        isAuthenticated() ? (
          <div>
            <TopBar position='static' toggleDrawer={toggleDrawer}></TopBar>
            <div>
              <div className='d-flex justify-content-center'>
                <div>
                  <SideMenu open={drawerStatus} toggleDrawer={toggleDrawer}
                   permissions={JSON.parse(localStorage.getItem('currentUser')).roles}
                  />
                </div>
                <Component {...props} className="main-container"
                 permissions={JSON.parse(localStorage.getItem('currentUser')).roles}
                />
              </div>
            </div>
          </div>
        ) : (
          <Redirect
            to={{ pathname: '/login', state: { from: props.location } }}
          />
        )
      }
    />
  )
}

export default PrivateRoute
