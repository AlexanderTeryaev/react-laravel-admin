import React, { Component } from 'react'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Link from '@material-ui/core/Link'
import TextField from '@material-ui/core/TextField'
import TextareaAutosize from '@material-ui/core/TextareaAutosize'
import Button from '@material-ui/core/Button'
import { Multiselect } from 'multiselect-react-dropdown';
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { updateFeatured, editFeatured } from '../../services/featured/featuredActions'
import { toastr } from 'react-redux-toastr';

class EditFeatured extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      quizzes: []
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this);
    this.onSelect = this.onSelect.bind(this);
    this.onRemove = this.onRemove.bind(this)
  }

  componentDidMount () {
    this.props.featuredActions.editFeatured(this.props.match.params.id)
  }

  componentDidUpdate(prevProps) {    
    if (!this.props.error && this.props.featured != prevProps.featured) {
      let featured = this.props.featured.featured
      this.setState({
        loading: false,
        name: featured.name,
        order_id: featured.order_id,
        quizzes: featured.quizzes,
        description: featured.description
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      this.props.history.push('/featured/featureds');
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
 
  onRemove(selectedList, removedItem) {
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
    const { name, quizzes, description, order_id, pic_url } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (quizzes == '' || !quizzes) {
      toastr.info('Required','Please enter quizzes');
      return;
    }

    if (order_id == '' || !order_id) {
      toastr.info('Required','Please enter order');
      return;
    }

    var formData = new FormData();
    var _quizzes = [];
    if(quizzes && quizzes.length  > 0)
    {
      for(var i = 0; i < quizzes.length; i++){
        _quizzes.push( quizzes[i].id );
      }
    }
    formData.append(`name`, name)
    formData.append('quizzes', _quizzes)
    formData.append('description', description)
    formData.append('order_id', order_id)
    if (pic_url) {
      formData.append('pic_url', pic_url)
    }
    
    this.props.featuredActions.updateFeatured(this.props.match.params.id, formData)
    
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
          <Link color='inherit' href='/featured/featureds'>
            Featureds
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
                label='Order'
                type='number'
                variant='outlined'
                className='w-100 mb-4'
                name='order_id'
                value={this.state.order_id || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <input
                accept='image/*'
                type='file'
                className='mb-4'
                name='pic_url'
                onChange={e => this._handleFileChange(e)}
              />
              <Multiselect
                options={this.props.featured.quizzes}
                selectedValues={this.state.quizzes}
                onSelect={this.onSelect}
                onRemove={this.onRemove}
                displayValue="name"
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
    loading: state.default.services.featured.loading,
    error: state.default.services.featured.error,
    reloading: state.default.services.featured.reloading,
    featured: state.default.services.featured.featured
  }),
  dispatch => ({
    featuredActions: bindActionCreators({ updateFeatured, editFeatured }, dispatch)
  })
)(EditFeatured)
