import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getShopTrainingsFailed,
  getShopTrainingsSucceed,
  addShopTrainingFailed,
  addShopTrainingSucceed,
  editShopTrainingFailed,
  editShopTrainingSucceed,
  createShopTrainingFailed,
  createShopTrainingSucceed,
  updateShopTrainingFailed,
  updateShopTrainingSucceed,
  deleteShopTrainingSucceed,
  deleteShopTrainingFailed,
} from './shopTrainingActions'

/** Import api */
import * as shopTrainingApi from './shopTrainingApi'

export function * shopTrainingSubscriber () {
  yield all([takeEvery('GET_SHOP_TRAININGS',  shopTrainings)])
  yield all([takeEvery('ADD_SHOP_TRAINING', addShopTraining)])
  yield all([takeEvery('EDIT_SHOP_TRAINING', editShopTraining)])
  yield all([takeEvery('CREATE_SHOP_TRAINING', createShopTraining)])
  yield all([takeEvery('UPDATE_SHOP_TRAINING', updateShopTraining)])
  yield all([takeEvery('DELETE_SHOP_TRAINING', deleteShopTraining)])
}

export function * shopTrainings ({payload: {params}}) {
  try {
    const res = yield call(shopTrainingApi.shopTrainings, params)
    yield put(getShopTrainingsSucceed(res.data))
  } catch (error) {
    yield put(getShopTrainingsFailed(error))
  }
}

export function * addShopTraining ({payload: {params}}) {
  try {
    const res = yield call(shopTrainingApi.addShopTraining, params)
    yield put(addShopTrainingSucceed(res.data))
  } catch (error) {
    yield put(addShopTrainingFailed(error))
  }
}

export function * editShopTraining ({payload: {id}}) {
  try {
    const res = yield call(shopTrainingApi.editShopTraining, id)
    yield put(editShopTrainingSucceed(res.data))
  } catch (error) {
    yield put(editShopTrainingFailed(error))
  }
}

export function * createShopTraining () {
  try {
    const res = yield call(shopTrainingApi.createShopTraining)
    yield put(createShopTrainingSucceed(res.data))
  } catch (error) {
    yield put(createShopTrainingFailed(error))
  }
}

export function * updateShopTraining ({payload: {id, params}}) {
  try {
    const res = yield call(shopTrainingApi.updateShopTraining, id, params)
    yield put(updateShopTrainingSucceed(res.data))
  } catch (error) {
    yield put(updateShopTrainingFailed(error))
  }
}

export function * deleteShopTraining ({payload: {id}}) {
  try {
    const res = yield call(shopTrainingApi.deleteShopTraining, id)
    yield put(deleteShopTrainingSucceed(res.data))
  } catch (error) {
    yield put(deleteShopTrainingFailed(error))
  }
}