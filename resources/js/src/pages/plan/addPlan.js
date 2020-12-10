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
import { addPlan, createPlan } from '../../services/plan/planActions'
import { toastr } from 'react-redux-toastr';

class AddPlan extends Component {
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
      this.props.plans.length != prevProps.plans.length
    ) {
      toastr.success('Success!', 'successfully created!');
      prevProps.history.push('/plan/plans')
    }

    if (
      this.props.error && !prevProps.error &&
      this.props.plans.length == prevProps.plans.length
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
    const { name, users_limit, features, price, plan_id } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (users_limit == '' || !users_limit) {
      toastr.info('Required','Please enter users limit');
      return;
    }

    if (features == '' || !features) {
      toastr.info('Required','Please enter features');
      return;
    }

    if (price == '' || !price) {
      toastr.info('Required','Please enter price');
      return;
    }

    if (plan_id == '' || !plan_id) {
      toastr.info('Required','Please enter plan');
      return;
    }

    var formData = new FormData()
    formData.append(`name`, name)
    formData.append(`users_limit`, users_limit)
    formData.append(`features`, features)
    formData.append(`price`, price)
    formData.append(`plan_id`, plan_id)
    this.props.planActions.addPlan(formData)
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
          <Link color='inherit' href='/plan/plans'>
          Plans
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
              label='Name'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='name'
              value={this.state.name || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <TextField
              label='Features'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='features'
              value={this.state.features || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <div className="w-100 mb-4 alert alert-success">
              The price indicated here is for display only (on the portal), the price charged to the customer
              is the price indicated on the product in the <a href="https://dashboard.stripe.com/test/subscriptions/products" target="_blank">dashboard stripe</a>
            </div>
            <TextField
              label='Price'
              type='number'
              variant='outlined'
              className='w-100 mb-4'
              name='price'
              value={this.state.price || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <TextField
              label='Users Limit'
              type='number'
              variant='outlined'
              className='w-100 mb-4'
              name='users_limit'
              value={this.state.users_limit || ''}
              onChange={e => this._handleTextFieldChange(e)}
            />
            <div className="w-100 mb-4 alert alert-success">
            The plan_id corresponds to the name of the product in the <a href="https://dashboard.stripe.com/test/subscriptions/products" target="_blank">dashboard stripe</a>
            , if the product does not exist it must be created
            </div>
            <TextField
              label='Plan ID'
              type='text'
              variant='outlined'
              className='w-100 mb-4'
              name='plan_id'
              value={this.state.plan_id || ''}
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
    loading: state.default.services.plan.loading,
    error: state.default.services.plan.error,
    plans: state.default.services.plan.plans,
    plan: state.default.services.plan.plan
  }),
  dispatch => ({
    planActions: bindActionCreators({ addPlan, createPlan }, dispatch)
  })
)(AddPlan)
