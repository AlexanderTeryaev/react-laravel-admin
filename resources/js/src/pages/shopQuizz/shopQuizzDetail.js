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
import { showShopQuizz, updateImage } from '../../services/shopQuizz/shopQuizzActions'
import { toastr } from 'react-redux-toastr';

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

class ShopQuizzDetail extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
  }
  componentDidMount () {
    this.props.shopQuizzActions.showShopQuizz(this.props.match.params.id)
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
    if (prevProps.status == false && this.props.status == true) {
      this.props.shopQuizzActions.showShopQuizz(this.props.match.params.id)
    }
  }

  _handleFileChange (e) {
    this.setState({
      [e.target.name]: e.target.files[0]
    })
  }

  handleSubmitClick () {
    const { image_url } = this.state
    var formData = new FormData()
    formData.append(`image`, image_url)
    this.props.shopQuizzActions.updateImage(this.props.shopQuizz.quizz.id, formData)
  }

  render () {
    var columns = [
      { title: 'Id', field: 'id', widht: 50 },
      { title: 'Question', field: 'question', width: 700 },
      { title: 'Good', field: 'good_answer' },
      { title: 'Bad', field: 'bad_answer' },
      {
        title: 'Bg',
        render: rowData => (
          <Avatar maxInitials={1} size={40} round={true} src={rowData.bg_url} />
        )
      },
      {
        title: 'Action',
        render: rowData => (
          <div>
            <Link to={'/shopQuestion/edit/' + rowData.id} className='text-dark'>
              <EditIcon />
            </Link>
          </div>
        )
      }
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
          <Link color='inherit' to='/shopQuizz/shopQuizzes'>
          Training Quizzes
          </Link>
          <Typography color='textPrimary'>Detail</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off' className='shopQuizzDetail'>
            <div className='d-flex justify-content-between mb-4'>
        <h4>Quizz:{this.props.shopQuizz.quizz.name}</h4>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                className=''
                to={'/shopQuizz/edit/' + this.props.match.params.id}
              >
                <EditIcon className='mr-2' />
                Edit
              </Button>
            </div>

            <div>
              <div className='row w-100'>
                <div className='col-md-6'>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Description
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.description}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Difficulty
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.difficulty}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Author
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.author.name}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Status
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.status}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Training
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.trainingName}
                    </div>
                  </div>
                  <hr className='mb-4' />
                </div>
                <div className='col-md-3'>
                  {this.props.shopQuizz.quizz.bg_url ? (
                    <img
                      src={this.props.shopQuizz.quizz.bg_url}
                      className='row mb-3'
                    />
                  ) : (
                    ''
                  )}
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Created at
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.created_at}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Updated at
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.shopQuizz.quizz.updated_at}
                    </div>
                  </div>
                </div>
                <div className='col-md-3'>
                  <div className='mb-3 font-weight-bold'>
                    Update questions image
                  </div>
                  <div className='mb-3'>
                    By uploading an image here, all questions will have this
                    image.
                  </div>
                  <div className='mb-3 text-danger'>
                    Caution! This action is not reversible!
                  </div>
                  <div className='mb-3 font-weight-bold'>Image</div>
                  <input
                    accept='image/*'
                    type='file'
                    className='mb-4'
                    name='image_url'
                    onChange={e => this._handleFileChange(e)}
                  />
                  <Button
                    variant='contained'
                    color='primary'
                    component='span'
                    type='submit'
                    style={{ width: 100 }}
                    className='text-right'
                    onClick={() => {
                      this.handleSubmitClick()
                    }}
                  >
                    Submit
                  </Button>
                </div>
              </div>
              <div className='row w-100'>
                <Grid item xs={12} className='w-100 mt-5'>
                  <MaterialTable
                    title='Questions'
                    columns={columns}
                    fixedHeader={false}
                    data={this.props.shopQuizz.quizz.questions}
                    icons={tableIcons}
                  />
                </Grid>
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
    error: state.default.services.shopQuizz.error,
    shopQuizz: state.default.services.shopQuizz.shopQuizz,
    loading: state.default.services.shopQuizz.loading,
    status: state.default.services.shopQuizz.status
  }),
  dispatch => ({
    shopQuizzActions: bindActionCreators(
      {
        showShopQuizz,
        updateImage
      },
      dispatch
    )
  })
)(ShopQuizzDetail)
