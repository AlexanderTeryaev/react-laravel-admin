import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getPlansFailed,
  getPlansSucceed,
  addPlanFailed,
  addPlanSucceed,
  editPlanFailed,
  editPlanSucceed,
  createPlanFailed,
  createPlanSucceed,
  updatePlanFailed,
  updatePlanSucceed,
  showPlanFailed,
  showPlanSucceed,
  deletePlanFailed,
  deletePlanSucceed
} from './planActions'

/** Import api */
import * as planApi from './planApi'

export function * planSubscriber () {
  yield all([takeEvery('GET_PLANS',  plans)])
  yield all([takeEvery('ADD_PLAN', addPlan)])
  yield all([takeEvery('EDIT_PLAN', editPlan)])
  yield all([takeEvery('CREATE_PLAN', createPlan)])
  yield all([takeEvery('UPDATE_PLAN', updatePlan)])
  yield all([takeEvery('SHOW_PLAN', showPlan)])
  yield all([takeEvery('DELETE_PLAN', deletePlan)])
}

export function * plans ({payload: {params}}) {
  try {
    const res = yield call(planApi.plans, params)
    yield put(getPlansSucceed(res.data))
  } catch (error) {
    yield put(getPlansFailed(error))
  }
}

export function * addPlan ({payload: {params}}) {
  try {
    const res = yield call(planApi.addPlan, params)
    yield put(addPlanSucceed(res.data))
  } catch (error) {
    yield put(addPlanFailed(error))
  }
}

export function * editPlan ({payload: {id}}) {
  try {
    const res = yield call(planApi.editPlan, id)
    yield put(editPlanSucceed(res.data))
  } catch (error) {
    yield put(editPlanFailed(error))
  }
}

export function * createPlan () {
  try {
    const res = yield call(planApi.createPlan)
    yield put(createPlanSucceed(res.data))
  } catch (error) {
    yield put(createPlanFailed(error))
  }
}

export function * updatePlan ({payload: {id, params}}) {
  try {
    const res = yield call(planApi.updatePlan, id, params)
    yield put(updatePlanSucceed(res.data))
  } catch (error) {
    yield put(updatePlanFailed(error))
  }
}

export function * showPlan ({payload: {id}}) {
  try {
    const res = yield call(planApi.showPlan, id)
    yield put(showPlanSucceed(res.data))
  } catch (error) {
    yield put(showPlanFailed(error))
  }
}

export function * deletePlan ({payload: {id}}) {
  try {
    const res = yield call(planApi.deletePlan, id)
    yield put(deletePlanSucceed(res.data))
  } catch (error) {
    yield put(deletePlanFailed(error))
  }
}


