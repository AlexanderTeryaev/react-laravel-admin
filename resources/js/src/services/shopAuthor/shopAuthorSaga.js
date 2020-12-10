import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getShopAuthorsFailed,
  getShopAuthorsSucceed,
  addShopAuthorFailed,
  addShopAuthorSucceed,
  editShopAuthorFailed,
  editShopAuthorSucceed,
  createShopAuthorFailed,
  createShopAuthorSucceed,
  updateShopAuthorFailed,
  updateShopAuthorSucceed,
  showShopAuthorFailed,
  showShopAuthorSucceed,
  deleteShopAuthorFailed,
  deleteShopAuthorSucceed
} from './shopAuthorActions'

/** Import api */
import * as shopAuthorApi from './shopAuthorApi'

export function * shopAuthorSubscriber () {
  yield all([takeEvery('GET_SHOP_AUTHORS',  shopAuthors)])
  yield all([takeEvery('ADD_SHOP_AUTHOR', addShopAuthor)])
  yield all([takeEvery('EDIT_SHOP_AUTHOR', editShopAuthor)])
  yield all([takeEvery('CREATE_SHOP_AUTHOR', createShopAuthor)])
  yield all([takeEvery('UPDATE_SHOP_AUTHOR', updateShopAuthor)])
  yield all([takeEvery('SHOW_SHOP_AUTHOR', showShopAuthor)])
  yield all([takeEvery('DELETE_SHOP_AUTHOR', deleteShopAuthor)])
}

export function * shopAuthors ({payload: {params}}) {
  try {
    const res = yield call(shopAuthorApi.shopAuthors, params)
    yield put(getShopAuthorsSucceed(res.data))
  } catch (error) {
    yield put(getShopAuthorsFailed(error))
  }
}

export function * addShopAuthor ({payload: {params}}) {
  try {
    const res = yield call(shopAuthorApi.addShopAuthor, params)
    yield put(addShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(addShopAuthorFailed(error))
  }
}

export function * editShopAuthor ({payload: {id}}) {
  try {
    const res = yield call(shopAuthorApi.editShopAuthor, id)
    yield put(editShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(editShopAuthorFailed(error))
  }
}

export function * createShopAuthor () {
  try {
    const res = yield call(shopAuthorApi.createShopAuthor)
    yield put(createShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(createShopAuthorFailed(error))
  }
}

export function * updateShopAuthor ({payload: {id, params}}) {
  try {
    const res = yield call(shopAuthorApi.updateShopAuthor, id, params)
    yield put(updateShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(updateShopAuthorFailed(error))
  }
}

export function * showShopAuthor ({payload: {id}}) {
  try {
    const res = yield call(shopAuthorApi.showShopAuthor, id)
    yield put(showShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(showShopAuthorFailed(error))
  }
}

export function * deleteShopAuthor ({payload: {id}}) {
  try {
    const res = yield call(shopAuthorApi.deleteShopAuthor, id)
    yield put(deleteShopAuthorSucceed(res.data))
  } catch (error) {
    yield put(deleteShopAuthorFailed(error))
  }
}


