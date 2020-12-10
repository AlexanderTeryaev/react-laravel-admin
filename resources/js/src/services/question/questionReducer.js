import { handleActions } from 'redux-actions';

import {
  getQuestions,
  getQuestionsFailed,
  getQuestionsSucceed,
  addQuestion,
  addQuestionFailed,
  addQuestionSucceed,
  editQuestion,
  editQuestionFailed,
  editQuestionSucceed,
  createQuestion,
  createQuestionFailed,
  createQuestionSucceed,
  updateQuestion,
  updateQuestionFailed,
  updateQuestionSucceed, 
  deleteQuestion,
  deleteQuestionSucceed, 
  deleteQuestionFailed
  
} from './questionActions';

const defaultState = {
  questions: [],
  error: null,
  loading: true,
  reloading: false,
  question: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getQuestionsFailed](
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
    [getQuestionsSucceed](
      state,
      {
        payload: { questions }
      }
    ) {
      return {
        ...state,
        questions: questions.questions,
        total_count: questions.total_count,
        loading: false
      };
    },
    [addQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addQuestionFailed](
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
    [addQuestionSucceed] (
      state,
      {
        payload: { question }
      }
    ) {
      return {
        ...state,
        questions: [
          ...state.questions,
          question
        ],
        error: null,
        loading: false
      };
    },
    [editQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editQuestionFailed](
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
    [editQuestionSucceed] (
      state,
      {
        payload: { question }
      }
    ) {
      return {
        ...state,
        question: question,
        error: null,
        loading: false
      };
    },
    [createQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createQuestionFailed](
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
    [createQuestionSucceed] (
      state,
      {
        payload: { question }
      }
    ) {
      return {
        ...state,
        question: question,
        error: null,
        loading: false
      };
    },
    [updateQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateQuestionFailed](
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
    [updateQuestionSucceed] (
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
    [deleteQuestion](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteQuestionFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteQuestionSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        error: null,
        reloading: true
      };
    },
  },
  defaultState
);

export default reducer;
