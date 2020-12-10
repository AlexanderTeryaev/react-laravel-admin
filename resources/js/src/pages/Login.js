import React, { Component } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { login } from '../services/auth/authActions'
import { toastr } from 'react-redux-toastr'
import '../../src/styles/common.scss'

class Login extends Component {
  constructor (props) {
    super(props)
    this.state = {
      email: null,
      password: null
    }
    this.change = this.change.bind(this)
    this.handleLoginClick = this.handleLoginClick.bind(this)
  }

  change (e) {
    this.setState({
      [e.target.name]: e.target.value
    })
  }

  handleLoginClick () {
    const { email, password } = this.state

    if (email === null || email === '') {
      toastr.error('email is missing', 'You must provide your email')
      return
    }

    this.props.authActions.login(email, password)
  }

  render () {
    return (
      <div className='login-contain'>
        <div className='row justify-content-center'>
          <div className='col-md-4'>
            <div className='card'>
              <div className='card-header'>Login</div>
              <div className='card-body'>
                <div className='form-group row'>
                  <label
                    htmlFor='email'
                    className='col-sm-4 col-form-label text-md-right'
                  >
                    Email Address
                  </label>
                  <div className='col-md-6'>
                    <input
                      id='email'
                      type='email'
                      className='form-control '
                      name='email'
                      onChange={this.change}
                    />
                  </div>
                </div>
                <div className='form-group row'>
                  <label
                    htmlFor='password'
                    className='col-md-4 col-form-label text-md-right'
                  >
                    Password
                  </label>
                  <div className='col-md-6'>
                    <input
                      id='password'
                      type='password'
                      className='form-control'
                      name='password'
                      onChange={this.change}
                    />
                  </div>
                </div>

                <div className='form-group row'>
                  <div className='col-md-6 offset-md-4'>
                    <div className='form-check'>
                      <input
                        className='form-check-input'
                        type='checkbox'
                        name='remember'
                        id='remember'
                      />

                      <label className='form-check-label' htmlFor='remember'>
                        Remember Me
                      </label>
                    </div>
                  </div>
                </div>
                <div className='form-group row mb-0'>
                  <div className='col-md-8 offset-md-4'>
                    <button
                      type='submit'
                      className='btn btn-primary'
                      onClick={() => {
                        this.handleLoginClick()
                      }}
                    >
                      Login
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }
}

export default connect(
  state => ({
    auth: state.default.services.auth
  }),
  dispatch => ({
    authActions: bindActionCreators({ login }, dispatch)
  })
)(Login)
