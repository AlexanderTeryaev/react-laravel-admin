import React from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import { logout } from '../services/auth/authActions'
import Hidden from '@material-ui/core/Hidden'
import { makeStyles } from '@material-ui/core/styles'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import Button from '@material-ui/core/Button'
import IconButton from '@material-ui/core/IconButton'
import MenuIcon from '@material-ui/icons/Menu'

const useStyles = makeStyles(theme => ({
  root: {
    flexGrow: 1,
    position: 'relative',
    zIndex: 2000
  },
  menuButton: {
    marginRight: theme.spacing(2)
  },
  title: {
    flexGrow: 1
  }
}));

function handleLoginClick (props) {
  props.authActions.logout();
}
const TopBar = props => {
  
  const classes = useStyles();
  return (
    <div className={classes.root}>
      <AppBar position='static'>
        <Toolbar>
          <Hidden smUp implementation="css">
            <IconButton
                edge='start'
                className={classes.menuButton}
                color='inherit'
                aria-label='menu'
                onClick={props.toggleDrawer}
              >
                <MenuIcon />
            </IconButton>
          </Hidden>
          <Typography variant='h6' className={classes.title}>
            MRMLD BO
          </Typography>
          <Button
            color='inherit'
            onClick={() => {handleLoginClick(props)}}
          >
            Logout
          </Button>
        </Toolbar>
      </AppBar>
    </div>
  )
}

export default connect(
  state => ({
    auth: state.default.services.auth
  }),
  dispatch => ({
    authActions: bindActionCreators({ logout }, dispatch)
  })
)(TopBar)
