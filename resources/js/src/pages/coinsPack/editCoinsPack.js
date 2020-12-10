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
import { updateCoinsPack, editCoinsPack } from '../../services/coinsPack/coinsPackActions'
import { toastr } from 'react-redux-toastr';
import CoinsPacks from './CoinsPacks'

class EditCoinsPack extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
    }
    this.handleUpdateClick = this.handleUpdateClick.bind(this)
  }

  componentDidMount () {
    this.props.coinsPackActions.editCoinsPack(this.props.match.params.id)
  }

  componentDidUpdate(prevProps) {    
    if (!this.props.error && this.props.coinsPack != prevProps.coinsPack) {
      let coinsPack = this.props.coinsPack;
      this.setState({
        loading: false,
        name: coinsPack.name,
        price:coinsPack.price,
        coins_quantity:coinsPack.coins_quantity
      })
    }
    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      toastr.success('Success!', 'successfully updated!');
      prevProps.history.push('/coins-pack/packs')
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

  handleUpdateClick () {
    const { name, coins_quantity, price } = this.state

    if (name == '' || !name) {
      toastr.info('Required','Please enter name');
      return;
    }

    if (coins_quantity == '' || !coins_quantity) {
      toastr.info('Required','Please enter coins quantity');
      return;
    }

    if (price == '' || !price) {
      toastr.info('Required','Please enter price');
      return;
    }

    var formData = new FormData()
    formData.append(`name`, name)
    formData.append('coins_quantity', coins_quantity)
    formData.append('price', price)

    this.props.coinsPackActions.updateCoinsPack(this.props.match.params.id, formData)
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
          <Link color='inherit' href='/coins-pack/packs'>
          Coins Pack
          </Link>
          <Typography color='textPrimary'>Edit</Typography>
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
              label='Coins Quantity'
              type='number'
              variant='outlined'
              className='w-100 mb-4'
              name='coins_quantity'
              value={this.state.coins_quantity || ''}
              onChange={e => this._handleTextFieldChange(e)}
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
    loading: state.default.services.coinsPack.loading,
    error: state.default.services.coinsPack.error,
    coinsPacks: state.default.services.coinsPack.coinsPacks,
    coinsPack: state.default.services.coinsPack.coinsPack,
    reloading: state.default.services.coinsPack.reloading
  }),
  dispatch => ({
    coinsPackActions: bindActionCreators({ updateCoinsPack, editCoinsPack }, dispatch)
  })
)(EditCoinsPack)
