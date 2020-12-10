import React, { Component } from 'react'
import { Link } from 'react-router-dom'
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Button from '@material-ui/core/Button'
import EditIcon from '@material-ui/icons/Edit'
import Avatar from 'react-avatar'
import Alert from '@material-ui/lab/Alert'
import '../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { showShopAuthor } from '../../services/shopAuthor/shopAuthorActions'
import { toastr } from 'react-redux-toastr'

//For table

import { forwardRef } from 'react'
import Grid from '@material-ui/core/Grid'
import MaterialTable from 'material-table'
import AddBox from '@material-ui/icons/AddBox'
import ArrowDownward from '@material-ui/icons/ArrowDownward'
import Check from '@material-ui/icons/Check'
import ChevronLeft from '@material-ui/icons/ChevronLeft'
import ChevronRight from '@material-ui/icons/ChevronRight'
import Clear from '@material-ui/icons/Clear'
import DeleteOutline from '@material-ui/icons/DeleteOutline'
import Edit from '@material-ui/icons/Edit'
import FilterList from '@material-ui/icons/FilterList'
import FirstPage from '@material-ui/icons/FirstPage'
import LastPage from '@material-ui/icons/LastPage'
import Remove from '@material-ui/icons/Remove'
import SaveAlt from '@material-ui/icons/SaveAlt'
import Search from '@material-ui/icons/Search'
import ViewColumn from '@material-ui/icons/ViewColumn'

const tableIcons = {
  Add: forwardRef((props, ref) => <AddBox {...props} ref={ref} />),
  Check: forwardRef((props, ref) => <Check {...props} ref={ref} />),
  Clear: forwardRef((props, ref) => <Clear {...props} ref={ref} />),
  Delete: forwardRef((props, ref) => <DeleteOutline {...props} ref={ref} />),
  DetailPanel: forwardRef((props, ref) => (
    <ChevronRight {...props} ref={ref} />
  )),
  Edit: forwardRef((props, ref) => <Edit {...props} ref={ref} />),
  Export: forwardRef((props, ref) => <SaveAlt {...props} ref={ref} />),
  Filter: forwardRef((props, ref) => <FilterList {...props} ref={ref} />),
  FirstPage: forwardRef((props, ref) => <FirstPage {...props} ref={ref} />),
  LastPage: forwardRef((props, ref) => <LastPage {...props} ref={ref} />),
  NextPage: forwardRef((props, ref) => <ChevronRight {...props} ref={ref} />),
  PreviousPage: forwardRef((props, ref) => (
    <ChevronLeft {...props} ref={ref} />
  )),
  ResetSearch: forwardRef((props, ref) => <Clear {...props} ref={ref} />),
  Search: forwardRef((props, ref) => <Search {...props} ref={ref} />),
  SortArrow: forwardRef((props, ref) => <ArrowDownward {...props} ref={ref} />),
  ThirdStateCheck: forwardRef((props, ref) => <Remove {...props} ref={ref} />),
  ViewColumn: forwardRef((props, ref) => <ViewColumn {...props} ref={ref} />)
}

class ShopAuthorDetail extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
  }
  componentDidMount () {
    this.props.shopAuthorActions.showShopAuthor(this.props.match.params.id)
  }

  componentDidUpdate (prevProps) {
    if (
      this.props.loading == false &&
      prevProps.loading == true &&
      !this.props.error
    ) {
      this.setState({
        loading: false
      })
    }
    if (
      this.props.loading == false &&
      prevProps.loading == true &&
      this.props.error
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
    if (prevProps.status == false && this.props.status == true) {
      this.props.shopAuthorActions.showShopAuthor(this.props.match.params.id)
    }
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  render () {
    var quizzeColumns = [
      { title: 'Id', field: 'id', widht: 50 },
      {
        title: 'Quizz',
        width: 500,
        render: rowData => (
          <div>
            <Link to={'/quizz/detail/' + rowData.id} className='text-dark'>
              {rowData.name}
            </Link>
          </div>
        )
      },
      { title: 'Created', field: 'created_at', width: 500 }
    ]
    var questionColumns = [
      { title: 'Id', field: 'id', widht: 50 },
      {
        title: 'Quizz',
        width: 200,
        render: rowData => (
          <div>
            <Link
              to={'/quizz/detail/' + rowData.quizz.id}
              className='text-dark'
            >
              {rowData.quizz.name}
            </Link>
          </div>
        )
      },
      {
        title: 'Question',
        width: 400,
        render: rowData => (
          <div>
            <Link to={'/question/edit/' + rowData.id} className='text-dark'>
              {rowData.question}
            </Link>
          </div>
        )
      },
      { title: 'Created', field: 'created_at', width: 200 }
    ]
    return (
      <div className='p-5 w-100 mt-60'>
        <div>
          {this.state.iserror && (
            <Alert severity='error'>
              {this.state.errorMessages.map((msg, i) => {
                return <div key={i}>{msg}</div>
              })}
            </Alert>
          )}
        </div>
        <Breadcrumbs aria-label='breadcrumb' className='w-100 mb-4'>
          <Link color='inherit' to='/dashboard'>
            BO
          </Link>
          <Link color='inherit' to='/shop-author/authors'>
            Training Authors
          </Link>
          <Typography color='textPrimary'>Detail</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off'>
            <div className='d-flex justify-content-between mb-4'>
              <h4>Author: {this.props.shopAuthor.author.name}</h4>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                className=''
                to={'/shop-author/edit/' + this.props.match.params.id}
              >
                <EditIcon className='mr-2' />
                Edit
              </Button>
            </div>

            <div>
              <div className='row w-100'>
                <div className='col-md-6'>
                  <div className="row mb-3">
                    <img src={this.props.shopAuthor.pic} className='w-50' />
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>Name</div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.name}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>Function</div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.function}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Description
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.description}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>FB Link</div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.fb_link != 'None' ? (
                        <a href={this.props.shopAuthor.author.fb_link}>Go To</a>
                      ) : (
                        <a>No Link</a>
                      )}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Twitter Link
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.twitter_link != 'None' ? (
                        <a href={this.props.shopAuthor.author.twitter_link}>
                          Go To
                        </a>
                      ) : (
                        <a>No Link</a>
                      )}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Website Link
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopAuthor.author.website_link != 'None' ? (
                        <a href={this.props.shopAuthor.author.website_link}>
                          Go To
                        </a>
                      ) : (
                        <a>No Link</a>
                      )}
                    </div>
                  </div>
                </div>

                <div className='col-md-6'>
                  <Grid item xs={12} className='w-100'>
                    <MaterialTable
                      title='Author quizzes'
                      columns={quizzeColumns}
                      fixedHeader={false}
                      data={this.props.shopAuthor.quizzes}
                      icons={tableIcons}
                    />
                  </Grid>
                  <Grid item xs={12} className='w-100 mt-4'>
                    <MaterialTable
                      title='Author question'
                      columns={questionColumns}
                      fixedHeader={false}
                      data={this.props.shopAuthor.questions}
                      icons={tableIcons}
                    />
                  </Grid>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    )
  }
}

export default connect(
  state => ({
    error: state.default.services.shopAuthor.error,
    shopAuthor: state.default.services.shopAuthor.shopAuthor,
    loading: state.default.services.shopAuthor.loading,
    status: state.default.services.shopAuthor.status
  }),
  dispatch => ({
    shopAuthorActions: bindActionCreators(
      {
        showShopAuthor
      },
      dispatch
    )
  })
)(ShopAuthorDetail)
