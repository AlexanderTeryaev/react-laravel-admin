import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getShopImportQuestionsFailed,
  getShopImportQuestionsSucceed,
  addShopImportQuestionFailed,
  addShopImportQuestionSucceed,
  deleteShopImportQuestionSucceed,
  deleteShopImportQuestionFailed,
  finishShopImportQuestionsFailed,
  finishShopImportQuestionsSucceed,
  deleteShopImportQuestionsFailed,
  deleteShopImportQuestionsSucceed

} from './shopImportQuestionActions'

/** Import api */
import * as shopImportQuestionApi from './shopImportQuestionApi'

export function * shopImportQuestionSubscriber () {
  yield all([takeEvery('GET_SHOP_IMPORT_QUESTIONS',  shopImportQuestions)])
  yield all([takeEvery('ADD_SHOP_IMPORT_QUESTION', addShopImportQuestion)])
  yield all([takeEvery('DELETE_SHOP_IMPORT_QUESTION', deleteShopImportQuestion)])
  yield all([takeEvery('FINISH_SHOP_IMPORT_QUESTIONS', finishShopImportQuestions)])
  yield all([takeEvery('DELETE_SHOP_IMPORT_QUESTIONS', deleteShopImportQuestions)])
}

export function * shopImportQuestions ({payload: {params}}) {
  try {
    const res = yield call(shopImportQuestionApi.shopImportQuestions, params)
    yield put(getShopImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(getShopImportQuestionsFailed(error))
  }
}

export function * addShopImportQuestion ({payload: {params}}) {
  try {
    const res = yield call(shopImportQuestionApi.addShopImportQuestion, params)
    yield put(addShopImportQuestionSucceed(res.data))
  } catch (error) {
    yield put(addShopImportQuestionFailed(error))
  }
}

export function * deleteShopImportQuestion ({payload: {id}}) {
  try {
    const res = yield call(shopImportQuestionApi.deleteShopImportQuestion, id)
    yield put(deleteShopImportQuestionSucceed(res.data))
  } catch (error) {
    yield put(deleteShopImportQuestionFailed(error))
  }
}

export function * finishShopImportQuestions () {
  try {
    const res = yield call(shopImportQuestionApi.finishShopImportQuestions)
    yield put(finishShopImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(finishShopImportQuestionsFailed(error))
  }
}

export function * deleteShopImportQuestions () {
  try {
    const res = yield call(shopImportQuestionApi.deleteShopImportQuestions)
    yield put(deleteShopImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(deleteShopImportQuestionsFailed(error))
  }
}
