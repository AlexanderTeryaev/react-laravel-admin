import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getShopQuestionsFailed,
  getShopQuestionsSucceed,
  addShopQuestionFailed,
  addShopQuestionSucceed,
  editShopQuestionFailed,
  editShopQuestionSucceed,
  createShopQuestionFailed,
  createShopQuestionSucceed,
  updateShopQuestionFailed,
  updateShopQuestionSucceed,
  deleteShopQuestionFailed,
  deleteShopQuestionSucceed
} from './shopQuestionActions'

/** Import api */
import * as shopQuestionApi from './shopQuestionApi'

export function * shopQuestionSubscriber () {
  yield all([takeEvery('GET_SHOP_QUESTIONS',  shopQuestions)])
  yield all([takeEvery('ADD_SHOP_QUESTION', addShopQuestion)])
  yield all([takeEvery('EDIT_SHOP_QUESTION', editShopQuestion)])
  yield all([takeEvery('CREATE_SHOP_QUESTION', createShopQuestion)])
  yield all([takeEvery('UPDATE_SHOP_QUESTION', updateShopQuestion)])
  yield all([takeEvery('DELETE_SHOP_QUESTION', deleteShopQuestion)])
}

export function * shopQuestions ({payload: {params}}) {
  try {
    const res = yield call(shopQuestionApi.shopQuestions, params)
    yield put(getShopQuestionsSucceed(res.data))
  } catch (error) {
    yield put(getShopQuestionsFailed(error))
  }
}

export function * addShopQuestion ({payload: {params}}) {
  try {
    const res = yield call(shopQuestionApi.addShopQuestion, params)
    yield put(addShopQuestionSucceed(res.data))
  } catch (error) {
    yield put(addShopQuestionFailed(error))
  }
}

export function * editShopQuestion ({payload: {id}}) {
  try {
    const res = yield call(shopQuestionApi.editShopQuestion, id)
    yield put(editShopQuestionSucceed(res.data))
  } catch (error) {
    yield put(editShopQuestionFailed(error))
  }
}

export function * createShopQuestion () {
  try {
    const res = yield call(shopQuestionApi.createShopQuestion)
    yield put(createShopQuestionSucceed(res.data))
  } catch (error) {
    yield put(createShopQuestionFailed(error))
  }
}

export function * updateShopQuestion ({payload: {id, params}}) {
  try {
    const res = yield call(shopQuestionApi.updateShopQuestion, id, params)
    yield put(updateShopQuestionSucceed(res.data))
  } catch (error) {
    yield put(updateShopQuestionFailed(error))
  }
}

export function * deleteShopQuestion ({payload: {id}}) {
  try {
    const res = yield call(shopQuestionApi.deleteShopQuestion, id)
    yield put(deleteShopQuestionSucceed(res.data))
  } catch (error) {
    yield put(deleteShopQuestionFailed(error))
  }
}
