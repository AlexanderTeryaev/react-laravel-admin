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
  getQuestions,
  deleteQuestion
} from '../../services/question/questionActions'
import { toastr } from 'react-redux-toastr'
import { getAllGroups } from '../../services/group/groupActions'
import InputLabel from '@material-ui/core/InputLabel'
import MenuItem from '@material-ui/core/MenuItem'
import FormControl from '@material-ui/core/FormControl'
import Select from '@material-ui/core/Select'


class Questions extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true,
      rowsPerPage: 5,
      currentPage: 0,
      searchText: '',
      sortBy: '',
      selectedGroup: 1
    }

    this.handleDelete = this.handleDelete.bind(this)
    this.handleChangePage = this.handleChangePage.bind(this)
    this.handleChangeRowsPerPage = this.handleChangeRowsPerPage.bind(this)
  }

  componentDidMount () {
    this.getAllList();
    this.props.groupActions.getAllGroups();
  }

  getAllList() {
    let params = {
      rowsPerPage: this.state.rowsPerPage,
      currentPage: this.state.currentPage,
      searchText: this.state.searchText,
      sortBy: this.state.sortBy,
      group: (this.state.selectedGroup-1)
    }
    this.props.questionActions.getQuestions(params)
  }

  getGroupName (id) {
    const result = this.props.allGroups.filter(group => group.id==id);
    if (result.length>0) {
      return(result[0].name);
    }
  }

  _handleSelectGroupChange (e) {
    this.setState({
      selectedGroup: e.target.value
    }, ()=>{
      this.getAllList();
    })
  }

  renderGroupOptions () {
    let allGroups = [{
      id: 1,
      name: 'All'
    }]
    this.props.allGroups.map((item)=>{
      allGroups.push({
        id: item.id + 1,
        name: item.name
      })
    })
    return allGroups.map((dt, i) => {
      return (
        <MenuItem
          label='Select Group'
          value={dt.id}
          key={i}
          name={dt.name}
        >
          {dt.name}
        </MenuItem>
      )
    })
  }

  handleDelete (id) {
    this.props.questionActions.deleteQuestion(id)
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
      this.getAllList();
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
    this.setState({ currentPage: page }, ()=>{
      this.getAllList();
    })
  }

  handleChangeRowsPerPage (event) {
    const rowsPerPage = event.target.value
    this.setState({
      rowsPerPage: rowsPerPage
    }, ()=>{
      this.getAllList()
    })
  }

  _handleTextFieldChange (e) {
    this.setState({
      [e.target.name]: e.target.value
    }, ()=>{
      this.getAllList();
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
          <Typography color='textPrimary'>Questions</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <Grid container spacing={1}>
            <Grid item xs={12} className='d-flex justify-content-between mb-4'>
              <div className="flex">
                <TextField
                  label='Search'
                  type='text'
                  className="mr-4"
                  name='searchText'
                  value={this.state.searchText || ''}
                  onChange={e => this._handleTextFieldChange(e)}
                />
                {this.props.allGroups.length>0 ? (
                  <FormControl variant='outlined' className='w-200 mr-4'>
                    <InputLabel id='group'>Group</InputLabel>
                    <Select
                      labelId='group'
                      id='group'
                      value={this.state.selectedGroup || ''}
                      onChange={e => this._handleSelectGroupChange(e)}
                      label='difficulty'
                    >
                      {this.renderGroupOptions()}
                    </Select>
                  </FormControl>
                ) : (
                  ''
                )}
              </div>              
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                to='/question/create/'
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
                      <TableCell>Group</TableCell>
                      <TableCell>Good Answer</TableCell>
                      <TableCell>Bad Answer</TableCell>
                      <TableCell align='center'>Bg</TableCell>
                      <TableCell align='center'>Difficulty</TableCell>
                      <TableCell align='center'>Status</TableCell>
                      <TableCell align='center'>Quizz</TableCell>
                      <TableCell align='center'>Author</TableCell>
                      <TableCell align='center'>More</TableCell>
                      <TableCell align='center'>Created</TableCell>
                      <TableCell>Action</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {this.props.questions.map(row => (
                      <TableRow key={row.id} hover>
                        <TableCell component='th' scope='row'>
                          {row.question ? row.question : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {this.props.allGroups ? this.getGroupName(row.group_id) : 'N/A'}
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
                        <TableCell>
                          {row.difficulty ? row.difficulty : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.status ? row.status : 'N/A'}
                        </TableCell>
                        <TableCell>
                          {row.quizz ? row.quizz.name : 'N/A'}
                        </TableCell>
                        <TableCell align='center'>
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
                          <IconButton aria-label='Edit'>
                            <Link to={'/question/edit/' + row.id}>
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
    questions: state.default.services.question.questions,
    total_count: state.default.services.question.total_count,
    loading: state.default.services.question.loading,
    reloading: state.default.services.question.reloading,    
    error: state.default.services.question.error,
    allGroups: state.default.services.group.allGroups,
  }),
  dispatch => ({
    questionActions: bindActionCreators({ getQuestions, deleteQuestion }, dispatch),
    groupActions: bindActionCreators({getAllGroups}, dispatch)
  })
)(Questions)