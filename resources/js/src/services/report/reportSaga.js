import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getReportsFailed,
  getReportsSucceed,
  addReportFailed,
  addReportSucceed,
  editReportFailed,
  editReportSucceed,
  createReportFailed,
  createReportSucceed,
  updateReportFailed,
  updateReportSucceed,
  deleteReportFailed,
  deleteReportSucceed
} from './reportActions'

/** Import api */
import * as reportApi from './reportApi'

export function * reportSubscriber () {
  yield all([takeEvery('GET_REPORTS',  reports)])
  yield all([takeEvery('ADD_REPORT', addReport)])
  yield all([takeEvery('EDIT_REPORT', editReport)])
  yield all([takeEvery('CREATE_REPORT', createReport)])
  yield all([takeEvery('UPDATE_REPORT', updateReport)])
  yield all([takeEvery('DELETE_REPORT', deleteReport)])
}

export function * reports ({payload: {params}}) {
  try {
    const res = yield call(reportApi.reports, params)
    yield put(getReportsSucceed(res.data))
  } catch (error) {
    yield put(getReportsFailed(error))
  }
}

export function * addReport ({payload: {params}}) {
  try {
    const res = yield call(reportApi.addReport, params)
    yield put(addReportSucceed(res.data))
  } catch (error) {
    yield put(addReportFailed(error))
  }
}

export function * editReport ({payload: {id}}) {
  try {
    const res = yield call(reportApi.editReport, id)
    yield put(editReportSucceed(res.data))
  } catch (error) {
    yield put(editReportFailed(error))
  }
}

export function * createReport () {
  try {
    const res = yield call(reportApi.createReport)
    yield put(createReportSucceed(res.data))
  } catch (error) {
    yield put(createReportFailed(error))
  }
}

export function * updateReport ({payload: {id, params}}) {
  try {
    const res = yield call(reportApi.updateReport, id, params)
    yield put(updateReportSucceed(res.data))
  } catch (error) {
    yield put(updateReportFailed(error))
  }
}

export function * deleteReport ({payload: {id}}) {
  try {
    const res = yield call(reportApi.deleteReport, id)
    yield put(deleteReportSucceed(res.data))
  } catch (error) {
    yield put(deleteReportFailed(error))
  }
}
