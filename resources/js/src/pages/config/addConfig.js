import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import TextareaAutosize from '@material-ui/core/TextareaAutosize'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { addConfig, createConfig } from '../../services/config/configActions'
import { toastr } from 'react-redux-toastr';

class AddConfig extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: false,
    }
    this.handleCreateClick = this.handleCreateClick.bind(this)
  }

  componentDidMount () {
  }

  componentDidUpdate(prevProps) {    
    if (
      !this.props.error &&
      this.props.configs.length != prevProps.configs.length
    ) {
      toastr.success('Success!', 'successfully created!');
      prevProps.history.push('/config/configs')
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.configs.length == prevProps.configs.length
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
    const { key, value } = this.state

    if (key == '' || !key) {
      toastr.info('Required','Please enter key');
      return;
    }

    if (value == '' || !value) {
      toastr.info('Required','Please enter value');
      return;
    }

    var formData = new FormData()
    formData.append(`key`, key)
    formData.append('value', value)
    
    this.props.configActions.addConfig(formData)
  }

  render () {
    return (
      <div className='mt-70 p-4 w-100'>
        <div>
          {this.state.iserror && (
            <Alert severity='error'>
              {this.state.errorMessages.map((msg, i) => {
                return <div key={i}>{msg}</div>
              })}
            </Alert>
          )}
          {this.props.error && (
            <Alert severity='error'>
              {[this.props.error.message].map((msg, i) => {
                return <div key={i}>{msg}</div>
              })}
            </Alert>
          )}
        </div>
        <Breadcrumbs aria-label='breadcrumb' className='w-100 mb-4'>
          <Link color='inherit' href='/dashboard'>
            BO
          </Link>
          <Link color='inherit' href='/config/configs'>
          Configs
          </Link>
          <Typography color='textPrimary'>Create</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
        <div noValidate autoComplete='off' className='w-100 mt-5'>
          <div className='w-60'>
            <TextField
              label='Key'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='key'
              value={this.state.key || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <TextField
              label='Value'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='value'
              value={this.state.value || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
          </div>
          <Button
            variant='contained'
            color='primary'
            component='span'
            type='submit'
            onClick={() => {
              this.handleCreateClick()
            }}
          >
            Create
          </Button>
        </div>
        )}        
      </div>
    )
  }
}

export default connect(
  state => ({
    loading: state.default.services.config.loading,
    error: state.default.services.config.error,
    configs: state.default.services.config.configs,
    config: state.default.services.config.config
  }),
  dispatch => ({
    configActions: bindActionCreators({ addConfig, createConfig }, dispatch)
  })
)(AddConfig)
