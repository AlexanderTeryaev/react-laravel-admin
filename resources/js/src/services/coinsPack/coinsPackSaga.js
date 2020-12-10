import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getCoinsPacksFailed,
  getCoinsPacksSucceed,
  addCoinsPackFailed,
  addCoinsPackSucceed,
  editCoinsPackFailed,
  editCoinsPackSucceed,
  createCoinsPackFailed,
  createCoinsPackSucceed,
  updateCoinsPackFailed,
  updateCoinsPackSucceed,
  showCoinsPackFailed,
  showCoinsPackSucceed,
  deleteCoinsPackFailed,
  deleteCoinsPackSucceed
} from './coinsPackActions'

/** Import api */
import * as coinsPackApi from './coinsPackApi'

export function * coinsPackSubscriber () {
  yield all([takeEvery('GET_COINS_PACKS',  coinsPacks)])
  yield all([takeEvery('ADD_COINS_PACK', addCoinsPack)])
  yield all([takeEvery('EDIT_COINS_PACK', editCoinsPack)])
  yield all([takeEvery('CREATE_COINS_PACK', createCoinsPack)])
  yield all([takeEvery('UPDATE_COINS_PACK', updateCoinsPack)])
  yield all([takeEvery('SHOW_COINS_PACK', showCoinsPack)])
  yield all([takeEvery('DELETE_COINS_PACK', deleteCoinsPack)])
}

export function * coinsPacks ({payload: {params}}) {
  try {
    const res = yield call(coinsPackApi.coinsPacks, params)
    yield put(getCoinsPacksSucceed(res.data))
  } catch (error) {
    yield put(getCoinsPacksFailed(error))
  }
}

export function * addCoinsPack ({payload: {params}}) {
  try {
    const res = yield call(coinsPackApi.addCoinsPack, params)
    yield put(addCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(addCoinsPackFailed(error))
  }
}

export function * editCoinsPack ({payload: {id}}) {
  try {
    const res = yield call(coinsPackApi.editCoinsPack, id)
    yield put(editCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(editCoinsPackFailed(error))
  }
}

export function * createCoinsPack () {
  try {
    const res = yield call(coinsPackApi.createCoinsPack)
    yield put(createCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(createCoinsPackFailed(error))
  }
}

export function * updateCoinsPack ({payload: {id, params}}) {
  try {
    const res = yield call(coinsPackApi.updateCoinsPack, id, params)
    yield put(updateCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(updateCoinsPackFailed(error))
  }
}

export function * showCoinsPack ({payload: {id}}) {
  try {
    const res = yield call(coinsPackApi.showCoinsPack, id)
    yield put(showCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(showCoinsPackFailed(error))
  }
}

export function * deleteCoinsPack ({payload: {id}}) {
  try {
    const res = yield call(coinsPackApi.deleteCoinsPack, id)
    yield put(deleteCoinsPackSucceed(res.data))
  } catch (error) {
    yield put(deleteCoinsPackFailed(error))
  }
}


