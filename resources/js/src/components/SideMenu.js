import React from 'react';
import Divider from '@material-ui/core/Divider';
import Drawer from '@material-ui/core/Drawer';
import Hidden from '@material-ui/core/Hidden';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import DashboardIcon from '@material-ui/icons/Dashboard';
import PeopleIcon from '@material-ui/icons/People';
import GroupWorkIcon from '@material-ui/icons/GroupWork';
import GroupIcon from '@material-ui/icons/Group';
import ContactsIcon from '@material-ui/icons/Contacts';
import AppleIcon from '@material-ui/icons/Apple';
import FeaturedPlayListIcon from '@material-ui/icons/FeaturedPlayList';
import CategoryIcon from '@material-ui/icons/Category';
import ReportIcon from '@material-ui/icons/Report';
import MenuBookIcon from '@material-ui/icons/MenuBook';
import CheckBoxIcon from '@material-ui/icons/CheckBox';
import ImportExportIcon from '@material-ui/icons/ImportExport';
import LiveHelpIcon from '@material-ui/icons/LiveHelp';
import SpellcheckIcon from '@material-ui/icons/Spellcheck';
import ImportantDevicesIcon from '@material-ui/icons/ImportantDevices';
import AttachMoneyIcon from '@material-ui/icons/AttachMoney';
import PermDataSettingIcon from '@material-ui/icons/PermDataSetting';
import { makeStyles, useTheme } from '@material-ui/core/styles';
import { Link } from "react-router-dom";
import { hasPermission } from './HasPermission';
const drawerWidth = 270;

