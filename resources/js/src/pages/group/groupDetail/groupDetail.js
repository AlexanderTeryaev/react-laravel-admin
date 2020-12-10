import React, { Component } from 'react'
import { Link } from 'react-router-dom'
// var fileDownload = require('js-file-download');
import Typography from '@material-ui/core/Typography'
import Breadcrumbs from '@material-ui/core/Breadcrumbs'
import TextField from '@material-ui/core/TextField'
import InputLabel from '@material-ui/core/InputLabel'
import MenuItem from '@material-ui/core/MenuItem'
import FormControl from '@material-ui/core/FormControl'
import Select from '@material-ui/core/Select'
import CircularProgress from '@material-ui/core/CircularProgress'
import Button from '@material-ui/core/Button'
import Alert from '@material-ui/lab/Alert'
import './groupDetail.scss'
import '../../../styles/common.scss'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import {
  showGroup,
  addGroupManager,
  deleteGroupManager,
  addGroupConfig,
  deleteGroupConfig,
  addGroupEmail,
  deleteGroupEmail,
  downloadGroupUsers
} from '../../../services/group/groupActions'
import { toastr } from 'react-redux-toastr';
import { getUserInstallation } from '../../../services/dashboard/dashboardActions'
import EditIcon from '@material-ui/icons/Edit'
import GetAppIcon from '@material-ui/icons/GetApp'

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

//Card
import Card from '@material-ui/core/Card'
import ImageIcon from '@material-ui/icons/Image'

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

class GroupDetail extends Component {
  constructor (props) {
    super(props)
    this.state = {
      loading: true
    }
  }
  componentDidMount () {
    this.props.groupActions.showGroup(this.props.match.params.id)
    this.props.groupActions.getUserInstallation()
  }
  UNSAFE_componentWillReceiveProps (nextProps) {
    if (nextProps.group) {
      this.setState({
        loading: false
      })
    }
    if (nextProps.userInstallation) {
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
    }
  }

