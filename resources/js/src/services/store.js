import { createStore, applyMiddleware, compose, combineReducers } from 'redux'
import { routerMiddleware } from 'react-router-redux'
import createSagaMiddleware from 'redux-saga'
import { reducer as formReducer } from 'redux-form'
import { reducer as toastrReducer } from 'react-redux-toastr'
// Import reducers
import * as reducers from '../services/reducer'

/**
 * Import Saga subscribers
 */

import {
  authSubscriber,
  dashboardSubscriber,
  groupSubscriber,
  userSubscriber,
  recipientSubscriber,
  featuredSubscriber,
  categorySubscriber,
  quizzSubscriber,
  questionSubscriber,
  importQuestionSubscriber,
  authorSubscriber,
  reportSubscriber,
  shopTrainingSubscriber,
  shopQuizzSubscriber,
  shopQuestionSubscriber,
  shopImportQuestionSubscriber,
  shopAuthorSubscriber,
  coinsPackSubscriber,
  planSubscriber,
  configSubscriber
} from '../services/saga'
import { all } from 'redux-saga/effects'

const initialState = {}
const enhancers = []
const sagaMiddleware = createSagaMiddleware()
const middleware = [sagaMiddleware, routerMiddleware(history)]

const composedEnhancers = compose(applyMiddleware(...middleware), ...enhancers)

const reducer = combineReducers({
  ...reducers,
  form: formReducer,
  toastr: toastrReducer
})

const store = createStore(reducer, initialState, composedEnhancers)

/**
 *
 * Run saga subscribers
 *
 */
function * rootSaga () {
  yield all([
    authSubscriber(),
    dashboardSubscriber(),
    userSubscriber(),
    groupSubscriber(),
    recipientSubscriber(),
    featuredSubscriber(),
    categorySubscriber(),
    quizzSubscriber(),
    questionSubscriber(),
    importQuestionSubscriber(),
    authorSubscriber(),
    reportSubscriber(),
    shopTrainingSubscriber(),
    shopQuizzSubscriber(),
    shopQuestionSubscriber(),
    shopImportQuestionSubscriber(),
    shopAuthorSubscriber(),
    coinsPackSubscriber(),
    planSubscriber(),
    configSubscriber()
  ])
}
sagaMiddleware.run(rootSaga)
export default store
