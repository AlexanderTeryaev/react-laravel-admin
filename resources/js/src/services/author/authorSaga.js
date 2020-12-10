import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getAuthorsFailed,
  getAuthorsSucceed,
  addAuthorFailed,
  addAuthorSucceed,
  editAuthorFailed,
  editAuthorSucceed,
  createAuthorFailed,
  createAuthorSucceed,
  updateAuthorFailed,
  updateAuthorSucceed,
  showAuthorFailed,
  showAuthorSucceed,
} from './authorActions'

/** Import api */
import * as authorApi from './authorApi'

export function * authorSubscriber () {
  yield all([takeEvery('GET_AUTHORS',  authors)])
  yield all([takeEvery('ADD_AUTHOR', addAuthor)])
  yield all([takeEvery('EDIT_AUTHOR', editAuthor)])
  yield all([takeEvery('CREATE_AUTHOR', createAuthor)])
  yield all([takeEvery('UPDATE_AUTHOR', updateAuthor)])
  yield all([takeEvery('SHOW_AUTHOR', showAuthor)])
}

export function * authors ({payload: {params}}) {
  try {
    const res = yield call(authorApi.authors, params)
    yield put(getAuthorsSucceed(res.data))
  } catch (error) {
    yield put(getAuthorsFailed(error))
  }
}

export function * addAuthor ({payload: {params}}) {
  try {
    const res = yield call(authorApi.addAuthor, params)
    yield put(addAuthorSucceed(res.data))
  } catch (error) {
    yield put(addAuthorFailed(error))
  }
}

export function * editAuthor ({payload: {id}}) {
  try {
    const res = yield call(authorApi.editAuthor, id)
    yield put(editAuthorSucceed(res.data))
  } catch (error) {
    yield put(editAuthorFailed(error))
  }
}

export function * createAuthor () {
  try {
    const res = yield call(authorApi.createAuthor)
    yield put(createAuthorSucceed(res.data))
  } catch (error) {
    yield put(createAuthorFailed(error))
  }
}

export function * updateAuthor ({payload: {id, params}}) {
  try {
    const res = yield call(authorApi.updateAuthor, id, params)
    yield put(updateAuthorSucceed(res.data))
  } catch (error) {
    yield put(updateAuthorFailed(error))
  }
}

export function * showAuthor ({payload: {id}}) {
  try {
    const res = yield call(authorApi.showAuthor, id)
    yield put(showAuthorSucceed(res.data))
  } catch (error) {
    yield put(showAuthorFailed(error))
  }
}
