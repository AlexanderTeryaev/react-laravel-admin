import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getQuestionsFailed,
  getQuestionsSucceed,
  addQuestionFailed,
  addQuestionSucceed,
  editQuestionFailed,
  editQuestionSucceed,
  createQuestionFailed,
  createQuestionSucceed,
  updateQuestionFailed,
  updateQuestionSucceed,
  deleteQuestionFailed,
  deleteQuestionSucceed
} from './questionActions'

/** Import api */
import * as questionApi from './questionApi'

export function * questionSubscriber () {
  yield all([takeEvery('GET_QUESTIONS',  questions)])
  yield all([takeEvery('ADD_QUESTION', addQuestion)])
  yield all([takeEvery('EDIT_QUESTION', editQuestion)])
  yield all([takeEvery('CREATE_QUESTION', createQuestion)])
  yield all([takeEvery('UPDATE_QUESTION', updateQuestion)])
  yield all([takeEvery('DELETE_QUESTION', deleteQuestion)])
}

export function * questions ({payload: {params}}) {
  try {
    const res = yield call(questionApi.questions, params)
    yield put(getQuestionsSucceed(res.data))
  } catch (error) {
    yield put(getQuestionsFailed(error))
  }
}

export function * addQuestion ({payload: {params}}) {
  try {
    const res = yield call(questionApi.addQuestion, params)
    yield put(addQuestionSucceed(res.data))
  } catch (error) {
    yield put(addQuestionFailed(error))
  }
}

export function * editQuestion ({payload: {id}}) {
  try {
    const res = yield call(questionApi.editQuestion, id)
    yield put(editQuestionSucceed(res.data))
  } catch (error) {
    yield put(editQuestionFailed(error))
  }
}

export function * createQuestion () {
  try {
    const res = yield call(questionApi.createQuestion)
    yield put(createQuestionSucceed(res.data))
  } catch (error) {
    yield put(createQuestionFailed(error))
  }
}

export function * updateQuestion ({payload: {id, params}}) {
  try {
    const res = yield call(questionApi.updateQuestion, id, params)
    yield put(updateQuestionSucceed(res.data))
  } catch (error) {
    yield put(updateQuestionFailed(error))
  }
}

export function * deleteQuestion ({payload: {id}}) {
  try {
    const res = yield call(questionApi.deleteQuestion, id)
    yield put(deleteQuestionSucceed(res.data))
  } catch (error) {
    yield put(deleteQuestionFailed(error))
  }
}
