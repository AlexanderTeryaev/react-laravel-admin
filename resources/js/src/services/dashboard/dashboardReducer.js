import { handleActions } from 'redux-actions';

import {
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

} from './dashboardActions';
// import { dashboard } from './dashboardSaga';

const defaultState = {
    dashboard: {
        users: [],
        groups: [],
        quizzes: [],
    },
  userInstallation: {
      months: [],
      users_nb: []
  },
  questionsAnswers: {
        months: []
  },
  repartition: {
      repartition: []
  },
  error: null,
  loading: true,
};

const reducer = handleActions(
  {
    [getDashboard](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getDashboardFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        ...error,
        loading: false
      };
    },
    [getDashboardSucceed](
      state,
      {
        payload: { dashboard }
      }
    ) {
      return {
        ...state,
        dashboard: dashboard,
        error: null,
        loading: false
      };
    },

    [getUserInstallation](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getUserInstallationFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        ...error,
        loading: false
      };
    },
    [getUserInstallationSucceed](
      state,
      {
        payload: { userInstallation }
      }
    ) {
      return {
        ...state,
        userInstallation: userInstallation,
        error: null,
        loading: false
      };
    },

    [getQuestionsAnswers](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getQuestionsAnswersFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        ...error,
        loading: false
      };
    },
    [getQuestionsAnswersSucceed](
      state,
      {
        payload: { questionsAnswers }
      }
    ) {
      return {
        ...state,
        questionsAnswers: questionsAnswers,
        error: null,
        loading: false
      };
    },

    [getRepartition](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getRepartitionFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        ...error,
        loading: false
      };
    },
    [getRepartitionSucceed](
      state,
      {
        payload: { repartition }
      }
    ) {
      return {
        ...state,
        repartition: repartition,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
