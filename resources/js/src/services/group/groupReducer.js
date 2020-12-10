import { handleActions } from 'redux-actions';

import {
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
} from './groupActions';

const defaultState = {
  groups: [],
  allGroups: [],
  error: null,
  loading: true,
  isUpdate: true,
  reloading: false,
  group: null,
  id: null,
  manager: null,
  config: null,
  mail: null,
  url: null,
  total_count: 0
};

const reducer = handleActions(
  {    
    [getGroups](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getGroupsFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [getGroupsSucceed](
      state,
      {
        payload: { groups }
      }
    ) {
      return {
        ...state,
        groups: groups.groups,
        total_count: groups.total_count,
        error: null,
        loading: false
      };
    },
    [getAllGroups](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getAllGroupsFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [getAllGroupsSucceed](
      state,
      {
        payload: { allGroups }
      }
    ) {
      return {
        ...state,
        allGroups: allGroups,
        error: null,
        loading: false
      };
    },
    [showGroup](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showGroupFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [showGroupSucceed] (
      state,
      {
        payload: { group }
      }
    ) {
      return {
        ...state,
        group: group,
        error: null,
        loading: false
      };
    },
    [addGroup](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addGroupFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [addGroupSucceed] (
      state,
      {
        payload: { group }
      }
    ) {
      let groups = JSON.parse(JSON.stringify(state.groups));
      groups.push(group);

      return {
        ...state,
        groups: groups,
        error: null,
        loading: false
      };
    },
    [updateGroup](state) {
      return {
        ...state,
        error: null,
        loading: true,
        isUpdate: true
      };
    },
    [updateGroupFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false,
        isUpdate: false
      };
    },
    [updateGroupSucceed] (
      state,
      {
        payload: { group }
      }
    ) {
      let groups = JSON.parse(JSON.stringify(state.groups));
      groups.forEach((item, index) => {
        if (item.id == group.id) {
          groups[index] = group;
        }
      });

      return {
        ...state,
        groups: groups,
        error: null,
        loading: false,
        isUpdate: false
      };
    },
    [deleteGroup](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteGroupFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteGroupSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      
      return {
        ...state,
        error: null,
        reloading: true
      };
    },
    [addGroupManager](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [addGroupManagerFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [addGroupManagerSucceed] (
      state,
      {
        payload: { manager }
      }
    ) {
      return {
        ...state,
        group:{
          ...state.group,
          managers: [
            ...state.group.managers,
            manager
          ]
        },
        error: null,
        reloading: true
      };
    },
    [deleteGroupManager](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteGroupManagerFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteGroupManagerSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        error: null,
        reloading: true
      };
    },
    [addGroupConfig](state) {
      return {
        ...state,
        error: null,
        reloading:false
      };
    },
    [addGroupConfigFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading:true
      };
    },
    [addGroupConfigSucceed] (
      state,
      {
        payload: { config }
      }
    ) {
      return {
        ...state,
        group:{
          ...state.group,
          configs: [
            ...state.group.configs,
            config
          ]
        },
        error: null,
        reloading:true
      };
    },
    [deleteGroupConfig](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteGroupConfigFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteGroupConfigSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        error: null,
        reloading: true,
        id: id
      };
    },
    [addGroupEmail](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [addGroupEmailFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [addGroupEmailSucceed] (
      state,
      {
        payload: { mail }
      }
    ) {
      return {
        ...state,
        group:{
          ...state.group,
          allowed_domains: [
            ...state.group.allowed_domains,
            mail
          ]
        },
        error: null,
        reloading: true
      };
    },
    [deleteGroupEmail](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteGroupEmailFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteGroupEmailSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        error: null,
        reloading: true
      };
    },
    [downloadGroupUsers](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [downloadGroupUsersFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [downloadGroupUsersSucceed] (
      state,
      {
        payload: { url }
      }
    ) {
      return {
        ...state,
        url: url,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
