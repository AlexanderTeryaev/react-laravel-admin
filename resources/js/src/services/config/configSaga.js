import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getConfigsFailed,
  getConfigsSucceed,
  addConfigFailed,
  addConfigSucceed,
  editConfigFailed,
  editConfigSucceed,
  createConfigFailed,
  createConfigSucceed,
  updateConfigFailed,
  updateConfigSucceed,
  showConfigFailed,
  showConfigSucceed,
  deleteConfigFailed,
  deleteConfigSucceed
} from './configActions'

/** Import api */
import * as configApi from './configApi'

export function * configSubscriber () {
  yield all([takeEvery('GET_CONFIGS',  configs)])
  yield all([takeEvery('ADD_CONFIG', addConfig)])
  yield all([takeEvery('EDIT_CONFIG', editConfig)])
  yield all([takeEvery('CREATE_CONFIG', createConfig)])
  yield all([takeEvery('UPDATE_CONFIG', updateConfig)])
  yield all([takeEvery('SHOW_CONFIG', showConfig)])
  yield all([takeEvery('DELETE_CONFIG', deleteConfig)])
}

export function * configs ({payload: {params}}) {
  try {
    const res = yield call(configApi.configs, params)
    yield put(getConfigsSucceed(res.data))
  } catch (error) {
    yield put(getConfigsFailed(error))
  }
}

export function * addConfig ({payload: {params}}) {
  try {
    const res = yield call(configApi.addConfig, params)
    yield put(addConfigSucceed(res.data))
  } catch (error) {
    yield put(addConfigFailed(error))
  }
}

export function * editConfig ({payload: {id}}) {
  try {
    const res = yield call(configApi.editConfig, id)
    yield put(editConfigSucceed(res.data))
  } catch (error) {
    yield put(editConfigFailed(error))
  }
}

export function * createConfig () {
  try {
    const res = yield call(configApi.createConfig)
    yield put(createConfigSucceed(res.data))
  } catch (error) {
    yield put(createConfigFailed(error))
  }
}

export function * updateConfig ({payload: {id, params}}) {
  try {
    const res = yield call(configApi.updateConfig, id, params)
    yield put(updateConfigSucceed(res.data))
  } catch (error) {
    yield put(updateConfigFailed(error))
  }
}

export function * showConfig ({payload: {id}}) {
  try {
    const res = yield call(configApi.showConfig, id)
    yield put(showConfigSucceed(res.data))
  } catch (error) {
    yield put(showConfigFailed(error))
  }
}

export function * deleteConfig ({payload: {id}}) {
  try {
    const res = yield call(configApi.deleteConfig, id)
    yield put(deleteConfigSucceed(res.data))
  } catch (error) {
    yield put(deleteConfigFailed(error))
  }
}


