import { handleActions } from 'redux-actions';

import {
  getImportQuestions,
  getImportQuestionsFailed,
  getImportQuestionsSucceed,
  addImportQuestion,
  addImportQuestionFailed,
  addImportQuestionSucceed,
  deleteImportQuestion,
  deleteImportQuestionSucceed,
  deleteImportQuestionFailed,
  finishImportQuestions,
  finishImportQuestionsFailed,
  finishImportQuestionsSucceed,
  deleteImportQuestions,
  deleteImportQuestionsFailed,
  deleteImportQuestionsSucceed,
  
} from './importQuestionActions';

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
    [getImportQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getImportQuestionsFailed](
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
    [getImportQuestionsSucceed](
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
    [addImportQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addImportQuestionFailed](
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
    [addImportQuestionSucceed] (
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
    [deleteImportQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [deleteImportQuestionFailed](
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
    [deleteImportQuestionSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      let questions = JSON.parse(JSON.stringify(state.questions));
      questions.forEach((item, index) => {
        if (item.id == id) {
          questions.splice(index, 1);
        }
      });
      return {
        ...state,
        questions: questions,
        error: null,
        loading: false
      };
    },
    [finishImportQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [finishImportQuestionsFailed](
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
    [finishImportQuestionsSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        loading: false,
        reloading: true
      };
    },
    [deleteImportQuestions](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteImportQuestionsFailed](
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
    [deleteImportQuestionsSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        reloading: true
      };
    },
  },
  defaultState
);

export default reducer;
