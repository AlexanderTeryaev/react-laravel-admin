import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getQuizzesFailed,
  getQuizzesSucceed,
  createQuizzSucceed, 
  createQuizzFailed,
  addQuizzFailed,
  addQuizzSucceed,
  showQuizzFailed,
  showQuizzSucceed,
  editQuizzFailed,
  editQuizzSucceed, 
  updateQuizzSucceed,
  updateQuizzFailed,
  updateImageSucceed,
  updateImageFailed,
} from './quizzActions'

/** Import api */
import * as quizzApi from './quizzApi'

export function * quizzSubscriber () {
  yield all([takeEvery('GET_QUIZZES', quizzes)])
  yield all([takeEvery('CREATE_QUIZZ', createQuizz)])
  yield all([takeEvery('ADD_QUIZZ', addQuizz)])
  yield all([takeEvery('SHOW_QUIZZ', showQuizz)])
  yield all([takeEvery('EDIT_QUIZZ', editQuizz)])
  yield all([takeEvery('UPDATE_QUIZZ', updateQuizz)])
  yield all([takeEvery('UPDATE_IMAGE', updateImage)])
}

export function * quizzes ({payload: {params}}) {
  try {
    const quizzes = yield call(quizzApi.quizzes, params)
    yield put(getQuizzesSucceed(quizzes.data))
  } catch (error) {
    yield put(getQuizzesFailed(error))
  }
}

export function * createQuizz () {
  try {
    const res = yield call(quizzApi.createQuizz)
    yield put(createQuizzSucceed(res.data))
  } catch (error) {
    yield put(createQuizzFailed(error))
  }
}

export function * addQuizz ({payload: {params}}) {
  try {
    const quizz = yield call(quizzApi.addQuizz, params)
    yield put(addQuizzSucceed(quizz.data))
  } catch (error) {
    yield put(addQuizzFailed(error))
  }
}

export function * showQuizz ({payload: {id}}) {
  try {
    const quizz = yield call(quizzApi.showQuizz, id)
    yield put(showQuizzSucceed(quizz.data))
  } catch (error) {
    yield put(showQuizzFailed(error))
  }
}

export function * editQuizz ({payload: {id}}) {
  try {
    const quizz = yield call(quizzApi.editQuizz, id)
    yield put(editQuizzSucceed(quizz.data))
  } catch (error) {
    yield put(editQuizzFailed(error))
  }
}

export function * updateQuizz ({payload: {id, params}}) {
  try {
    const quizz = yield call(quizzApi.updateQuizz, id, params)
    yield put(updateQuizzSucceed(quizz.data))
  } catch (error) {
    yield put(updateQuizzFailed(error))
  }
}

export function * updateImage ({payload: {id, params}}) {
  try {
    const res = yield call(quizzApi.updateImage, id, params)
    yield put(updateImageSucceed(res.data))
  } catch (error) {
    yield put(updateImageFailed(error))
  }
}
