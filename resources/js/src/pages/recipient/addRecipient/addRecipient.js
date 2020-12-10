import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import './addRecipient.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { addRecipient } from '../../../services/recipient/recipientActions'
import { toastr } from 'react-redux-toastr';

class AddRecipient extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
    this.handleCreateClick = this.handleCreateClick.bind(this);
  }
  
  componentDidUpdate(prevProps) {
    if (
      !this.props.error &&
      this.props.recipients.length != prevProps.recipients.length
    ) {
      prevProps.history.push('/recipient/recipients')
      toastr.success('Success!', 'successfully created!');
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.recipients.length == prevProps.recipients.length
    ) {
      var _errorStr = "";
      if(this.props.error.errors)
      {
        for(var key in this.props.error.errors)
        {
          for(var i = 0; i < this.props.error.errors[`${key}`].length; i++)
          {
            _errorStr = this.props.error.errors[`${key}`][i] + '\n';
          }
        }
      }
      toastr.error("Error", _errorStr);
    }
  }

  _handleTextFieldChange (e) {
    this.setState({
      [e.target.name]: e.target.value
    })
  }

  handleCreateClick () {
    let email = this.state.email;
    if (email == '' || !email) {
      toastr.info('Required','Please enter email');
      return;
    }
    let params = {'email': email};
    this.props.recipientActions.addRecipient(params);
  }

  render () {

    return (
      <div className="mt-70 p-4 w-100">
        <div>
          {this.props.error && (
            <Alert severity='error'>
              {[this.props.error.message].map((msg, i) => {
                return <div key={i}>{msg}</div>
              })}
            </Alert>
          )}
        </div>
        <Breadcrumbs aria-label='breadcrumb' className="w-100 mb-4">
          <Link color='inherit' href='/dashboard'>BO</Link>
          <Link color='inherit' href='/recipient/recipients'>Recipients</Link>
          <Typography color='textPrimary'>Create</Typography>
        </Breadcrumbs>
        
        <div noValidate autoComplete='off' className="w-90 mt-5">
          <div className='w-60'>
            <TextField
              label='Email'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='email'
              value={this.state.email || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <Button
              variant='contained'
              color='primary'
              component='span'
              type='submit'
              onClick={() => {
                this.handleCreateClick()
              }}
            >
              Creat
            </Button>
          </div>
        </div>
      </div>
    )
  }
}

export default connect(
  state => ({
    loading: state.default.services.recipient.loading,
    error: state.default.services.recipient.error,
    recipients: state.default.services.recipient.recipients,
  }),
  dispatch => ({
    recipientActions: bindActionCreators({ addRecipient }, dispatch)
  })
)(AddRecipient)