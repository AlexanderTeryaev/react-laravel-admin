import { put, takeEvery, call, all } from 'redux-saga/effects';

/** Import actions */
import {
  getDashboardSucceed,
  getDashboardFailed,
  getUserInstallationFailed,
  getUserInstallationSucceed,
  getQuestionsAnswersFailed,
  getQuestionsAnswersSucceed,
  getRepartitionFailed,
  getRepartitionSucceed,
} from './dashboardActions';

/** Import api */
import * as dashboardApi from './dashboardApi';

export function* dashboardSubscriber() {
  yield all([takeEvery('GET_DASHBOARD',  dashboard)]);
  yield all([takeEvery('GET_USER_INSTALLATION',  userInstallation)]);
  yield all([takeEvery('GET_QUESTIONS_ANSWERS',  questionsAnswers)]);
  yield all([takeEvery('GET_REPARTITION',  repartition)]);
}

export function* dashboard() {
  try {
    const dashboard = yield call(dashboardApi.dashboard);
    yield put(getDashboardSucceed(dashboard.data));
  } catch (error) {
    yield put(getDashboardFailed(error));
  }
}

export function* userInstallation() {
  try {
    const userInstallation = yield call(dashboardApi.userInstallation);
    yield put(getUserInstallationSucceed(userInstallation.data));
  } catch (error) {
    yield put(getUserInstallationFailed(error));
  }
}

export function* questionsAnswers() {
  try {
    const questionsAnswers = yield call(dashboardApi.questionsAnswers);
    yield put(getQuestionsAnswersSucceed(questionsAnswers.data));
  } catch (error) {
    yield put(getQuestionsAnswersFailed(error));
  }
}

export function* repartition() {
  try {
    const repartition = yield call(dashboardApi.repartition);
    yield put(getRepartitionSucceed(repartition.data));
  } catch (error) {
    yield put(getRepartitionFailed(error));
  }
}