const useStyles = makeStyles((theme) => ({
  bgBlack: {
    background: "#0000"
  },
  root: {
    display: 'flex',
  },
  drawer: {
    [theme.breakpoints.up('sm')]: {
      width: drawerWidth,
      flexShrink: 0,
    },
  },
  appBar: {
    [theme.breakpoints.up('sm')]: {
      width: `calc(100% - ${drawerWidth}px)`,
      marginLeft: drawerWidth,
    },
  },
  menuButton: {
    marginRight: theme.spacing(2),
    [theme.breakpoints.up('sm')]: {
      display: 'none',
    },
  },
  // necessary for content to be below app bar
  toolbar: theme.mixins.toolbar,
  drawerPaper: {
    width: drawerWidth,
  },
  content: {
    flexGrow: 1,
    padding: theme.spacing(3),
  },
  subMenuList: {
    paddingLeft: '20px'
  }
}));
export default function TemporaryDrawer(props) {  
  const { window } = props;
  const classes = useStyles();
  const theme = useTheme();
  const [groupManagementCollapse, setGroupManagementCollapse] = React.useState(true);
  const handleGroupManagementCollapse = () => {
    setGroupManagementCollapse(!groupManagementCollapse);
  };

  const [appContentCollapse, setAppContentCollapse] = React.useState(true);
  const handleAppContentCollapse = () => {
    setAppContentCollapse(!appContentCollapse);
  };

  const [trainingContentCollapse, setTrainingContentCollapse] = React.useState(true);
  const handleTrainingContentCollapse = () => {
    setTrainingContentCollapse(!trainingContentCollapse);
  };

  const [landingContentCollapse, setLandingContentCollapse] = React.useState(true);
  const handleLandingContentCollapse = () => {
    setLandingContentCollapse(!landingContentCollapse);
  };

  const drawer = (
    <div className={classes.bgBlack}>
      <div className={classes.toolbar} />
      <Divider />
      <List>
        <Link to="/dashboard">
          <ListItem button key="Dashboard">
            <ListItemIcon><DashboardIcon /></ListItemIcon>
            <ListItemText primary={"Dashboard"} />
          </ListItem>
        </Link>
        { hasPermission('view user') &&
        <Link to="/user/users">
          <ListItem button key="Users">
            <ListItemIcon><PeopleIcon /></ListItemIcon>
            <ListItemText primary={"Users"} />
          </ListItem>
        </Link>}
        <ListItem button key="GroupManagement" onClick={handleGroupManagementCollapse}>
          <ListItemIcon><GroupWorkIcon /></ListItemIcon>
          <ListItemText primary={"Group management"} />
        </ListItem>
        {
          groupManagementCollapse ? (
            ''
          ) : (
            <List className={classes.subMenuList}>
              { hasPermission('view group') &&
              <Link to="/group/groups">
                <ListItem button key="Groups">
                  <ListItemIcon><GroupIcon /></ListItemIcon>
                  <ListItemText primary={"Groups"} />
                </ListItem>
              </Link>}
              { hasPermission('view insight_recipient') &&
              <Link to="/recipient/recipients">
                <ListItem button key="InsightRecipients">
                  <ListItemIcon><ContactsIcon /></ListItemIcon>
                  <ListItemText primary={"Insight Recipients"} />
                </ListItem>
              </Link>}
            </List>
          )
        }

        <ListItem button key="App Content" onClick={handleAppContentCollapse}>
          <ListItemIcon><AppleIcon /></ListItemIcon>
          <ListItemText primary={"App Content"} />
        </ListItem>
        {
          appContentCollapse ? (
            ''
          ) : (
            <List className={classes.subMenuList}>
              { hasPermission('view featured') &&
              <Link to="/featured/featureds">
                <ListItem button key="AppFeatured">
                  <ListItemIcon><FeaturedPlayListIcon /></ListItemIcon>
                  <ListItemText primary={"Featured"} />
                </ListItem>
              </Link>}
              { hasPermission('view category') &&
              <Link to="/category/categories">
                <ListItem button key="AppCategories">
                  <ListItemIcon><CategoryIcon /></ListItemIcon>
                  <ListItemText primary={"Categories"} />
                </ListItem>
              </Link>}
              { hasPermission('view quizz') &&
              <Link to="/quizz/quizzes">
                <ListItem button key="AppQuizzes">
                  <ListItemIcon><LiveHelpIcon /></ListItemIcon>
                  <ListItemText primary={"Quizzes"} />
                </ListItem>
              </Link>}
              { hasPermission('view question') &&
              <Link to="/question/questions">
                <ListItem button key="AppQuestions">
                  <ListItemIcon><LiveHelpIcon /></ListItemIcon>
                  <ListItemText primary={"Questions"} />
                </ListItem>
              </Link>}
              { hasPermission('view question') &&
              <Link to="/import/questions">
                <ListItem button key="AppQuestionImports">
                  <ListItemIcon><ImportExportIcon /></ListItemIcon>
                  <ListItemText primary={"Question Imports"} />
                </ListItem>
              </Link>}
              { hasPermission('view question') &&
              <Link to="/report/reports">
                <ListItem button key="AppReports">
                  <ListItemIcon><ReportIcon /></ListItemIcon>
                  <ListItemText primary={"Reports"} />
                </ListItem>
              </Link>}
              { hasPermission('view author') &&
              <Link to="/author/authors">
                <ListItem button key="AppAuthors">
                  <ListItemIcon><SpellcheckIcon /></ListItemIcon>
                  <ListItemText primary={"Authors"} />
                </ListItem>
              </Link>}
            </List>
          )
        }

        <ListItem button key="TrainingContent" onClick={handleTrainingContentCollapse}>
          <ListItemIcon><MenuBookIcon /></ListItemIcon>
          <ListItemText primary={"Training Content"} />
        </ListItem>
        {
          trainingContentCollapse ? (
            ''
          ) : (
            <List className={classes.subMenuList}>
              <Link to="/shopTraining/shopTrainings">
                <ListItem button key="TrainTrainings">
                  <ListItemIcon><MenuBookIcon /></ListItemIcon>
                  <ListItemText primary={"Trainings"} />
                </ListItem>
              </Link>
              { hasPermission('view quizz') &&
              <Link to="/shopQuizz/shopQuizzes">
                <ListItem button key="TrainQuizzes">
                  <ListItemIcon><CheckBoxIcon /></ListItemIcon>
                  <ListItemText primary={"Quizzes"} />
                </ListItem>
              </Link>}
              { hasPermission('view question') &&
              <Link to="/shop-import/questions">
                <ListItem button key="TrainQuestionImports">
                  <ListItemIcon><ImportExportIcon /></ListItemIcon>
                  <ListItemText primary={"Question Imports"} />
                </ListItem>
              </Link>}
              { hasPermission('view question') &&
              <Link to="/shop-question/questions">
                <ListItem button key="TrainQuestions">
                  <ListItemIcon><LiveHelpIcon /></ListItemIcon>
                  <ListItemText primary={"Questions"} />
                </ListItem>
              </Link>}
              { hasPermission('view author') &&
              <Link to="/shop-author/authors">
                <ListItem button key="TrainAuthors">
                  <ListItemIcon><SpellcheckIcon /></ListItemIcon>
                  <ListItemText primary={"Authors"} />
                </ListItem>
              </Link>}
            </List>
          )
        }

        <ListItem button key="LandingContent" onClick={handleLandingContentCollapse}>
          <ListItemIcon><ImportantDevicesIcon /></ListItemIcon>
          <ListItemText primary={"Landing Content"} />
        </ListItem>
        {
          landingContentCollapse ? (
            ''
          ) : (
            <List className={classes.subMenuList}>
              { hasPermission('view plan') &&
              <Link to="/plan/plans">
                <ListItem button key="LandingPlans">
                  <ListItemIcon><AttachMoneyIcon /></ListItemIcon>
                  <ListItemText primary={"Plans"} />
                </ListItem>
              </Link>}
              { hasPermission('view plan') &&
              <Link to="/coins-pack/packs">
                <ListItem button key="LandingCoins">
                  <ListItemIcon><AttachMoneyIcon /></ListItemIcon>
                  <ListItemText primary={"Coins Packs"} />
                </ListItem>
              </Link>}
            </List>
          )
        }
        
        { hasPermission('view config') &&
        <Link to="/config/configs">
          <ListItem button key="Configs">
            <ListItemIcon><PermDataSettingIcon /></ListItemIcon>
            <ListItemText primary={"Configs"} />
          </ListItem>
        </Link>}
        
      </List>
    </div>
  );

  const container = window !== undefined ? () => window().document.body : undefined;

  return (
    <nav className={classes.drawer} aria-label="mailbox folders">
      {/* The implementation can be swapped with js to avoid SEO duplication of links. */}
      <Hidden smUp implementation="css">
        <Drawer
          container={container}
          variant="temporary"
          anchor={theme.direction === 'rtl' ? 'right' : 'left'}
          open={props.open}
          onClose={props.toggleDrawer}
          classes={{
            paper: classes.drawerPaper,
          }}
          ModalProps={{
            keepMounted: true, // Better open performance on mobile.
          }}
        >
          {drawer}
        </Drawer>
      </Hidden>
      <Hidden xsDown implementation="css">
        <Drawer
          classes={{
            paper: classes.drawerPaper,
          }}
          variant="permanent"
          open
        >
          {drawer}
        </Drawer>
      </Hidden>
    </nav>
  );
}