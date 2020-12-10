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
import './editQuizz.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { editQuizz, updateQuizz } from '../../../services/quizz/quizzActions'
import Checkbox from '@material-ui/core/Checkbox'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import { toastr } from 'react-redux-toastr';

class EditQuizz extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
  }

  componentDidMount () {
    this.props.quizzActions.editQuizz(this.props.match.params.id)
  }

  componentDidUpdate (prevProps) {
    if (!this.props.error && this.props.quizz != prevProps.quizz) {
      let quizz = this.props.quizz.quizz
      let difficultyId
      this.props.quizz.difficulty_array.map((item, key) => {
        if (item == quizz.difficulty) {
          difficultyId = key
        }
      })
      this.setState({
        loading: false,
        name: quizz.name,
        enduroLimit: quizz.enduro_limit,
        is_published: quizz.is_published,
        // image_url: quizz.image_url,
        // default_questions_image: quizz.default_questions_image,
        author_id: quizz.author_id,
        description: quizz.description,
        tags: quizz.tags,
        difficulty: difficultyId,
        latitude: quizz.latitude,
        longitude: quizz.longitude,
        radius: quizz.radius
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      this.props.history.push('/quizz/quizzes')
      toastr.success('Success!', 'successfully updated!');
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
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

  _handleCheckChange (e) {
    this.setState({
      [e.target.name]: e.target.checked == true ? 1 : 0
    })
  }

  _handleSelectAuthorChange (e) {
    this.setState({
      author_id: e.target.value
    })
  }

  _handleSelectDifficultyChange (e) {
    this.setState({
      difficulty: e.target.value
    })
  }

  renderAuthorOptions () {
    var output = Object.entries(this.props.quizz.authors).map(
      ([key, value]) => ({ key, value })
    )
    return output.map((dt, i) => {
      return (
        <MenuItem
          label='Select an author'
          value={dt.key}
          key={i}
          name={dt.value}
        >
          {dt.value}
        </MenuItem>
      )
    })
  }

  renderDifficultyOptions () {
    var output = Object.entries(this.props.quizz.difficulty_array).map(
      ([key, value]) => ({ key, value })
    )
    return output.map((dt, i) => {
      return (
        <MenuItem
          label='Select the difficulty of the Quizz'
          value={dt.key}
          key={i}
          name={dt.value}
        >
          {dt.value}
        </MenuItem>
      )
    })
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleUpdateClick () {
    const { name, enduroLimit, image_url, default_questions_image, author_id, description, tags, difficulty, latitude, longitude, radius } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (enduroLimit == '' || !enduroLimit) {
      toastr.info('Required','Please enter enduroLimit');
      return;
    }

    if (tags == '' || !tags) {
      toastr.info('Required','Please enter tags');
      return;
    }

    if (description == '' || !description) {
      toastr.info('Required','Please enter description');
      return;
    }

    if (difficulty == '' || !difficulty) {
      toastr.info('Required','Please enter difficulty');
      return;
    }

    var formData = new FormData()
    
    formData.append('name', name)
    formData.append('enduroLimit', enduroLimit)
    if (author_id) {
      formData.append('author_id', author_id)
    }
    formData.append('description', description)
    formData.append('tags', tags)
    formData.append('difficulty', difficulty)

    if (image_url) {
      formData.append('image_url', image_url)
    }    
    if (default_questions_image) {
      formData.append('default_questions_image', default_questions_image)
    }    
    if (latitude) {
      formData.append('latitude', latitude)
    }
    if (longitude) {
      formData.append('longitude', longitude)
    }    

    if (radius) {
      formData.append('radius', radius)
    }    
    
    this.props.quizzActions.updateQuizz(this.props.match.params.id, formData)
    
  }

  render () {
    return (
      <div className='mt-70 p-4 w-100'>
        <div>
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
          <Link color='inherit' href='/quizz/quizzes'>
            Quizzes
          </Link>
          <Typography color='textPrimary'>Edit</Typography>
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
                  label='Name'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='name'
                  value={this.state.name || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                <TextField
                  label='EnduroLimit'
                  type='number'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='enduroLimit'
                  value={this.state.enduroLimit || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                <input
                  accept='image/*'
                  type='file'
                  className='mb-4'
                  name='image_url'
                  onChange={e => this._handleFileChange(e)}
                />
                <input
                  accept='image/*'
                  type='file'
                  className='mb-4'
                  name='default_questions_image'
                  onChange={e => this._handleFileChange(e)}
                />
                {this.props.quizz ? (
                  <FormControl variant='outlined' className='mb-4'>
                    <InputLabel id='author'>Author</InputLabel>
                    <Select
                      labelId='author'
                      id='author'
                      value={this.state.author_id || ''}
                      onChange={e => this._handleSelectAuthorChange(e)}
                      label='author'
                    >
                      {this.renderAuthorOptions()}
                    </Select>
                  </FormControl>
                ) : (
                  ''
                )}
                <FormControlLabel
                  control={
                    <Checkbox
                      checked={this.state.is_published == 1 ? true : false}
                      onChange={e => this._handleCheckChange(e)}
                      name='is_published'
                      color='primary'
                    />
                  }
                  label='Published'
                />
              </div>
              <div className='col-md-6'>
                <TextareaAutosize
                  aria-label='Description'
                  rowsMin={5.5}
                  placeholder='Description'
                  className='w-100 mb-4 p-2'
                  variant='outlined'
                  name='description'
                  value={this.state.description || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                <TextField
                  label='Tags'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='tags'
                  value={this.state.tags || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                {this.props.quizz && this.props.quizz.difficulty_array ? (
                  <FormControl variant='outlined' className='mb-4'>
                    <InputLabel id='difficulty'>Difficulty</InputLabel>
                    <Select
                      labelId='difficulty'
                      id='difficulty'
                      value={this.state.difficulty || ''}
                      onChange={e => this._handleSelectDifficultyChange(e)}
                      label='difficulty'
                    >
                      {this.renderDifficultyOptions()}
                    </Select>
                  </FormControl>
                ) : (
                  ''
                )}

                <TextField
                  label='Latitude'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='latitude'
                  value={this.state.latitude || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                <TextField
                  label='Longitude'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='longitude'
                  value={this.state.longitude || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                <TextField
                  label='Radius'
                  type='number'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='radius'
                  value={this.state.radius || ''}
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
                this.handleUpdateClick()
              }}
            >
              Update
            </Button>
          </div>
        )}
      </div>
    )
  }
}

export default connect(
  state => ({
    loading: state.default.services.quizz.loading,
    error: state.default.services.quizz.error,
    quizz: state.default.services.quizz.quizz,
    reloading: state.default.services.quizz.reloading
  }),
  dispatch => ({
    quizzActions: bindActionCreators({ editQuizz, updateQuizz }, dispatch)
  })
)(EditQuizz)
