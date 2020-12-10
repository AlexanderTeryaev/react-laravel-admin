import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getImportQuestionsFailed,
  getImportQuestionsSucceed,
  addImportQuestionFailed,
  addImportQuestionSucceed,
  deleteImportQuestionSucceed,
  deleteImportQuestionFailed,
  finishImportQuestionsFailed,
  finishImportQuestionsSucceed,
  deleteImportQuestionsFailed,
  deleteImportQuestionsSucceed

} from './importQuestionActions'

/** Import api */
import * as importQuestionApi from './importQuestionApi'

export function * importQuestionSubscriber () {
  yield all([takeEvery('GET_IMPORT_QUESTIONS',  importQuestions)])
  yield all([takeEvery('ADD_IMPORT_QUESTION', addImportQuestion)])
  yield all([takeEvery('DELETE_IMPORT_QUESTION', deleteImportQuestion)])
  yield all([takeEvery('FINISH_IMPORT_QUESTIONS', finishImportQuestions)])
  yield all([takeEvery('DELETE_IMPORT_QUESTIONS', deleteImportQuestions)])
}

export function * importQuestions ({payload: {params}}) {
  try {
    const res = yield call(importQuestionApi.importQuestions, params)
    yield put(getImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(getImportQuestionsFailed(error))
  }
}

export function * addImportQuestion ({payload: {params}}) {
  try {
    const res = yield call(importQuestionApi.addImportQuestion, params)
    yield put(addImportQuestionSucceed(res.data))
  } catch (error) {
    yield put(addImportQuestionFailed(error))
  }
}

export function * deleteImportQuestion ({payload: {id}}) {
  try {
    const res = yield call(importQuestionApi.deleteImportQuestion, id)
    yield put(deleteImportQuestionSucceed(res.data))
  } catch (error) {
    yield put(deleteImportQuestionFailed(error))
  }
}

export function * finishImportQuestions () {
  try {
    const res = yield call(importQuestionApi.finishImportQuestions)
    yield put(finishImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(finishImportQuestionsFailed(error))
  }
}

export function * deleteImportQuestions () {
  try {
    const res = yield call(importQuestionApi.deleteImportQuestions)
    yield put(deleteImportQuestionsSucceed(res.data))
  } catch (error) {
    yield put(deleteImportQuestionsFailed(error))
  }
}
