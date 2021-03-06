import { createActions } from 'redux-actions';

const {
  getGroups,
  getGroupsFailed,
  getGroupsSucceed,
  getAllGroups,
  getAllGroupsFailed,
  getAllGroupsSucceed,
  addGroup,
  addGroupSucceed,
  addGroupFailed,
  showGroup,
  showGroupSucceed,
  showGroupFailed,
  updateGroup,
  updateGroupSucceed,
  updateGroupFailed,
  deleteGroup,
  deleteGroupSucceed,
  deleteGroupFailed,
  // for group detail
  addGroupManager,
  addGroupManagerSucceed,
  addGroupManagerFailed,
  deleteGroupManager,
  deleteGroupManagerSucceed,
  deleteGroupManagerFailed,
  addGroupConfig,
  addGroupConfigSucceed,
  addGroupConfigFailed,
  deleteGroupConfig,
  deleteGroupConfigSucceed,
  deleteGroupConfigFailed,
  addGroupEmail,
  addGroupEmailSucceed,
  addGroupEmailFailed,
  deleteGroupEmail,
  deleteGroupEmailSucceed,
  deleteGroupEmailFailed,

  //Download
  downloadGroupUsers,
  downloadGroupUsersFailed,
  downloadGroupUsersSucceed,
} = createActions({
  GET_GROUPS: (params) => ({params}),
  GET_GROUPS_FAILED: error => ({ error }),
  GET_GROUPS_SUCCEED: groups => ({ groups }),
  GET_ALL_GROUPS: () => ({}),
  GET_ALL_GROUPS_FAILED: error => ({ error }),
  GET_ALL_GROUPS_SUCCEED: allGroups => ({ allGroups }),
  SHOW_GROUP: (id) => ({id: id}),
  SHOW_GROUP_SUCCEED: group => ({group: group}),
  SHOW_GROUP_FAILED: error => ({ error }),
  ADD_GROUP: (params) => ({params: params}),
  ADD_GROUP_SUCCEED: group => ({group: group}),
  ADD_GROUP_FAILED: error => ({ error }),
  UPDATE_GROUP: (id, params) => ({id: id, params: params}),
  UPDATE_GROUP_SUCCEED: group => ({group: group}),
  UPDATE_GROUP_FAILED: error => ({ error }),
  DELETE_GROUP: (id) => ({id}),
  DELETE_GROUP_SUCCEED: (id) => ({id}),
  DELETE_GROUP_FAILED: (error) => ({error}),
  // For group detail
  ADD_GROUP_MANAGER: ( id, params) => ({id: id, params: params}),
  ADD_GROUP_MANAGER_SUCCEED: manager => ({manager: manager}),
  ADD_GROUP_MANAGER_FAILED: error => ({ error }),
  DELETE_GROUP_MANAGER: (group_id, id) => ({group_id, id}),
  DELETE_GROUP_MANAGER_SUCCEED: (id) => ({id}),
  DELETE_GROUP_MANAGER_FAILED: (error) => ({error}),
  ADD_GROUP_CONFIG: ( id, params) => ({id: id, params: params}),
  ADD_GROUP_CONFIG_SUCCEED: config => ({config: config}),
  ADD_GROUP_CONFIG_FAILED: error => ({ error }),
  DELETE_GROUP_CONFIG: (group_id, id) => ({group_id, id}),
  DELETE_GROUP_CONFIG_SUCCEED: (id) => ({id}),
  DELETE_GROUP_CONFIG_FAILED: (error) => ({error}),
  ADD_GROUP_EMAIL: ( id, params) => ({id: id, params: params}),
  ADD_GROUP_EMAIL_SUCCEED: mail => ({mail: mail}),
  ADD_GROUP_EMAIL_FAILED: error => ({ error }),
  DELETE_GROUP_EMAIL: (group_id, id) => ({group_id, id}),
  DELETE_GROUP_EMAIL_SUCCEED: (id) => ({id}),
  DELETE_GROUP_EMAIL_FAILED: (error) => ({error}),
  // downloadGroupUsers
  DOWNLOAD_GROUP_USERS: (id) => ({id:id}),
  DOWNLOAD_GROUP_USERS_FAILED: error => ({ error }),
  DOWNLOAD_GROUP_USERS_SUCCEED: url => ({ url }),
});

export {
  getGroups,
  getGroupsFailed,
  getGroupsSucceed,
  getAllGroups,
  getAllGroupsFailed,
  getAllGroupsSucceed,
  showGroup,
  showGroupSucceed,
  showGroupFailed,
  addGroup,
  addGroupSucceed,
  addGroupFailed,
  updateGroup,
  updateGroupSucceed,
  updateGroupFailed,
  deleteGroup,
  deleteGroupSucceed,
  deleteGroupFailed,
  // For group detail
  addGroupManager,
  addGroupManagerSucceed,
  addGroupManagerFailed,
  deleteGroupManager,
  deleteGroupManagerSucceed,
  deleteGroupManagerFailed,
  addGroupConfig,
  addGroupConfigSucceed,
  addGroupConfigFailed,
  deleteGroupConfig,
  deleteGroupConfigSucceed,
  deleteGroupConfigFailed,
  addGroupEmail,
  addGroupEmailSucceed,
  addGroupEmailFailed,
  deleteGroupEmail,
  deleteGroupEmailSucceed,
  deleteGroupEmailFailed,

  //DOWNLOAD_GROUP_USERS
  downloadGroupUsers,
  downloadGroupUsersSucceed,
  downloadGroupUsersFailed
};
