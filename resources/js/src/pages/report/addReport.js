import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { addReport, createReport } from '../../services/report/reportActions'
import { toastr } from 'react-redux-toastr';

class AddReport extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: false,
    }
    this.handleCreateClick = this.handleCreateClick.bind(this)
  }

  componentDidUpdate(prevProps) {    
    
    if (
      !this.props.error &&
      this.props.reports.length != prevProps.reports.length
    ) {
      toastr.success('Success!', 'successfully created!');
      prevProps.history.push('/report/reports')
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.reports.length == prevProps.reports.length
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

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleCreateClick () {
    const { question } = this.state

    if (question == '' || !question) {
      toastr.info('Required', 'Please enter question');
      return;
    }

    var formData = new FormData()
    formData.append('question', question)
    this.props.reportActions.addReport(formData)
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
          <Link color='inherit' href='/report/reports'>
          Reports
          </Link>
          <Typography color='textPrimary'>Create</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off' className='w-100 mt-5'>
          <div className='row w-100'>
            <div className='col-md-6'>
              <TextField
                label='question'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='question'
                value={this.state.question || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />  
            </div>
            <div className='col-md-6'>
              <input
                accept='image/*'
                type='file'
                className='mb-4'
                name='bg_url'
                onChange={e => this._handleFileChange(e)}
              />
            </div>
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
    loading: state.default.services.report.loading,
    error: state.default.services.report.error,
    reports: state.default.services.report.reports,
    report: state.default.services.report.report
  }),
  dispatch => ({
    reportActions: bindActionCreators({ addReport, createReport }, dispatch)
  })
)(AddReport)
