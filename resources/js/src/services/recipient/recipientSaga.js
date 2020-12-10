import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getRecipientsSucceed,
  getRecipientsFailed,
  addRecipientFailed,
  addRecipientSucceed,
  deleteRecipientFailed,
  deleteRecipientSucceed,
} from './recipientActions'

/** Import api */
import * as recipientApi from './recipientApi'

export function * recipientSubscriber () {
  yield all([takeEvery('GET_RECIPIENTS', recipients)])
  yield all([takeEvery('ADD_RECIPIENT', addRecipient)])
  yield all([takeEvery('DELETE_RECIPIENT', deleteRecipient)])
}

export function * recipients ({payload: {params}}) {
  try {
    const recipients = yield call(recipientApi.recipients, params)
    yield put(getRecipientsSucceed(recipients.data))
  } catch (error) {
    yield put(getRecipientsFailed(error))
  }
}

export function * addRecipient ({payload: {params}}) {
  try {
    const recipient = yield call(recipientApi.addRecipient, params)
    yield put(addRecipientSucceed(recipient.data))
  } catch (error) {
    yield put(addRecipientFailed(error))
  }
}

export function * deleteRecipient ({payload: {id}}) {
  try {
    const resp = yield call(recipientApi.deleteRecipient, id)
    yield put(deleteRecipientSucceed(resp))
  } catch (error) {
    yield put(deleteRecipientFailed(error))
  }
}
