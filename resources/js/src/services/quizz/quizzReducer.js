import { handleActions } from 'redux-actions';

import {
  getQuizzes,
  getQuizzesFailed,
  getQuizzesSucceed,
  createQuizz,
  createQuizzFailed,
  createQuizzSucceed,
  addQuizz,
  addQuizzFailed,
  addQuizzSucceed,
  showQuizz,
  showQuizzFailed,
  showQuizzSucceed,
  editQuizz,
  editQuizzFailed,
  editQuizzSucceed,
  updateQuizz,
  updateQuizzFailed,
  updateQuizzSucceed,
  updateImage,
  updateImageFailed,
  updateImageSucceed,
  
} from './quizzActions';

const defaultState = {
  quizzes: [],
  error: null,
  loading: true,
  reloading: false,
  quizz: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getQuizzes](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getQuizzesFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [getQuizzesSucceed](
      state,
      {
        payload: { quizzes }
      }
    ) {
      return {
        ...state,
        quizzes: quizzes.quizzes,
        total_count: quizzes.total_count,
        loading: false
      };
    },
    [createQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createQuizzFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [createQuizzSucceed](
      state,
      {
        payload: { quizz }
      }
    ) {
      return {
        ...state,
        quizz: quizz,
        loading: false
      };
    },
    [addQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addQuizzFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [addQuizzSucceed] (
      state,
      {
        payload: { quizz }
      }
    ) {
      return {
        ...state,
        quizzes: [
          ...state.quizzes,
          quizz
        ],
        error: null,
        loading: false
      };
    },
    [showQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showQuizzFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [showQuizzSucceed] (
      state,
      {
        payload: { quizz }
      }
    ) {
      return {
        ...state,
        quizz: quizz,
        error: null,
        loading: false
      };
    },
    [editQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editQuizzFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [editQuizzSucceed] (
      state,
      {
        payload: { quizz }
      }
    ) {
      return {
        ...state,
        quizz: quizz,
        error: null,
        loading: false
      };
    },
    [updateQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateQuizzFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false,
        reloading: true
      };
    },
    [updateQuizzSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        id: id,
        error: null,
        loading: false,
        reloading: true
      };
    },
    [updateImage](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [updateImageFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [updateImageSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
