import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getUsersSucceed,
  getUsersFailed,
  updateUserFailed,
  updateUserSucceed,
  showUserSucceed,
  showUserFailed,
  leftGroupSucceed,
  leftGroupFailed,
  trainingDocSucceed,
  trainingDocFailed,
} from './userActions'

/** Import api */
import * as userApi from './userApi'

export function * userSubscriber () {
  yield all([takeEvery('GET_USERS', users)])
  yield all([takeEvery('UPDATE_USER', updateUser)])
  yield all([takeEvery('SHOW_USER', showUser)])
  yield all([takeEvery('LEFT_GROUP', leftGroup)])
  yield all([takeEvery('TRAINING_DOC', trainingDoc)])
}

export function * users ({payload: {params}}) {
  try {
    const users = yield call(userApi.users, params)
    yield put(getUsersSucceed(users.data))
  } catch (error) {
    yield put(getUsersFailed(error))
  }
}

export function * showUser ({ payload: { id } }) {
  try {
    const user = yield call(userApi.showUser, id)
    yield put(showUserSucceed(user.data))
  } catch (error) {
    yield put(showUserFailed(error))
  }
}

export function * updateUser ({ payload: { id, params } }) {
  try {
    const user = yield call(userApi.updateUser, id, params)
    yield put(updateUserSucceed(user))
  } catch (error) {
    yield put(updateUserFailed(error))
  }
}

export function * leftGroup ({ payload: { id, group_id } }) {
  try {
    const response = yield call(userApi.leftGroup, id, group_id)
    yield put(leftGroupSucceed(response))
  } catch (error) {
    yield put(leftGroupFailed(error))
  }
}

export function * trainingDoc ({ payload: { id, group_id } }) {
  try {
    const response = yield call(userApi.trainingDoc, id, group_id)
    yield put(trainingDocSucceed(response))
  } catch (error) {
    yield put(trainingDocFailed(error))
  }
}
