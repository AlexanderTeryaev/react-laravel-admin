import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import Checkbox from '@material-ui/core/Checkbox'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { addImportQuestion } from '../../services/importQuestion/importQuestionActions'
import { toastr } from 'react-redux-toastr'

class AddImportQuestion extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: false,
      delimiter: true
    }
    this.handleCreateClick = this.handleCreateClick.bind(this)
  }

  componentDidUpdate (prevProps) {
    if (
      !this.props.error &&
      this.props.questions.length != prevProps.questions.length
    ) {
      toastr.success('Success!', 'successfully created!')
      prevProps.history.push('/import/questions')
    }

    if (
      this.props.error &&
      !prevProps.error &&
      this.props.questions.length == prevProps.questions.length
    ) {
      var _errorStr = ''
      if (this.props.error.errors) {
        for (var key in this.props.error.errors) {
          for (var i = 0; i < this.props.error.errors[`${key}`].length; i++) {
            _errorStr = this.props.error.errors[`${key}`][i] + '\n'
          }
        }
      }
      toastr.error('Error', _errorStr)
    }
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  _handleCheckChange () {
    if (this.state.delimiter) {
      this.setState({delimiter:false})
    } else {
      this.setState({delimiter:true})
    }
  }

  handleCreateClick () {
    const { csv, delimiter, bg_url } = this.state

    if (!csv) {
      toastr.info('Required', 'Please select csv file')
      return
    }

    let _delimiter;
    if (delimiter) {
      _delimiter = ",";
    } else {
      _delimiter = ";"
    }

    var formData = new FormData()
    formData.append(`csv`, csv);
    formData.append(`delimiter`, _delimiter);
    if (bg_url) {
      formData.append(`bg_url`, bg_url);
    }
    this.props.importQuestionActions.addImportQuestion(formData)
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
          <Link color='inherit' href='/import/questions'>
            Question Imports
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
                <h5 className="mb-3">CSV</h5>
                <input
                  accept='txt/*'
                  type='file'
                  className='mb-4'
                  name='csv'
                  onChange={e => this._handleFileChange(e)}
                />
                <h5 className="mb-3">BG URL</h5>
                <input
                  accept='image/*'
                  type='file'
                  className='mb-4'
                  name='bg_url'
                  onChange={e => this._handleFileChange(e)}
                />
                <FormControlLabel
                  control={
                    <Checkbox
                      checked={this.state.delimiter}
                      onChange={() => this._handleCheckChange()}
                      color='primary'
                    />
                  }
                  label='Comma ","'
                />
                <FormControlLabel
                  control={
                    <Checkbox
                      checked={!this.state.delimiter}
                      onChange={() => this._handleCheckChange()}
                      color='primary'
                    />
                  }
                  label='Semicolon ";"'
                />
              </div>
              <div className='col-md-6'>
                <div className='w-100 mb-4 alert alert-success'>
                  For the import of questions here is some information that will be useful for you:
                  <ul>
                    <li>
                      It is recommended to use{' '}
                      <a href='https://www.google.fr/intl/fr/sheets/about/' target='_blank'>
                        Google docs
                      </a>{' '}
                      to create the CSV file
                    </li>
                    <li>
                      It is <b>absolutely</b> necessary to respect the number
                      and order of the columns, so it is better to use the
                      <a
                        href='https://docs.google.com/spreadsheets/d/144gGysyzty34AvtGjDNftcW4ok4n68grVXyrW9etG1s/edit?usp=sharing'
                        target='_blank'
                      >
                        template
                      </a>{' '}
                      provided
                    </li>
                    <li>
                      The rules regarding questions are the same as when you
                      enter them manually in the back office (size of the
                      question, answers...)
                    </li>
                    <li>
                      If your file contains errors, the import will be aborted
                      and you will have to correct all your errors before
                      sending it back.{' '}
                    </li>
                    <li>
                      Once the import is finished you will have to check the
                      formatted information before finalizing the import. If the
                      import is not finished the questions will not be available
                      on the application{' '}
                    </li>
                    <li>
                      The first line of the table is reserved for column
                      headings, i. e. the first line is not imported as a
                      question
                    </li>
                    <li>
                      If you use Google docs, you can leave the delimiter by
                      default, otherwise you must check the delimiter used by
                      your software and modify accordingly
                    </li>
                    <li>
                      If you import an image at the same time as your CSV the
                      imported image will be placed on all questions that do not
                      have an "Image URL".
                    </li>
                  </ul>
                </div>
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
    loading: state.default.services.importQuestion.loading,
    error: state.default.services.importQuestion.error,
    questions: state.default.services.importQuestion.questions
  }),
  dispatch => ({
    importQuestionActions: bindActionCreators({ addImportQuestion }, dispatch)
  })
)(AddImportQuestion)
