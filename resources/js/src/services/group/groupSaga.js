import { put, takeEvery, call, all } from 'redux-saga/effects';

/** Import actions */
import {
  getGroupsSucceed,
  getGroupsFailed,
  getAllGroupsSucceed,
  getAllGroupsFailed,
  showGroupSucceed,
  showGroupFailed,
  addGroupSucceed,
  addGroupFailed,
  updateGroupSucceed,
  updateGroupFailed,
  deleteGroupSucceed,
  deleteGroupFailed,
  addGroupManagerSucceed,
  addGroupManagerFailed,
  deleteGroupManagerSucceed,
  deleteGroupManagerFailed,
  addGroupConfigSucceed,
  addGroupConfigFailed,
  deleteGroupConfigSucceed,
  deleteGroupConfigFailed,
  addGroupEmailSucceed,
  addGroupEmailFailed,
  deleteGroupEmailSucceed,
  deleteGroupEmailFailed,
  downloadGroupUsersSucceed,
  downloadGroupUsersFailed,
} from './groupActions';

/** Import api */
import * as groupApi from './groupApi';

export function* groupSubscriber() {
  yield all([takeEvery('GET_GROUPS', groups)]);
  yield all([takeEvery('GET_ALL_GROUPS', allGroups)]);
  yield all([takeEvery('ADD_GROUP', addGroup)]);
  yield all([takeEvery('UPDATE_GROUP', updateGroup)]);
  yield all([takeEvery('DELETE_GROUP', deleteGroup)]);
  yield all([takeEvery('SHOW_GROUP', showGroup)]);
  yield all([takeEvery('ADD_GROUP_MANAGER', addGroupManager)]);
  yield all([takeEvery('DELETE_GROUP_MANAGER', deleteGroupManager)]);
  yield all([takeEvery('ADD_GROUP_CONFIG', addGroupConfig)]);
  yield all([takeEvery('DELETE_GROUP_CONFIG', deleteGroupConfig)]);
  yield all([takeEvery('ADD_GROUP_EMAIL', addGroupEmail)]);
  yield all([takeEvery('DELETE_GROUP_EMAIL', deleteGroupEmail)]);
  yield all([takeEvery('DOWNLOAD_GROUP_USERS', downloadGroupUsers)]);
}

export function* groups ({payload: {params}}) {
  try {
    const groups = yield call(groupApi.groups, params);
    yield put(getGroupsSucceed(groups.data));
  } catch (error) {
    yield put(getGroupsFailed(error));
  }
}

export function* allGroups () {
  try {
    const allGroups = yield call(groupApi.allGroups);
    yield put(getAllGroupsSucceed(allGroups.data));
  } catch (error) {
    yield put(getAllGroupsFailed(error));
  }
}

export function* showGroup({payload: {id}}) {
  try {
    const group = yield call(groupApi.showGroup, id);
    yield put(showGroupSucceed(group));
  } catch (error) {
    yield put(showGroupFailed(error));
  }
}

export function* addGroup({payload: {params}}) {
  try {
    const group = yield call(groupApi.addGroup, params);
    yield put(addGroupSucceed(group));
  } catch (error) {
    yield put(addGroupFailed(error));
  }
}

export function* updateGroup({payload: {id, params}}) {
  try {
    const group = yield call(groupApi.updateGroup, id, params);
    yield put(updateGroupSucceed(group));
  } catch (error) {
    yield put(updateGroupFailed(error));
  }
}

export function* deleteGroup({payload: {id}}) {
  try {
    const group = yield call(groupApi.deleteGroup, id);
    yield put(deleteGroupSucceed(id));
  } catch (error) {
    yield put(deleteGroupFailed(error));
  }
}

export function* addGroupManager({payload: {id, params}}) {
  try {
    let response = yield call(groupApi.addGroupManager, id, params);
    yield put(addGroupManagerSucceed(response));
  } catch (error) {
    yield put(addGroupManagerFailed(error));
  }
}

export function* deleteGroupManager({payload: {group_id, id}}) {
  try {
    yield call(groupApi.deleteGroupManager, group_id, id);
    yield put(deleteGroupManagerSucceed(id));
  } catch (error) {
    yield put(deleteGroupManagerFailed(error));
  }
}

export function* addGroupConfig({payload: {id, params}}) {
  try {
    let response = yield call(groupApi.addGroupConfig, id, params);
    yield put(addGroupConfigSucceed(response));
  } catch (error) {
    yield put(addGroupConfigFailed(error));
  }
}

export function* deleteGroupConfig({payload: {group_id, id}}) {
  try {
    yield call(groupApi.deleteGroupConfig, group_id, id);
    yield put(deleteGroupConfigSucceed(id));
  } catch (error) {
    yield put(deleteGroupConfigFailed(error));
  }
}

export function* addGroupEmail({payload: {id, params}}) {
  try {
    let response = yield call(groupApi.addGroupEmail, id, params);
    yield put(addGroupEmailSucceed(response));
  } catch (error) {
    yield put(addGroupEmailFailed(error));
  }
}

export function* deleteGroupEmail({payload: {group_id, id}}) {
  try {
    yield call(groupApi.deleteGroupEmail, group_id, id);
    yield put(deleteGroupEmailSucceed(id));
  } catch (error) {
    yield put(deleteGroupEmailFailed(error));
  }
}

export function* downloadGroupUsers({payload: {id}}) {
  try {
    let res = yield call(groupApi.downloadGroupUsers, id);
    yield put(downloadGroupUsersSucceed(res));
  } catch (error) {
    yield put(downloadGroupUsersFailed(error));
  }
}