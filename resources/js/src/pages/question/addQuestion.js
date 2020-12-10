import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import InputLabel from '@material-ui/core/InputLabel'
import MenuItem from '@material-ui/core/MenuItem'
import FormControl from '@material-ui/core/FormControl'
import Select from '@material-ui/core/Select'
import TextareaAutosize from '@material-ui/core/TextareaAutosize'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { addQuestion, createQuestion } from '../../services/question/questionActions'
import { toastr } from 'react-redux-toastr';

class AddQuestion extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      difficulty_array: ['EASY', 'MEDIUM', 'HARD']
    }
    this.handleCreateClick = this.handleCreateClick.bind(this)
  }

  componentDidMount () {
    this.props.questionActions.createQuestion()
  }

  componentDidUpdate(prevProps) {    
    if (!this.props.error && this.props.question != prevProps.question) {
      this.setState({
        loading: false
      })
    }
    if (
      !this.props.error &&
      this.props.questions.length != prevProps.questions.length
    ) {
      toastr.success('Success!', 'successfully created!');
      prevProps.history.push('/question/questions')
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.questions.length == prevProps.questions.length
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

  _handleSelectDiffChange (e) {
    this.setState({
      difficulty: e.target.value
    })
  }

  _handleSelectQuizzesChange (e) {
    this.setState({
      quizz_id: e.target.value
    })
  }

  _handleSelectAuthorsChange (e) {
    this.setState({
      author_id: e.target.value
    })
  }

  renderDiffOptions () {
    var output = Object.entries(this.state.difficulty_array).map(
      ([key, value]) => ({ key, value })
    )
    return output.map((dt, i) => {
      return (
        <MenuItem
          label='Select the difficulty of the Question'
          value={dt.key}
          key={i}
          name={dt.value}
        >
          {dt.value}
        </MenuItem>
      )
    })
  }

  renderQuizzesOptions () {
    return this.props.question.quizzes.map((dt, i) => {
      return (
        <MenuItem
          label='Select Quizz'
          value={dt.id}
          key={i}
          name={dt.name}
        >
          {dt.name}
        </MenuItem>
      )
    })
  }

  renderAuthorsOptions () {
    return this.props.question.authors.map((dt, i) => {
      return (
        <MenuItem
          label='Select Author'
          value={dt.id}
          key={i}
          name={dt.name}
        >
          {dt.name}
        </MenuItem>
      )
    })
  }
  

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleCreateClick () {
    const { question, bad_answer, good_answer, difficulty, author_id, quizz_id, more, bg_url } = this.state

    if (question == '' || !question) {
      toastr.info('Required', 'Please enter question');
      return;
    }

    if (good_answer == '' || !good_answer) {
      toastr.info('Required', 'Please enter good answer');
      return;
    }

    if (bad_answer == '' || !bad_answer) {
      toastr.info('Required', 'Please enter bad answer');
      return;
    }

    if (difficulty == '' || !difficulty) {
      toastr.info('Required', 'Please select difficulty');
      return;
    }

    if (quizz_id == '' || !quizz_id) {
      toastr.info('Required', 'Please select quizz');
      return;
    }

    if (author_id == '' || !author_id) {
      toastr.info('Required', 'Please select author');
      return;
    }

    if (!bg_url) {
      toastr.info('Required', 'Please select image');
      return;
    }

    var formData = new FormData()
    formData.append(`bg_url`, bg_url)
    formData.append('question', question)
    formData.append('bad_answer', bad_answer)
    formData.append('author_id', author_id)
    formData.append('good_answer', good_answer)
    formData.append('difficulty', difficulty)
    formData.append('quizz_id', quizz_id)
    formData.append('more', more)
    this.props.questionActions.addQuestion(formData)
    
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
          <Link color='inherit' href='/question/questions'>
          Questions
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
              <TextField
                label='good_answer'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='good_answer'
                value={this.state.good_answer || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <TextField
                label='bad_answer'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='bad_answer'
                value={this.state.bad_answer || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />              
              {this.state.difficulty_array ? (
                <FormControl variant='outlined' className='mb-4'>
                  <InputLabel id='difficulty'>Difficulty</InputLabel>
                  <Select
                    labelId='difficulty'
                    id='difficulty'
                    value={this.state.difficulty || ''}
                    onChange={e => this._handleSelectDiffChange(e)}
                    label='difficulty'
                  >
                    {this.renderDiffOptions()}
                  </Select>
                </FormControl>
              ) : (
                ''
              )}
              
              {this.props.question && this.props.question.quizzes ? (
                <FormControl variant='outlined' className='mb-4'>
                  <InputLabel id='quizzes'>Quizzes</InputLabel>
                  <Select
                    labelId='quizzes'
                    id='quizzes'
                    value={this.state.quizz_id || ''}
                    onChange={e => this._handleSelectQuizzesChange(e)}
                    label='quizzes'
                  >
                    {this.renderQuizzesOptions()}
                  </Select>
                </FormControl>
              ) : (
                ''
              )}
              {this.props.question && this.props.question.authors ? (
                <FormControl variant='outlined' className='mb-4'>
                  <InputLabel id='authors'>Authors</InputLabel>
                  <Select
                    labelId='authors'
                    id='authors'
                    value={this.state.author_id || ''}
                    onChange={e => this._handleSelectAuthorsChange(e)}
                    label='authors'
                  >
                    {this.renderAuthorsOptions()}
                  </Select>
                </FormControl>
              ) : (
                ''
              )}
            </div>
            <div className='col-md-6'>
              <input
                accept='image/*'
                type='file'
                className='mb-4'
                name='bg_url'
                onChange={e => this._handleFileChange(e)}
              />
              <TextareaAutosize
                aria-label='More'
                rowsMin={10}
                placeholder='More'
                className='w-100 mb-4 p-2'
                variant='outlined'
                name='more'
                value={this.state.more || ''}
                onChange={e => this._handleTextFieldChange(e)}
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
    loading: state.default.services.question.loading,
    error: state.default.services.question.error,
    questions: state.default.services.question.questions,
    question: state.default.services.question.question
  }),
  dispatch => ({
    questionActions: bindActionCreators({ addQuestion, createQuestion }, dispatch)
  })
)(AddQuestion)
