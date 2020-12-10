import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import { toastr } from 'react-redux-toastr'
import CircularProgress from '@material-ui/core/CircularProgress'
import DateFnsUtils from '@date-io/date-fns'
import { DatePicker, MuiPickersUtilsProvider } from '@material-ui/pickers'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import './editGroup.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { updateGroup, showGroup } from '../../../services/group/groupActions'

class EditGroup extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
  }
  componentDidMount () {
    this.props.groupActions.showGroup(this.props.match.params.id)
  }
  UNSAFE_componentWillReceiveProps(nextProps) {
    if(nextProps.group) {
        this.setState({
          name: nextProps.group.name,
          coins: nextProps.group.coins,
          users_limit: nextProps.group.users_limit,
          trial_ends_at: new Date(nextProps.group.trial_ends_at),
          loading: false
        });
    }
  }

  componentDidUpdate(prevProps) {
    if (this.props.isUpdate == false && prevProps.isUpdate == true && !this.props.error) {
      toastr.success('Success!', 'successfully updated!');
      prevProps.history.push('/group/groups')
    }
    if (
      !prevProps.error && this.props.error
    ) {
      toastr.error("Error", this.props.error.message ? this.props.error.message : '');
    }
  }

  handleDateChange (date) {
    this.setState({
      trial_ends_at: date
    })
  }

  _handleTextFieldChange (e) {
    this.setState({
      [e.target.name]: e.target.value
    })
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleUpdateClick () {
    const { name, coins, trial_ends_at, users_limit, logo_url } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }
    if (coins == '' || !coins) {
      toastr.info('Required','Please enter coins');
      return;
    }
    if (trial_ends_at == '' || !trial_ends_at) {
      toastr.info('Required','Please enter trial end date');
      return;
    }
    if (users_limit == '' || !users_limit) {
      toastr.info('Required','Please enter users limit');
      return;
    }
    

    let trial_ends_at_date
    if (trial_ends_at) {
      trial_ends_at_date = trial_ends_at.getFullYear() + '-' + trial_ends_at.getMonth() + '-' + trial_ends_at.getDate()
    } else {
      trial_ends_at_date = new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate()
    }

    
    var formData = new FormData();
    if (logo_url) {
      formData.append(`logo_url`, logo_url)
    }    
    formData.append('trial_ends_at', trial_ends_at_date)
    formData.append('name', name)
    formData.append('users_limit', users_limit)
    formData.append('coins', coins)
    
    this.props.groupActions.updateGroup(this.props.match.params.id, formData);
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
          <Link color='inherit' href='/group/groups'>Groups</Link>
          <Typography color='textPrimary'>Edit</Typography>
        </Breadcrumbs>
        {this.state.loading? <CircularProgress className="mt-5"/> : 
        <div noValidate autoComplete='off' className="w-90 mt-5">
          <div className='w-60'>
            <TextField
              label='Name'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='name'
              value={this.state.name || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <input
              accept='image/*'
              type='file'
              className='mb-4'
              name='logo_url'
              onChange={e => this._handleFileChange(e)}
            />
            <TextField
              label='Coins'
              type='number'
              variant='outlined'
              className='w-100 mb-4'
              name='coins'
              value={this.state.coins || 0}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <TextField
              label='Users Limit (0 for unlimited)'
              type='number'
              variant='outlined'
              className='w-100 mb-4'
              name='users_limit'
              value={this.state.users_limit || 0}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <MuiPickersUtilsProvider utils={DateFnsUtils}>
              <DatePicker
                label='Trial ends'
                inputVariant='outlined'
                value={this.state.trial_ends_at}
                onChange={date => this.handleDateChange(date)}
                className='w-100 mb-5'
              />
            </MuiPickersUtilsProvider>
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
    loading: state.default.services.group.loading,
    isUpdate: state.default.services.group.isUpdate,
    group: state.default.services.group.group,
    groups: state.default.services.group.groups,
    error: state.default.services.group.error,
  }),
  dispatch => ({
    groupActions: bindActionCreators({ updateGroup, showGroup }, dispatch)
  })
)(EditGroup)