  componentDidUpdate (prevProps) {
    if (
      this.props.reloading == true &&
      prevProps.reloading == false &&
      !this.props.error
    ) {
      this.props.groupActions.showGroup(this.props.match.params.id)
    }
    if (
      this.props.reloading == true &&
      prevProps.reloading == false &&
      this.props.error && !prevProps.error
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

  _handleSelectChange (e) {
    this.setState({
      population: e.target.value
    })
  }

  renderOptions () {
    return this.props.group.populations.map((dt, i) => {
      return (
        <MenuItem
          label='Select a population'
          value={dt.id}
          key={i}
          name={dt.country_name}
        >
          {dt.name}
        </MenuItem>
      )
    })
  }

  handleDownloadClick () {
    window.open('/api/groups/' + this.props.match.params.id + '/users/download', '_blank');
  }

  handleAddManagerClick () {
    const { email, firstname, lastname, username } = this.state
    if (email == '' || !email) {
      toastr.info('Required','Please enter name');
      return;
    }
    if (username == '' || !username) {
      toastr.info('Required','Please enter username');
      return;
    }
    if (firstname == '' || !firstname) {
      toastr.info('Required','Please enter firstname');
      return;
    }
    if (lastname == '' || !lastname) {
      toastr.info('Required','Please enter lastname');
      return;
    }
    var formData = new FormData()
    formData.append(`username`, username)
    formData.append('email', email)
    formData.append('first_name', firstname)
    formData.append('last_name', lastname)
    this.props.groupActions.addGroupManager(this.props.match.params.id, formData)
    
  }

  handleManagerDelete (oldData, resolve) {
    this.props.groupActions.deleteGroupManager(
      this.props.match.params.id,
      oldData.id
    )
    resolve()
  }

  handleAddConfigClick () {
    const { key, value } = this.state
    if (key == '' || !key) {
      toastr.info('Required','Please enter key');
      return;
    }
    if (value == '' || !value) {
      toastr.info('Required','Please enter value');
      return;
    }
    var formData = new FormData()
    formData.append('key', key)
    formData.append('value', value)
    this.props.groupActions.addGroupConfig(this.props.match.params.id, formData)
    
  }

  handleConfigDelete (oldData, resolve) {
    this.props.groupActions.deleteGroupConfig(
      this.props.match.params.id,
      oldData.id
    )
    resolve()
  }

  handleAddDomainClick () {
    const { domain, population } = this.state

    if (domain == '' || !domain) {
      toastr.info('Required','Please enter domain');
      return;
    }

    if (population == '' || !population) {
      toastr.info('Required','Please enter population');
      return;
    }

    var formData = new FormData()
    formData.append('username', domain)
    formData.append('population_id', population)
    this.props.groupActions.addGroupEmail(this.props.match.params.id, formData)
  }

  handleDomainDelete (oldData, resolve) {
    this.props.groupActions.deleteGroupEmail(
      this.props.match.params.id,
      oldData.id
    )
    resolve()
  }

  render () {
    var Managercolumns = [
      { title: 'id', field: 'id', hidden: true },
      { title: 'Username', field: 'username' },
      { title: 'Email', field: 'email' },
      { title: 'Verified at', field: 'email_verified_at' }
    ]
    var Configcolumns = [
      { title: 'id', field: 'id', hidden: true },
      { title: 'Key', field: 'key' },
      { title: 'Value', field: 'value' },
      { title: 'Created at', field: 'created_at' }
    ]
    var Populationscolumns = [
      { title: 'id', field: 'id', hidden: true },
      { title: 'Name', field: 'name' },
      { title: 'Master Key', field: 'master_key' },
      { title: 'Created at', field: 'created_at' }
    ]
    var Emailcolumns = [
      { title: 'id', field: 'id', hidden: true },
      { title: 'Domain', field: 'username' },
      { title: 'Email', field: 'population.name' },
      { title: 'Verified at', field: 'created_at' }
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
          <Link color='inherit' to='/group/groups'>
            Groups
          </Link>
          <Typography color='textPrimary'>Detail</Typography>
        </Breadcrumbs>
        {this.state.loading ? (
          <div className='text-center w-100 mt-5'>
            <CircularProgress className='mt-5' />
          </div>
        ) : (
          <div noValidate autoComplete='off' className='groupDetail'>            
            <div className='text-right mb-4'>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                component={Link}
                className='mr-5'
                to={'/group/edit/' + this.props.match.params.id}
              >
                <EditIcon className='mr-2' />
                Edit
              </Button>
              <Button
                variant='contained'
                color='primary'
                component='span'
                type='submit'
                onClick={() => {
                  this.handleDownloadClick()
                }}
              >
                <GetAppIcon className='mr-2' />
                Download Users
              </Button>
            </div>
            <div className='row mb-5'>
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

            <div>
              <div className='row w-100'>
                <div className='col-md-6'>
                  <h4 className='mb-4'>Group</h4>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>Users</div>
                    <div className='w-50 col-md'>
                      {this.props.group.users_count}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Allowed users
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.group.users_limit}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>Quizzes</div>
                    <div className='w-50 col-md'>
                      {this.props.group.quizzes_count}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Questions
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.group.questions_count}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Trial end
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.group.trial_ends_at_temp}
                    </div>
                  </div>
                  <div className='row d-flex mb-3'>
                    <div className='font-weight-bold w-50 col-md'>
                      Default Card
                    </div>
                    <div className='w-50 col-md'>
                      {this.props.group.default_card}
                    </div>
                  </div>
                  <hr className='mb-4' />
                  <h4 className='mb-4'>Group Managers</h4>
                  <div className='mb-3'>
                    To add a manager, you can specify an existing user via his
                    username, it is not mandatory but it is better. To do this,
                    simply ask the user for his username and indicate it. This
                    will allow you to have a single account for the user for the
                    application and the portal.
                  </div>
                  <div className='d-flex justify-content-between w-100 align-items-center mb-4'>
                    <TextField
                      label='email'
                      type='text'
                      variant='outlined'
                      className='w-20'
                      name='email'
                      value={this.state.email || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <TextField
                      label='First Name'
                      type='text'
                      variant='outlined'
                      className='w-20'
                      name='firstname'
                      value={this.state.firstname || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <TextField
                      label='Last Name'
                      type='text'
                      variant='outlined'
                      className='w-20'
                      name='lastname'
                      value={this.state.lastname || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <TextField
                      label='Username'
                      type='text'
                      variant='outlined'
                      className='w-20'
                      name='username'
                      value={this.state.username || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <Button
                      variant='contained'
                      color='primary'
                      component='span'
                      type='submit'
                      onClick={() => {
                        this.handleAddManagerClick()
                      }}
                    >
                      Add
                    </Button>
                  </div>
                  <Grid item xs={12}>
                    <MaterialTable
                      title='Group Managers'
                      options={{
                        actionsColumnIndex: 9
                      }}
                      columns={Managercolumns}
                      data={this.props.group.managers}
                      icons={tableIcons}
                      editable={{
                        onRowDelete: oldData =>
                          new Promise(resolve => {
                            this.handleManagerDelete(oldData, resolve)
                          })
                      }}
                    />
                  </Grid>
                  <hr className='mb-4 mt-4' />
                  <h4 className='mb-4'>Group Config</h4>
                  <div className='d-flex justify-content-between w-100 align-items-center mb-4'>
                    <TextField
                      label='Key'
                      type='text'
                      variant='outlined'
                      className='w-40'
                      name='key'
                      value={this.state.key || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <TextField
                      label='Value'
                      type='text'
                      variant='outlined'
                      className='w-40'
                      name='value'
                      value={this.state.value || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <Button
                      variant='contained'
                      color='primary'
                      component='span'
                      type='submit'
                      onClick={() => {
                        this.handleAddConfigClick()
                      }}
                    >
                      Add
                    </Button>
                  </div>
                  <Grid item xs={12}>
                    <MaterialTable
                      title='Group Config'
                      options={{
                        actionsColumnIndex: 9
                      }}
                      columns={Configcolumns}
                      data={ this.props.group && this.props.group.configs && this.props.group.configs.length > 0 ? this.props.group.configs : []}
                      icons={tableIcons}
                      editable={{
                        onRowDelete: oldData =>
                          new Promise(resolve => {
                            this.handleConfigDelete(oldData, resolve)
                          })
                      }}
                    />
                  </Grid>
                </div>
                <div className='col-md-6'>
                  <h4 className='mb-4'>Populations</h4>
                  <Grid item xs={12}>
                    <MaterialTable
                      title='Populations'
                      options={{
                        actionsColumnIndex: 9
                      }}
                      columns={Populationscolumns}
                      data={this.props.group.populations}
                      icons={tableIcons}
                    />
                  </Grid>
                  <hr className='mb-4 mt-4' />
                  <h4 className='mb-4'>Allowed email domains</h4>
                  <div className='d-flex justify-content-between w-100 align-items-center mb-4'>
                    <TextField
                      label='Domain'
                      type='text'
                      variant='outlined'
                      className='w-40'
                      name='domain'
                      value={this.state.domain || ''}
                      onChange={e => this._handleTextFieldChange(e)}
                    />
                    <FormControl variant='outlined' className='w-40'>
                      <InputLabel id='population'>Population</InputLabel>
                      <Select
                        labelId='population'
                        id='population'
                        value={this.state.population || ''}
                        onChange={e => this._handleSelectChange(e)}
                        label='Population'
                      >
                        {this.renderOptions()}
                      </Select>
                    </FormControl>
                    <Button
                      variant='contained'
                      color='primary'
                      component='span'
                      type='submit'
                      onClick={() => {
                        this.handleAddDomainClick()
                      }}
                    >
                      Add
                    </Button>
                  </div>
                  <Grid item xs={12}>
                    <MaterialTable
                      title='Allowed email domains'
                      options={{
                        actionsColumnIndex: 9
                      }}
                      columns={Emailcolumns}
                      data={this.props.group.allowed_domains}
                      icons={tableIcons}
                      editable={{
                        onRowDelete: oldData =>
                          new Promise(resolve => {
                            this.handleDomainDelete(oldData, resolve)
                          })
                      }}
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
    error: state.default.services.group.error,
    group: state.default.services.group.group,
    reloading: state.default.services.group.reloading,
    loading: state.default.services.group.loading,
    userInstallation: state.default.services.dashboard.userInstallation,
    url: state.default.services.group.url
  }),
  dispatch => ({
    groupActions: bindActionCreators(
      {
        addGroupManager,
        deleteGroupManager,
        addGroupConfig,
        deleteGroupConfig,
        addGroupEmail,
        deleteGroupEmail,
        showGroup,
        getUserInstallation,
        downloadGroupUsers
      },
      dispatch
    )
  })
)(GroupDetail)
