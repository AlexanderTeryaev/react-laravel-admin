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
import TableCell from '@material-ui/core/TableCell'

import Alert from '@material-ui/lab/Alert'
import {
  getRecipients
} from '../../../services/recipient/recipientActions'
import { toastr } from 'react-redux-toastr'

class Recipients extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      rowsPerPage: 5,
      currentPage: 0,
      searchText: '',
      sortBy: ''
    }

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
    this.props.recipientActions.getRecipients(params)
  }

  componentDidUpdate (prevProps) {
    if (
      !this.props.error &&
      this.props.loading != prevProps.loading
    ) {
      this.setState({loading: false})
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
    this.props.recipientActions.getRecipients(params)
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
    this.props.recipientActions.getRecipients(params)
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
      this.props.recipientActions.getRecipients(params)
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
          <Typography color='textPrimary'>Recipients</Typography>
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
                to='/recipient/create/'
              >
                Create
              </Button>
            </Grid>
            <Grid item xs={12}>
              <Box>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Id</TableCell>
                      <TableCell>Email</TableCell>
                      <TableCell align='center'>Created</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {this.props.recipients.map(row => (
                      <TableRow key={row.id} hover>
                        <TableCell component='th' scope='row'>
                          {row.id ? row.id : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.email ? row.email : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.createdAt ? row.createdAt : 'N/A'}
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
    recipients: state.default.services.recipient.recipients,
    total_count: state.default.services.recipient.total_count,
    loading: state.default.services.recipient.loading,
    error: state.default.services.recipient.error
  }),
  dispatch => ({
    recipientActions: bindActionCreators(
      { getRecipients },
      dispatch
    )
  })
)(Recipients)