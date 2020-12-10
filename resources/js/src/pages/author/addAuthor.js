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
import { addAuthor, createAuthor } from '../../services/author/authorActions'
import { toastr } from 'react-redux-toastr';

class AddAuthor extends Component {
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
      this.props.authors.length != prevProps.authors.length
    ) {
      toastr.success('Success!', 'successfully created!');
      prevProps.history.push('/author/authors')
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.authors.length == prevProps.authors.length
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
    const { name, functionText, pic_url, fb_link, twitter_link, website_link, description } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (functionText == '' || !functionText) {
      toastr.info('Required','Please enter function');
      return;
    }

    if (pic_url == '' || !pic_url) {
      toastr.info('Required','Please select Image');
      return;
    }

    var formData = new FormData()
    formData.append(`name`, name)
    formData.append('function', functionText)
    formData.append('pic_url', pic_url)
    if (fb_link) {
      formData.append('fb_link', fb_link)
    }
    if (twitter_link) {
      formData.append('twitter_link', twitter_link)
    }
    if (website_link) {
      formData.append('website_link', website_link)
    }   
    
    formData.append('description', description)
    this.props.authorActions.addAuthor(formData)
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
          <Link color='inherit' href='/author/authors'>
          Authors
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
                label='Name'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='name'
                value={this.state.name || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <TextField
                label='Function'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='functionText'
                value={this.state.functionText || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <TextareaAutosize
                aria-label='description'
                rowsMin={10}
                placeholder='description'
                className='w-100 mb-4 p-2'
                variant='outlined'
                name='description'
                value={this.state.description || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
            </div>
            <div className='col-md-6'>
              <input
                accept='image/*'
                type='file'
                className='mb-4'
                name='pic_url'
                onChange={e => this._handleFileChange(e)}
              />
              <TextField
                label='Facebook Link'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='fb_link'
                value={this.state.fb_link || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <TextField
                label='Twitter Link'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='twitter_link'
                value={this.state.twitter_link || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <TextField
                label='Website Link'
                type='text'
                variant='outlined'
                className='w-100 mb-4'
                name='website_link'
                value={this.state.website_link || ''}
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
    loading: state.default.services.author.loading,
    error: state.default.services.author.error,
    authors: state.default.services.author.authors,
    author: state.default.services.author.author
  }),
  dispatch => ({
    authorActions: bindActionCreators({ addAuthor, createAuthor }, dispatch)
  })
)(AddAuthor)
