import React, { Component } from 'react'
import { Link } from 'react-router-dom'
import CircularProgress from '@material-ui/core/CircularProgress'
// connection
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import {
  getDashboard,
  getUserInstallation,
  getQuestionsAnswers,
  getRepartition
} from '../services/dashboard/dashboardActions'
//Bread Crumbs
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
//Card
import Card from '@material-ui/core/Card'
import CardActions from '@material-ui/core/CardActions'
import CardContent from '@material-ui/core/CardContent'
import Button from '@material-ui/core/Button'

import PeopleAltIcon from '@material-ui/icons/PeopleAlt'
import ImageIcon from '@material-ui/icons/Image'
import QuestionAnswerIcon from '@material-ui/icons/QuestionAnswer'
import GroupWorkIcon from '@material-ui/icons/GroupWork'
import DateRangeIcon from '@material-ui/icons/DateRange'
import HelpIcon from '@material-ui/icons/Help'
//Table
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

//Chart

import {
  LineChart,
  ResponsiveContainer,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip
} from 'recharts'

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

class Dashboard extends Component {
  constructor (props) {
    super(props)
    this.state = {
      isLoading: true
    }
  }

  componentDidMount () {
    this.props.groupActions.getDashboard()
    this.props.groupActions.getUserInstallation()
    this.props.groupActions.getQuestionsAnswers()
    this.props.groupActions.getRepartition()
  }

  componentDidUpdate (prevProps) {
    if (
      this.props.loading == false &&
      prevProps.loading == true &&
      !this.props.error
    ) {
      let users = this.props.dashboard ? this.props.dashboard.users : [];
      for (let i = 0; i < users.length; i++) {
        users[i].groupsName = ''
        for (let j = 0; j < users[i].groups.length; j++) {
          if (j < users[i].groups.length - 1) {
            users[i].groupsName = users[i].groups[j].name + ','
          } else {
            users[i].groupsName = users[i].groups[j].name
          }
        }
      }
      this.setState({
        users: users,
        isLoading: false
      })
    }
    if (
      this.props.loading == false &&
      prevProps.loading == true &&
      this.props.error
    ) {
      console.log(this.props.error)
    }
  }

  UNSAFE_componentWillReceiveProps (nextProps) {
    if (
      nextProps.userInstallation &&
      nextProps.questionsAnswers &&
      nextProps.repartition
    ) {
      // Data for userInstallation
      let userInstallation = []

      for (let i = 0; i < nextProps.userInstallation.months.length; i++) {
        userInstallation.push({
          x: nextProps.userInstallation.months[i],
          y: nextProps.userInstallation.users_nb[i]
        })
      }
      this.setState({
        userInstallation: userInstallation,
        userInstallationMax: nextProps.userInstallation.max
      })

      // Data for questionsAnswers
      let questionsAnswers = []

      for (let i = 0; i < nextProps.questionsAnswers.months.length; i++) {
        questionsAnswers.push({
          x: nextProps.questionsAnswers.months[i],
          y: nextProps.questionsAnswers.questions_nb[i]
        })
      }
      this.setState({
        questionsAnswers: questionsAnswers,
        questionsAnswersMax: nextProps.questionsAnswers.max
      })

      // Data for repartition
      let repartition = []

      for (let i = 0; i < nextProps.repartition.repartition.length; i++) {
        repartition.push({
          x: nextProps.repartition.repartition[i],
          y: nextProps.repartition.data[i]
        })
      }
      this.setState({
        repartition: repartition
      })
    }
  }

