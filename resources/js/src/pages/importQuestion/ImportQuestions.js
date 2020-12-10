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

import {
  getImportQuestions,
  deleteImportQuestion,
  deleteImportQuestions,
  finishImportQuestions
} from '../../services/importQuestion/importQuestionActions'
import { toastr } from 'react-redux-toastr'

class ImportQuestions extends Component {
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
    this.handleDeleteAll = this.handleDeleteAll.bind(this)
    this.handleFinish = this.handleFinish.bind(this)
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
    this.props.importQuestionActions.getImportQuestions(params)
  }

  handleDelete (id) {
    this.props.importQuestionActions.deleteImportQuestion(id)
    toastr.success('Success!', 'successfully deleted!')
  }

  handleDeleteAll () {
    this.props.importQuestionActions.deleteImportQuestions()
  }

  handleFinish () {
    this.props.importQuestionActions.finishImportQuestions()
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
      this.props.importQuestionActions.getImportQuestions(params)
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
    this.props.importQuestionActions.getImportQuestions(params)
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
    this.props.importQuestionActions.getImportQuestions(params)
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
      this.props.importQuestionActions.getImportQuestions(params)
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
          <Typography color='textPrimary'>Question Imports</Typography>
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
                to='/import/create/'
              >
                Create
              </Button>
            </Grid>
            <Grid item xs={12}>
              <Box>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Question</TableCell>
                      <TableCell align='center'>"Good Answer</TableCell>
                      <TableCell align='center'>Bad Answer</TableCell>
                      <TableCell align='center'>Bg</TableCell>
                      <TableCell align='center'>Difficulty</TableCell>
                      <TableCell align='center'>Status</TableCell>
                      <TableCell align='center'>Quizz</TableCell>
                      <TableCell align='center'>Author</TableCell>
                      <TableCell align='center'>More</TableCell>
                      <TableCell align='center'>Created</TableCell>
                      <TableCell align='center'>Action</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {this.props.questions.map(row => (
                      <TableRow key={row.id} hover>
                        <TableCell component='th' scope='row'>
                          {row.question ? row.question : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.good_answer ? row.good_answer : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.bad_answer ? row.bad_answer : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          <Avatar
                            maxInitials={1}
                            size={40}
                            round={true}
                            src={row.bg_url}
                          />
                        </TableCell>
                        <TableCell align='center'>
                          {row.difficulty ? row.difficulty : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
                          {row.status ? row.status : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.quizz ? row.quizz.name : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.quizz && row.quizz.author ? row.quizz.author.name : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.more ? row.more : 'N/A'}
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
            <Grid item xs={12} className='d-flex mt-4'>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                className="mr-5"
                onClick={()=>this.handleFinish()}
              >
                Finish import
              </Button>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                onClick={()=>this.handleDeleteAll()}
              >
                Delete all records
              </Button>
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
    questions: state.default.services.importQuestion.questions,
    total_count: state.default.services.importQuestion.total_count,
    loading: state.default.services.importQuestion.loading,
    reloading: state.default.services.importQuestion.reloading,
    error: state.default.services.importQuestion.error
  }),
  dispatch => ({
    importQuestionActions: bindActionCreators(
      { getImportQuestions, deleteImportQuestion, deleteImportQuestions, finishImportQuestions },
      dispatch
    )
  })
)(ImportQuestions)