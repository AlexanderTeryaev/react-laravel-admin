import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getFeaturedsFailed,
  getFeaturedsSucceed,
  addFeaturedFailed,
  addFeaturedSucceed,
  editFeaturedFailed,
  editFeaturedSucceed,
  createFeaturedFailed,
  createFeaturedSucceed,
  updateFeaturedFailed,
  updateFeaturedSucceed,
  deleteFeaturedFailed,
  deleteFeaturedSucceed
} from './featuredActions'

/** Import api */
import * as featuredApi from './featuredApi'

export function * featuredSubscriber () {
  yield all([takeEvery('GET_FEATUREDS',  featureds)])
  yield all([takeEvery('ADD_FEATURED', addFeatured)])
  yield all([takeEvery('EDIT_FEATURED', editFeatured)])
  yield all([takeEvery('CREATE_FEATURED', createFeatured)])
  yield all([takeEvery('UPDATE_FEATURED', updateFeatured)])
  yield all([takeEvery('DELETE_FEATURED', deleteFeatured)])
}

export function * featureds ({payload: {params}}) {
  try {
    const res = yield call(featuredApi.featureds, params)
    yield put(getFeaturedsSucceed(res.data))
  } catch (error) {
    yield put(getFeaturedsFailed(error))
  }
}

export function * addFeatured ({payload: {params}}) {
  try {
    const res = yield call(featuredApi.addFeatured, params)
    yield put(addFeaturedSucceed(res.data))
  } catch (error) {
    yield put(addFeaturedFailed(error))
  }
}

export function * editFeatured ({payload: {id}}) {
  try {
    const res = yield call(featuredApi.editFeatured, id)
    yield put(editFeaturedSucceed(res.data))
  } catch (error) {
    yield put(editFeaturedFailed(error))
  }
}

export function * createFeatured () {
  try {
    const res = yield call(featuredApi.createFeatured)
    yield put(createFeaturedSucceed(res.data))
  } catch (error) {
    yield put(createFeaturedFailed(error))
  }
}

export function * updateFeatured ({payload: {id, params}}) {
  try {
    const res = yield call(featuredApi.updateFeatured, id, params)
    yield put(updateFeaturedSucceed(res.data))
  } catch (error) {
    yield put(updateFeaturedFailed(error))
  }
}

export function * deleteFeatured ({payload: {id}}) {
  try {
    const res = yield call(featuredApi.deleteFeatured, id)
    yield put(deleteFeaturedSucceed(res.data))
  } catch (error) {
    yield put(deleteFeaturedFailed(error))
  }
}