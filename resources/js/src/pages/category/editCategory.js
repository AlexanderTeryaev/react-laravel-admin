import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import { toastr } from 'react-redux-toastr';
import Button from '@material-ui/core/Button'
import { Multiselect } from 'multiselect-react-dropdown'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import {
  updateCategory,
  editCategory
} from '../../services/category/categoryActions'

class EditCategory extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      quizzes: []
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
    this.onSelect = this.onSelect.bind(this)
    this.onRemove = this.onRemove.bind(this)
  }

  componentDidMount () {
    this.props.categoryActions.editCategory(this.props.match.params.id)
  }

  componentDidUpdate (prevProps) {
    if (!this.props.error && this.props.category != prevProps.category) {
      let category = this.props.category.category
      this.setState({
        loading: false,
        name: category.name,
        order_id: category.order_id,
        quizzes: category.quizzes,
        description: category.description
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      this.props.history.push('/category/categories')
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

  onSelect (selectedList, selectedItem) {
    this.setState({
      quizzes: selectedList
    })
  }

  onRemove (selectedList, removedItem) {
    this.setState({
      quizzes: selectedList
    })
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleUpdateClick () {
    const { name, quizzes, logo_url } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }
    if (quizzes == '' || !quizzes) {
      toastr.info('Required','Please enter quizzes');
      return;
    }

      var formData = new FormData()
      var _quizzes = []
      if (quizzes && quizzes.length > 0) {
        for (var i = 0; i < quizzes.length; i++) {
          _quizzes.push(quizzes[i].id)
        }
      }
      formData.append(`name`, name)
      formData.append('quizzes', _quizzes)
      if (logo_url) {
        formData.append('pic_url', logo_url)
      }
      this.props.categoryActions.updateCategory(this.props.match.params.id, formData)
    
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
          <Link color='inherit' href='/category/categories'>
            Categories
          </Link>
          <Typography color='textPrimary'>Update</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off' className='w-60 mt-5'>
            <div className='row w-100 mb-4 ml-0'>
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
              <Multiselect
                options={this.props.category.quizzes}
                selectedValues={this.state.quizzes}
                onSelect={this.onSelect}
                onRemove={this.onRemove}
                displayValue='name'
              />
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
    loading: state.default.services.category.loading,
    error: state.default.services.category.error,
    reloading: state.default.services.category.reloading,
    category: state.default.services.category.category
  }),
  dispatch => ({
    categoryActions: bindActionCreators(
      { updateCategory, editCategory },
      dispatch
    )
  })
)(EditCategory)
