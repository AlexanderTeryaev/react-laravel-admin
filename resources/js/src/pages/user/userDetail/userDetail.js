import React, { Component } from 'react'
import { Link } from 'react-router-dom'
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import CircularProgress from '@material-ui/core/CircularProgress'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import './userDetail.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import {
  showUser, leftGroup, trainingDoc
} from '../../../services/user/userActions'
import { toastr } from 'react-redux-toastr'
import EditIcon from '@material-ui/icons/Edit'

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

import DeleteIcon from '@material-ui/icons/Delete';
import GetAppIcon from '@material-ui/icons/GetApp';

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

class UserDetail extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
  }
  componentDidMount () {
    this.props.userActions.showUser(this.props.match.params.id)
  }
  UNSAFE_componentWillReceiveProps (nextProps) {
    if (nextProps.user) {
      this.setState({
        loading: false
      })
    }
  }

  componentDidUpdate (prevProps) {
    if (
      this.props.loading == false &&
      prevProps.loading == true &&
      !this.props.error
    ) {
      this.setState({
        loading : false
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
  }

  leftgroup (id) {
    this.props.userActions.leftGroup(this.props.user.user.id, id);
  }

  training_doc (id) {
    this.props.userActions.trainingDoc(this.props.user.user.id, id);
  }


  render () {
    var Usercolumns = [
      { title: 'id', field: 'pivot.id', hidden: true },
      {title: "Group", render: (rowData) => 
      <div>
        <Link to={ '/group/detail/' + rowData.id } className="text-dark">{rowData.name}</Link>
      </div>
      },
      { title: 'Method', field: 'pivot.method' },
      { title: 'Since', field: 'pivot.created_at' },
      {title: "Current", render: (rowData) => 
      <div>
        {
          this.props.user.user.current_group == rowData.id ? <div>true</div>
          : <div>false</div>
        }
      </div>
      },
      {title: "Current", render: (rowData) => 
      <div>
        {rowData.id !=1 ? 
        <div className='d-flex'>
          <DeleteIcon onClick={() => this.leftgroup(rowData.id)} className="text-dark"/>
          <GetAppIcon onClick={() => this.training_doc(rowData.id)} className="text-dark"/>
        </div>:
        <div></div>}
      </div>
      },      
    ];
    var Quizzescolumns = [
      { title: 'id', field: 'id', hidden: true },
      {title: "Group", render: (rowData) => 
      <div>
        <Link to={ '/group/detail/' + rowData.group_id } className="text-dark">{rowData.group_name}</Link>
      </div>
      },
      {title: "Quizz", render: (rowData) => 
      <div>
        <Link to={ '/quizz/detail/' + rowData.quizz_id } className="text-dark">{rowData.quizz_name}</Link>
      </div>
      },
      { title: 'Since', field: 'created_at' },      
    ];

    var Answerscolumns = [
      { title: 'id', field: 'id', hidden: true },
      {title: "Group", render: (rowData) => 
      <div>
        <Link to={ '/group/detail/' + rowData.id } className="text-dark">{rowData.group_name}</Link>
      </div>
      },
      {title: "Question", render: (rowData) => 
      <div>
        <Link to={ '/question/detail/' + rowData.question_id } className="text-dark">{rowData.question}</Link>
      </div>
      },
      {title: "Type", render: (rowData) => 
      <div>
        {rowData.is_enduro ? 'Enduro mode' : 'L.S./widget'}
      </div>
      },
      { title: 'Date', field: 'created_at' },
    ];
    return (
      <div className='p-5 w-100 mt-60'>
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
          <Link color='inherit' to='/user/users'>
            Users
          </Link>
          <Typography color='textPrimary'>Detail</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off' className='userDetail'>            
            <div className='text-right mb-4'>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                className='mr-5'
                to={'/user/edit/' + this.props.match.params.id}
              >
                <EditIcon className='mr-2' />
                Edit
              </Button>
            </div>
            
            <div>
              <div className='row w-100'>
                <div className='col-md-6'>
                  <h4 className='mb-4'>User# {this.props.match.params.id}</h4>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>Device-id</div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.device_id}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Username
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.username}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    First Name
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.first_name}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Last Name
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.last_name}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Bio
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.bio}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Email
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.email}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Platform
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.curr_os}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Last Ip
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.last_ip}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Last action
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.updated_at}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    Created at
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.created_at}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                    One Signal id
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.user.user.one_signal_id}
                    </div>
                  </div>
                  <hr className='mb-4' />
                  <h4 className='mb-4'>User groups</h4>
                  
                  <Grid item xs={12}>
                    <MaterialTable
                      title='User groups'
                      columns={Usercolumns}
                      data={this.props.user.groups}
                      icons={tableIcons}
                    />
                  </Grid>
                  
                  
                </div>
                <div className='col-md-6'>
                  <h4 className='mb-4'>User quizzes</h4>
                  <Grid item xs={12}>
                    <MaterialTable
                      title='User quizzes'
                      columns={Quizzescolumns}
                      data={this.props.user.quizzes}
                      icons={tableIcons}
                    />
                  </Grid>
                  <hr className='mb-4 mt-4' />
                  <h4 className='mb-4'>User answers</h4>
                  
                  <Grid item xs={12}>
                    <MaterialTable
                      title='User answers'
                      columns={Answerscolumns}
                      data={this.props.user.answers}
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
    error: state.default.services.user.error,
    user: state.default.services.user.user,
    loading: state.default.services.user.loading,
  }),
  dispatch => ({
    userActions: bindActionCreators(
      {
        showUser, leftGroup, trainingDoc
      },
      dispatch
    )
  })
)(UserDetail)
