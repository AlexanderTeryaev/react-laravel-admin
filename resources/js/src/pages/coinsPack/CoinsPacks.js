import React, { Component } from 'react'
import { Link } from 'react-router-dom'
import Button from '@material-ui/core/Button'
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import Grid from '@material-ui/core/Grid'
import TextField from '@material-ui/core/TextField'
import CircularProgress from '@material-ui/core/CircularProgress'
import Table from '@material-ui/core/Table'
import TableBody from '@material-ui/core/TableBody'
import TableHead from '@material-ui/core/TableHead'
import TablePagination from '@material-ui/core/TablePagination'
import TableRow from '@material-ui/core/TableRow'
import Box from '@material-ui/core/Box'
import Avatar from 'react-avatar'
import IconButton from '@material-ui/core/IconButton'
import TableCell from '@material-ui/core/TableCell'

import Alert from '@material-ui/lab/Alert'
import DeleteIcon from '@material-ui/icons/Delete'
import EditIcon from '@material-ui/icons/Edit'
import {
  getCoinsPacks,
  deleteCoinsPack
} from '../../services/coinsPack/coinsPackActions'
import { toastr } from 'react-redux-toastr'

class CoinsPacks extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      rowsPerPage: 5,
      currentPage: 0,
      searchText: '',
      sortBy: ''
    }

    this.handleDelete = this.handleDelete.bind(this)
    this.handleChangePage = this.handleChangePage.bind(this)
    this.handleChangeRowsPerPage = this.handleChangeRowsPerPage.bind(this)
  }

  componentDidMount () {
    let params = {
      rowsPerPage: this.state.rowsPerPage,
      currentPage: this.state.currentPage,
      searchText: this.state.searchText,
      sortBy: this.state.sortBy
    }
    this.props.coinsPackActions.getCoinsPacks(params)
  }

  handleDelete (id) {
    this.props.coinsPackActions.deleteCoinsPack(id)
  }

  componentDidUpdate (prevProps) {
    if (
      !this.props.error &&
      this.props.loading != prevProps.loading
    ) {
      this.setState({loading: false})
    }

    if (
      !this.props.error &&
      this.props.reloading == true &&
      prevProps.reloading == false
    ) {
      let params = {
        rowsPerPage: this.state.rowsPerPage,
        currentPage: this.state.currentPage,
        searchText: this.state.searchText,
        sortBy: this.state.sortBy
      }
      this.props.coinsPackActions.getCoinsPacks(params)
      toastr.success('Success!', 'successfully deleted!')
    }

    if (this.props.error && !prevProps.error) {
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

  handleChangePage (event, page) {
    this.setState({ currentPage: page })
    let params = {
      currentPage: page,
      rowsPerPage: this.state.rowsPerPage,
      searchText: this.state.searchText,
      sortBy: this.state.sortBy
    }
    this.props.coinsPackActions.getCoinsPacks(params)
  }

  handleChangeRowsPerPage (event) {
    const rowsPerPage = event.target.value
    this.setState({
      rowsPerPage: rowsPerPage
    })
    let params = {
      currentPage: this.state.currentPage,
      rowsPerPage: rowsPerPage,
      searchText: this.state.searchText,
      sortBy: this.state.sortBy
    }
    this.props.coinsPackActions.getCoinsPacks(params)
  }

  _handleTextFieldChange (e) {
    this.setState({
      [e.target.name]: e.target.value
    }, ()=>{
      let params = {
        currentPage: this.state.currentPage,
        rowsPerPage: this.state.rowsPerPage,
        searchText: this.state.searchText,
        sortBy: this.state.sortBy
      };
      this.props.coinsPackActions.getCoinsPacks(params)
    })
  }

  render () {
    return (
      <div className='p-4 w-100 mt-70'>
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
          <Link color='inherit' to='/dashboard'>
            BO
          </Link>
          <Typography color='textPrimary'>Coins Pack</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <Grid container spacing={1}>
            <Grid item xs={12} className='d-flex justify-content-between mb-4'>
              <TextField
                label='Search'
                type='text'
                name='searchText'
                value={this.state.searchText || ''}
                onChange={e => this._handleTextFieldChange(e)}
              />
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                to='/coins-pack/create/'
              >
                Create
              </Button>
            </Grid>
            <Grid item xs={12}>
              <Box>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Name</TableCell>
                      <TableCell align='center'>Coins Quantity</TableCell>
                      <TableCell align='center'>Price</TableCell>
                      <TableCell align='center'>Price</TableCell>
                      <TableCell align='center'>Updated</TableCell>
                      <TableCell align='center'>Created</TableCell>
                      <TableCell>Action</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {this.props.coinsPacks.map(row => (
                      <TableRow key={row.id} hover>
                        <TableCell component='th' scope='row'>
                          {row.name ? row.name : 'N/A'}
                        </TableCell >
                        <TableCell align='center'>
                          {row.coins_quantity ? row.coins_quantity : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.price ? row.price : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.price ? row.price : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.updatedAt ? row.updatedAt : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.createdAt ? row.createdAt : 'N/A'}
                        </TableCell>
                        <TableCell align='center' className='d-flex'>
                          <IconButton
                            aria-label='Delete'
                            color='primary'
                            onClick={() => this.handleDelete(row.id)}
                          >
                            <DeleteIcon />
                          </IconButton>
                          <IconButton aria-label='Edit'>
                            <Link to={'/coins-pack/edit/' + row.id}>
                              <EditIcon color='primary' />
                            </Link>
                          </IconButton>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </Box>

              <TablePagination
                style={{ overflow: 'auto' }}
                rowsPerPageOptions={[5, 10, 20]}
                component='div'
                count={this.props.total_count}
                rowsPerPage={this.state.rowsPerPage}
                page={this.state.currentPage}
                backIconButtonProps={{ 'aria-label': 'Previous Page' }}
                nextIconButtonProps={{ 'aria-label': 'Next Page' }}
                onChangePage={this.handleChangePage}
                onChangeRowsPerPage={this.handleChangeRowsPerPage}
              />
            </Grid>
            <Grid item xs={3}></Grid>
          </Grid>
        )}
      </div>
    )
  }
}

export default connect(
  state => ({
    coinsPacks: state.default.services.coinsPack.coinsPacks,
    total_count: state.default.services.coinsPack.total_count,
    loading: state.default.services.coinsPack.loading,
    reloading: state.default.services.coinsPack.reloading,
    error: state.default.services.coinsPack.error
  }),
  dispatch => ({
    coinsPackActions: bindActionCreators(
      { getCoinsPacks, deleteCoinsPack },
      dispatch
    )
  })
)(CoinsPacks)