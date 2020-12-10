import { createActions } from 'redux-actions';

const {
  getDashboard,
  getDashboardFailed,
  getDashboardSucceed,
  getUserInstallation,
  getUserInstallationFailed,
  getUserInstallationSucceed,
  getQuestionsAnswers,
  getQuestionsAnswersFailed,
  getQuestionsAnswersSucceed,
  getRepartition,
  getRepartitionFailed,
  getRepartitionSucceed,
} = createActions({
  GET_DASHBOARD: () => ({}),
  GET_DASHBOARD_FAILED: error => ({ error }),
  GET_DASHBOARD_SUCCEED: dashboard => ({ dashboard }),
  GET_USER_INSTALLATION: () => ({}),
  GET_USER_INSTALLATION_FAILED: error => ({ error }),
  GET_USER_INSTALLATION_SUCCEED: userInstallation => ({ userInstallation }),
  GET_QUESTIONS_ANSWERS: () => ({}),
  GET_QUESTIONS_ANSWERS_FAILED: error => ({ error }),
  GET_QUESTIONS_ANSWERS_SUCCEED: questionsAnswers => ({ questionsAnswers }),
  GET_REPARTITION: () => ({}),
  GET_REPARTITION_FAILED: error => ({ error }),
  GET_REPARTITION_SUCCEED: repartition => ({ repartition }),
});

export {
  getDashboard,
  getDashboardFailed,
  getDashboardSucceed,
  getUserInstallation,
  getUserInstallationFailed,
  getUserInstallationSucceed,
  getQuestionsAnswers,
  getQuestionsAnswersFailed,
  getQuestionsAnswersSucceed,
  getRepartition,
  getRepartitionFailed,
  getRepartitionSucceed,
};
