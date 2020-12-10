import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import { toastr } from 'react-redux-toastr'
import CircularProgress from '@material-ui/core/CircularProgress'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import './editUser.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { updateUser, showUser } from '../../../services/user/userActions'

class EditUser extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this);
  }
  componentDidMount () {
    this.props.userActions.showUser(this.props.match.params.id)
  }
  
  componentDidUpdate(prevProps) {
    if (this.props.loading == false && prevProps.loading == true && prevProps.user != this.props.user) {
      this.setState({
        loading: false,
        first_name: this.props.user.user.first_name,
        last_name: this.props.user.user.last_name
      });
    }

    if (!this.props.error && prevProps.isUpdate == true && this.props.isUpdate == false) {
      this.props.history.push('/user/users');
      toastr.success('Success!', 'successfully updated!');
    }
    if (this.props.error && prevProps.isUpdate == true && this.props.isUpdate == false) {
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

  handleUpdateClick () {
    const { first_name, last_name } = this.state;
    if (first_name == '' || first_name == null) {
      toastr.error('firt name is missing')
      return;
    }
    if (last_name == '' || last_name == null) {
      toastr.error('last name is missing');
      return;
    }

    let params = this.props.user.user;
    params.first_name = first_name;
    params.last_name = last_name
    
    this.props.userActions.updateUser(this.props.match.params.id, params);
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
          <Link color='inherit' href='/user/users'>Users</Link>
          <Typography color='textPrimary'>Edit</Typography>
        </Breadcrumbs>
        {this.state.loading? <CircularProgress className="mt-5"/> : 
        <div noValidate autoComplete='off' className="w-90 mt-5">
          <div className='w-60'>
            <TextField
              label='First Name'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='first_name'
              value={this.state.first_name || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <TextField
              label='Last Name'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='last_name'
              value={this.state.last_name || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <Button
              variant='contained'
              color='primary'
              component='span'
              type='submit'
              onClick={() => {
                this.handleUpdateClick()
              }}
            >
              Update
            </Button>
          </div>
        </div>
        }
      </div>
    )
  }
}

export default connect(
  state => ({
    loading: state.default.services.user.loading,
    isUpdate: state.default.services.user.isUpdate,
    user: state.default.services.user.user,
    error: state.default.services.user.error,
  }),
  dispatch => ({
    userActions: bindActionCreators({ updateUser, showUser }, dispatch)
  })
)(EditUser)
