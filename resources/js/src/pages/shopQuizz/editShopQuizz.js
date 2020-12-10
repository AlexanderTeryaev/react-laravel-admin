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
import {
  editShopQuizz,
  updateShopQuizz
} from '../../services/shopQuizz/shopQuizzActions'
import Checkbox from '@material-ui/core/Checkbox'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import { toastr } from 'react-redux-toastr'

class EditShopQuizz extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
  }

  componentDidMount () {
    this.props.shopQuizzActions.editShopQuizz(this.props.match.params.id)
  }

  componentDidUpdate (prevProps) {
    if (!this.props.error && this.props.shopQuizz != prevProps.shopQuizz) {
      let shopQuizz = this.props.shopQuizz.shopQuizz;
      let difficultyId
      this.props.shopQuizz.difficulty_array.map((item, key) => {
        if (item == shopQuizz.difficulty) {
          difficultyId = key
        }
      })
      
      this.setState({        
        name: shopQuizz.name,
        shop_training_id: shopQuizz.shop_training_id,
        description: shopQuizz.description,
        tags: shopQuizz.tags,
        difficulty: difficultyId,
        loading: false,
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      this.props.history.push('/shopQuizz/shopQuizzes')
      toastr.success('Success!', 'successfully updated!')
    }

    if (
      this.props.error &&
      !prevProps.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
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

  _handleSelectTrainingChange (e) {
    this.setState({
      shop_training_id: e.target.value
    })
  }

  _handleSelectDifficultyChange (e) {
    this.setState({
      difficulty: e.target.value
    })
  }

  renderTrainingOptions () {
    var output = Object.entries(this.props.shopQuizz.shopTrainings).map(
      ([key, value]) => ({ key, value })
    )
    return output.map((dt, i) => {
      return (
        <MenuItem
          label='Select a training'
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
    var output = Object.entries(this.props.shopQuizz.difficulty_array).map(
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
    const { name, image_url, shop_training_id, description, tags, difficulty } = this.state

    if (name == '' || !name) {
      toastr.info('Required', 'Please enter name');
      return;
    }
    // if (image_url == '' || !image_url) {
    //   toastr.info('Required', 'Please select Image');
    //   return;
    // }
    if (shop_training_id == '' || !shop_training_id) {
      toastr.info('Required', 'Please select Training');
      return;
    }
    if (tags == '' || !tags) {
      toastr.info('Required', 'Please enter tags');
      return;
    }
    if (difficulty == '' || !difficulty) {
      toastr.info('Required', 'Please enter difficulty');
      return;
    }

    var formData = new FormData()
    if (image_url) {
      formData.append(`image_url`, image_url)
    }    
    formData.append('name', name)
    formData.append('training_id', shop_training_id)
    formData.append('description', description)
    formData.append('tags', tags)
    formData.append('difficulty', difficulty)
    this.props.shopQuizzActions.updateShopQuizz(
      this.props.match.params.id,
      formData
    )
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
          <Link color='inherit' href='/shopQuizz/shopQuizzes'>
            Training Quizzes
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
                <input
                  accept='image/*'
                  type='file'
                  className='mb-4'
                  name='image_url'
                  onChange={e => this._handleFileChange(e)}
                />

                {this.props.shopQuizz && this.props.shopQuizz.shopTrainings ? (
                  <FormControl variant='outlined' className='mb-4'>
                    <InputLabel id='author'>Training</InputLabel>
                    <Select
                      labelId='training'
                      id='training'
                      value={this.state.shop_training_id || ''}
                      onChange={e => this._handleSelectTrainingChange(e)}
                      label='training'
                    >
                      {this.renderTrainingOptions()}
                    </Select>
                  </FormControl>
                ) : (
                  ''
                )}
                <TextField
                  label='Tags'
                  type='text'
                  variant='outlined'
                  className='w-100 mb-4'
                  name='tags'
                  value={this.state.tags || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                {this.props.shopQuizz &&
                this.props.shopQuizz.difficulty_array ? (
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
    loading: state.default.services.shopQuizz.loading,
    error: state.default.services.shopQuizz.error,
    shopQuizz: state.default.services.shopQuizz.shopQuizz,
    reloading: state.default.services.shopQuizz.reloading
  }),
  dispatch => ({
    shopQuizzActions: bindActionCreators(
      { editShopQuizz, updateShopQuizz },
      dispatch
    )
  })
)(EditShopQuizz)
