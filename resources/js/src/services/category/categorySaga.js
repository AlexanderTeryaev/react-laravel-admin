import { put, takeEvery, call, all } from 'redux-saga/effects'

/** Import actions */
import {
  getCategoriesFailed,
  getCategoriesSucceed,
  addCategoryFailed,
  addCategorySucceed,
  editCategoryFailed,
  editCategorySucceed,
  createCategoryFailed,
  createCategorySucceed,
  updateCategoryFailed,
  updateCategorySucceed,
  deleteCategorySucceed,
  deleteCategoryFailed,
} from './categoryActions'

/** Import api */
import * as categoryApi from './categoryApi'

export function * categorySubscriber () {
  yield all([takeEvery('GET_CATEGORIES',  categories)])
  yield all([takeEvery('ADD_CATEGORY', addCategory)])
  yield all([takeEvery('EDIT_CATEGORY', editCategory)])
  yield all([takeEvery('CREATE_CATEGORY', createCategory)])
  yield all([takeEvery('UPDATE_CATEGORY', updateCategory)])
  yield all([takeEvery('DELETE_CATEGORY', deleteCategory)])
}

export function * categories ({payload: {params}}) {
  try {
    const res = yield call(categoryApi.categories, params)
    yield put(getCategoriesSucceed(res.data))
  } catch (error) {
    yield put(getCategoriesFailed(error))
  }
}

export function * addCategory ({payload: {params}}) {
  try {
    const res = yield call(categoryApi.addCategory, params)
    yield put(addCategorySucceed(res.data))
  } catch (error) {
    yield put(addCategoryFailed(error))
  }
}

export function * editCategory ({payload: {id}}) {
  try {
    const res = yield call(categoryApi.editCategory, id)
    yield put(editCategorySucceed(res.data))
  } catch (error) {
    yield put(editCategoryFailed(error))
  }
}

export function * createCategory () {
  try {
    const res = yield call(categoryApi.createCategory)
    yield put(createCategorySucceed(res.data))
  } catch (error) {
    yield put(createCategoryFailed(error))
  }
}

export function * updateCategory ({payload: {id, params}}) {
  try {
    const res = yield call(categoryApi.updateCategory, id, params)
    yield put(updateCategorySucceed(res.data))
  } catch (error) {
    yield put(updateCategoryFailed(error))
  }
}

export function * deleteCategory ({payload: {id}}) {
  try {
    const res = yield call(categoryApi.deleteCategory, id)
    yield put(deleteCategorySucceed(res.data))
  } catch (error) {
    yield put(deleteCategoryFailed(error))
  }
}