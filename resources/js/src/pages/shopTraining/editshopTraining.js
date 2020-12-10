import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import TextareaAutosize from '@material-ui/core/TextareaAutosize'
import Button from '@material-ui/core/Button'
import InputLabel from '@material-ui/core/InputLabel'
import MenuItem from '@material-ui/core/MenuItem'
import FormControl from '@material-ui/core/FormControl'
import Select from '@material-ui/core/Select'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import {
  updateShopTraining,
  editShopTraining
} from '../../services/shopTraining/shopTrainingActions'
import { toastr } from 'react-redux-toastr'

class EditShopTraining extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      quizzes: []
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
  }

  componentDidMount () {
    this.props.shopTrainingActions.editShopTraining(this.props.match.params.id);
  }

  componentDidUpdate (prevProps) {
    if (
      !this.props.error &&
      this.props.shopTraining != prevProps.shopTraining
    ) {
      this.setState({
        difficulty_array: this.props.shopTraining.difficulty_array
      })
    }
    if (!this.props.error && this.props.shopTraining != prevProps.shopTraining) {
      let shopTraining = this.props.shopTraining.shopTraining;
      let difficultyId
      this.props.shopTraining.difficulty_array.map((item, key) => {
        if (item == shopTraining.difficulty) {
          difficultyId = key
        }
      })
      let author_id;
      var output = Object.entries(this.props.shopTraining.shopAuthors).map(
        ([key, value]) => ({ key, value })
      )
      output.map((item, key) => {
        if (item.key == shopTraining.shop_author_id) {
          author_id = item.key
        }
      })
      this.setState({        
        name: shopTraining.name,
        subtitle: shopTraining.subtitle,
        price: shopTraining.price,
        description: shopTraining.description,
        tags: shopTraining.tags,
        difficulty_array: this.props.shopTraining.difficulty_array,
        difficulty: difficultyId,
        author_id: author_id,
        loading: false,
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      prevProps.history.push('/shopTraining/shopTrainings')
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

  _handleSelectDiffChange (e) {
    this.setState({
      difficulty: e.target.value
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

  renderAuthorsOptions () {
    var output = Object.entries(this.props.shopTraining.shopAuthors).map(
      ([key, value]) => ({ key, value })
    )
    return output.map((dt, i) => {
      return (
        <MenuItem label='Select Author' value={dt.key} key={i} name={dt.value}>
          {dt.value}
        </MenuItem>
      )
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
    const {
      name,
      subtitle,
      description,
      tags,
      image_url,
      difficulty,
      price,
      author_id
    } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (subtitle == '' || !subtitle) {
      toastr.info('Required','Please enter subtitle');
      return;
    }

    if (tags == '' || !tags) {
      toastr.info('Required','Please enter tags');
      return;
    }

    if (author_id == '' || !author_id) {
      toastr.info('Required','Please select author');
      return;
    }

    if (price == '' || !price) {
      toastr.info('Required','Please enter price');
      return;
    }

    var formData = new FormData()
    
    formData.append(`name`, name)
    formData.append('subtitle', subtitle)
    formData.append('description', description)
    formData.append('tags', tags)
    if (image_url) {
      formData.append('image_url', image_url)
    }    
    formData.append('difficulty', difficulty)
    formData.append('price', price)
    formData.append('author_id', author_id)
    this.props.shopTrainingActions.updateShopTraining(this.props.match.params.id,formData)
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
          <Link color='inherit' href='/shopTraining/shopTrainings'>
            Trainings
          </Link>
          <Typography color='textPrimary'>Update</Typography>
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
                  label='Subtitle'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='subtitle'
                  value={this.state.subtitle || ''}
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
                <input
                  accept='image/*'
                  type='file'
                  className='mb-4'
                  name='image_url'
                  onChange={e => this._handleFileChange(e)}
                />
                <TextField
                  label='Price'
                  type='number'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='price'
                  value={this.state.price || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
              </div>
              <div className='col-md-6'>
                <TextareaAutosize
                  aria-label='Description'
                  rowsMin={10}
                  placeholder='Description'
                  className='w-100 mb-4 p-2'
                  variant='outlined'
                  name='description'
                  value={this.state.description || ''}
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
                {this.props.shopTraining && this.props.shopTraining.shopAuthors ? (
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
    loading: state.default.services.shopTraining.loading,
    error: state.default.services.shopTraining.error,
    reloading: state.default.services.shopTraining.reloading,
    shopTraining: state.default.services.shopTraining.shopTraining
  }),
  dispatch => ({
    shopTrainingActions: bindActionCreators(
      { editShopTraining, updateShopTraining },
      dispatch
    )
  })
)(EditShopTraining)