  render () {
    var columns = [
      { title: 'Device-id', field: 'device_id' },
      { title: 'Platform', field: 'curr_os' },
      { title: 'App Version', field: 'curr_app_version' },
      { title: 'Sub. Quizzes', field: 'quizzes_count' },
      { title: 'Groups', field: 'groupsName' },
      { title: 'Boarding', field: 'created_at' }
    ]

    return (
      <div className='p-4 w-100 mt-80' spacing={3}>
        <Breadcrumbs aria-label='breadcrumb' className='w-100 mb-4'>
          <Link color='inherit' to='/dashboard'>
            BO
          </Link>
          <Typography color='textPrimary'>Dashboard</Typography>
        </Breadcrumbs>
        {this.state.isLoading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div>
            <div className='row mb-3'>
              <div className='col-md-3 d-flex'>
                <Card className='col-md-12 bg-primary'>
                  <div className='d-flex align-items-center justify-content-between w-100'>
                    <div>
                      <CardContent className='d-flex'>
                        <Typography
                          className='ft-20'
                          className='ft-20 text-white'
                          gutterBottom
                        >
                          {this.props.dashboard ? this.props.dashboard .user_count : 0}
                        </Typography>
                        <Typography className='ml-3 ft-20 text-white'>
                          Users
                        </Typography>
                      </CardContent>
                      <CardActions>
                        <Button
                          size='small'
                          className='text-white'
                          component={Link}
                          to='/user/users'
                        >
                          View Details
                        </Button>
                      </CardActions>
                    </div>
                    <PeopleAltIcon
                      style={{ width: 100, height: 100 }}
                      className='text-white'
                    />
                  </div>
                </Card>
              </div>
              <div className='col-md-3 d-flex'>
                <Card className='col-md-12 bg-success'>
                  <div className='d-flex align-items-center justify-content-between w-100'>
                    <div>
                      <CardContent className='d-flex'>
                        <Typography
                          className='ft-20'
                          className='ft-20 text-white'
                          gutterBottom
                        >
                          {this.props.dashboard.group_count}
                        </Typography>
                        <Typography className='ml-3 ft-20 text-white'>
                          Groups
                        </Typography>
                      </CardContent>
                      <CardActions>
                        <Button
                          size='small'
                          className='text-white'
                          component={Link}
                          to='/group/groups/'
                        >
                          View Details
                        </Button>
                      </CardActions>
                    </div>
                    <GroupWorkIcon
                      style={{ width: 100, height: 100 }}
                      className='text-white'
                    />
                  </div>
                </Card>
              </div>
              <div className='col-md-3 d-flex'>
                <Card className='col-md-12 bg-warning'>
                  <div className='d-flex align-items-center justify-content-between w-100'>
                    <div>
                      <CardContent className='d-flex'>
                        <Typography
                          className='ft-20'
                          className='ft-20 text-primary'
                          gutterBottom
                        >
                          {this.props.dashboard.question_count}
                        </Typography>
                        <Typography className='ml-3 ft-20 text-primary'>
                          Questions
                        </Typography>
                      </CardContent>
                      <CardActions>
                        <Button
                          size='small'
                          className='text-primary'
                          component={Link}
                          to='/question/questions'
                        >
                          View Details
                        </Button>
                      </CardActions>
                    </div>
                    <HelpIcon
                      style={{ width: 100, height: 100 }}
                      className='text-primary'
                    />
                  </div>
                </Card>
              </div>
              <div className='col-md-3 d-flex'>
                <Card className='col-md-12 bg-danger'>
                  <div className='d-flex align-items-center justify-content-between w-100'>
                    <div>
                      <CardContent className='d-flex'>
                        <Typography
                          className='ft-20'
                          className='ft-20 text-white'
                          gutterBottom
                        >
                          {this.props.dashboard.answer_count}
                        </Typography>
                        <Typography className='ml-3 ft-20 text-white'>
                          Users Answers
                        </Typography>
                      </CardContent>
                      <CardActions>
                        <Button
                          size='small'
                          className='text-white'
                          component={Link}
                          to='/user/users'
                        >
                          View Details
                        </Button>
                      </CardActions>
                    </div>
                    <QuestionAnswerIcon
                      style={{ width: 100, height: 100 }}
                      className='text-white'
                    />
                  </div>
                </Card>
              </div>
            </div>
            <div className='row mb-3'>
              <div className='col-md-12'>
                <Card className='col-md-12 p-0'>
                  <div className='bg-secondary p-2 d-flex text-white align-items-center'>
                    <ImageIcon />
                    <div>User Installation</div>
                  </div>
                  <div style={{ height: '200px' }}>
                    <ResponsiveContainer width='100%'>
                      <LineChart
                        data={this.state.userInstallation}
                        margin={{ top: 15, right: 10, left: 10, bottom: 5 }}
                      >
                        <XAxis dataKey='x' fontFamily='sans-serif' />
                        <YAxis
                          domain={['dataMin', 'dataMax']}
                          ticks={[0, this.state.userInstallationMax]}
                        />
                        <CartesianGrid vertical={false} stroke='#ebf3f0' />
                        <Tooltip />
                        <Line dataKey='y' dot={false} />
                      </LineChart>
                    </ResponsiveContainer>
                  </div>
                  <div className='bg-secondary text-white p-2 m-0 ft-15'>
                    Updated yesterday, at september 18, 2020
                  </div>
                </Card>
              </div>
            </div>

            <div className='row mb-5'>
              <div className='col-md-8'>
                <Card className='col-md-12 p-0'>
                  <div className='bg-secondary p-2 d-flex text-white align-items-center'>
                    <ImageIcon />
                    <div>Questions Answered</div>
                  </div>
                  <div style={{ height: '200px' }}>
                    <ResponsiveContainer width='100%'>
                      <LineChart
                        data={this.state.questionsAnswers}
                        margin={{ top: 15, right: 10, left: 10, bottom: 5 }}
                      >
                        <XAxis dataKey='x' fontFamily='sans-serif' />
                        <YAxis
                          domain={['dataMin', 'dataMax']}
                          ticks={[0, this.state.questionsAnswersMax]}
                        />
                        <CartesianGrid vertical={false} stroke='#ebf3f0' />
                        <Tooltip />
                        <Line dataKey='y' dot={false} />
                      </LineChart>
                    </ResponsiveContainer>
                  </div>
                  <div className='bg-secondary text-white p-2 m-0 ft-15'>
                    Updated yesterday, at september 18, 2020
                  </div>
                </Card>
              </div>
              <div className='col-md-4'>
                <Card className='col-md-12 p-0'>
                  <div className='bg-secondary p-2 d-flex text-white align-items-center'>
                    <ImageIcon />
                    <div>Platform Repartition</div>
                  </div>
                  <div style={{ height: '200px' }}>
                    <ResponsiveContainer width='100%'>
                      <LineChart
                        data={this.state.repartition}
                        margin={{ top: 15, right: 10, left: 10, bottom: 5 }}
                      >
                        <XAxis dataKey='x' fontFamily='sans-serif' />
                        <YAxis
                          domain={['dataMin', 'dataMax']}
                          // ticks={[0, 100]}
                        />
                        <CartesianGrid vertical={false} stroke='#ebf3f0' />
                        <Tooltip />
                        <Line dataKey='y' dot={false} />
                      </LineChart>
                    </ResponsiveContainer>
                  </div>
                  <div className='bg-secondary text-white p-2 m-0 ft-15'>
                    Updated yesterday, at september 18, 2020
                  </div>
                </Card>
              </div>
            </div>
            <div className='d-flex align-items-center'>
              <QuestionAnswerIcon className='mr-2' />
              <div className='ft-18 text-secondary'>New Quizzes</div>
            </div>
            <hr />
            <div className='row mb-4'>
              {this.props.dashboard.quizzes.map(quizze => (
                <div className='col-md-6 mb-3' key={quizze.id}>
                  <Card className='col-md-12 p-3' >
                    <Link
                      className='ft-20 text-primary'
                      color='inherit'
                      to={'/quizz/detail/' + quizze.id}
                    >
                      {quizze.name}
                    </Link>
                    <div className='d-flex align-items-center text-primary'>
                      <GroupWorkIcon className='mr-2' />
                      <Link
                        className='ft-16 text-primary'
                        color='inherit'
                        to={'/group/detail/' + quizze.group.id}
                      >
                        {quizze.group.name}
                      </Link>
                    </div>
                    <img
                      src={quizze.image_url}
                      width='100'
                      className='mt-3 mb-3'
                    ></img>
                    <div>
                      <Link
                        className='ft-15 text-primary'
                        color='inherit'
                        to={
                          '/author/detail/' +
                          quizze.author_without_global_scopes.id
                        }
                      >
                        {quizze.author_without_global_scopes.name}
                      </Link>
                    </div>
                    <div className='text-secondary mb-3'>
                      {quizze.description}
                    </div>
                    <div className='text-secondary'>{quizze.created_at}</div>
                  </Card>
                </div>
              ))}
            </div>
            <div className='row mb-3'>
              <div className='col-md-12'>
                <Card className='col-md-12 p-0'>
                  <div className='bg-secondary p-2 d-flex text-white align-items-center'>
                    <DateRangeIcon className='mr-2' />
                    <div>Last Users</div>
                  </div>
                  <div>
                    <Grid item xs={12}>
                      <MaterialTable
                        title='Users'
                        columns={columns}
                        data={this.props.dashboard.users}
                        icons={tableIcons}
                        options={{
                          paging: false
                        }}
                      />
                    </Grid>
                  </div>
                  <div className='bg-secondary text-white p-2 m-0 ft-15'>
                    Updated yesterday, at september 18, 2020
                  </div>
                </Card>
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
    dashboard: state.default.services.dashboard.dashboard,
    userInstallation: state.default.services.dashboard.userInstallation,
    questionsAnswers: state.default.services.dashboard.questionsAnswers,
    repartition: state.default.services.dashboard.repartition,
    loading: state.default.services.dashboard.loading,
    error: state.default.services.dashboard.error
  }),
  dispatch => ({
    groupActions: bindActionCreators(
      {
        getDashboard,
        getUserInstallation,
        getQuestionsAnswers,
        getRepartition
      },
      dispatch
    )
  })
)(Dashboard)
