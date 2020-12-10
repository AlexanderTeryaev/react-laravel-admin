import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getShopQuizzesFailed,
  getShopQuizzesSucceed,
  createShopQuizzFailed,
  createShopQuizzSucceed,
  addShopQuizzFailed,
  addShopQuizzSucceed,
  showShopQuizzFailed,
  showShopQuizzSucceed,
  editShopQuizzFailed,
  editShopQuizzSucceed, 
  updateShopQuizzSucceed,
  updateShopQuizzFailed,
  updateImageSucceed,
  updateImageFailed,
} from './shopQuizzActions'

/** Import api */
import * as shopQuizzApi from './shopQuizzApi'

export function * shopQuizzSubscriber () {
  yield all([takeEvery('GET_SHOP_QUIZZES', shopQuizzes)])
  yield all([takeEvery('CREATE_SHOP_QUIZZ', createShopQuizz)])
  yield all([takeEvery('ADD_SHOP_QUIZZ', addShopQuizz)])
  yield all([takeEvery('SHOW_SHOP_QUIZZ', showShopQuizz)])
  yield all([takeEvery('EDIT_SHOP_QUIZZ', editShopQuizz)])
  yield all([takeEvery('UPDATE_SHOP_QUIZZ', updateShopQuizz)])
  yield all([takeEvery('UPDATE_IMAGE', updateImage)])
}

export function * shopQuizzes ({payload: {params}}) {
  try {
    const shopQuizzes = yield call(shopQuizzApi.shopQuizzes, params)
    yield put(getShopQuizzesSucceed(shopQuizzes.data))
  } catch (error) {
    yield put(getShopQuizzesFailed(error))
  }
}

export function * createShopQuizz () {
  try {
    const res = yield call(shopQuizzApi.createShopQuizz)
    yield put(createShopQuizzSucceed(res.data))
  } catch (error) {
    yield put(createShopQuizzFailed(error))
  }
}

export function * addShopQuizz ({payload: {params}}) {
  try {
    const shopQuizz = yield call(shopQuizzApi.addShopQuizz, params)
    yield put(addShopQuizzSucceed(shopQuizz.data))
  } catch (error) {
    yield put(addShopQuizzFailed(error))
  }
}

export function * showShopQuizz ({payload: {id}}) {
  try {
    const shopQuizz = yield call(shopQuizzApi.showShopQuizz, id)
    yield put(showShopQuizzSucceed(shopQuizz.data))
  } catch (error) {
    yield put(showShopQuizzFailed(error))
  }
}

export function * editShopQuizz ({payload: {id}}) {
  try {
    const shopQuizz = yield call(shopQuizzApi.editShopQuizz, id)
    yield put(editShopQuizzSucceed(shopQuizz.data))
  } catch (error) {
    yield put(editShopQuizzFailed(error))
  }
}

export function * updateShopQuizz ({payload: {id, params}}) {
  try {
    const shopQuizz = yield call(shopQuizzApi.updateShopQuizz, id, params)
    yield put(updateShopQuizzSucceed(shopQuizz.data))
  } catch (error) {
    yield put(updateShopQuizzFailed(error))
  }
}

export function * updateImage ({payload: {id, params}}) {
  try {
    const res = yield call(shopQuizzApi.updateImage, id, params)
    yield put(updateImageSucceed(res.data))
  } catch (error) {
    yield put(updateImageFailed(error))
  }
}
