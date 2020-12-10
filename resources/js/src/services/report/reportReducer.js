import { handleActions } from 'redux-actions';

import {
  getReports,
  getReportsFailed,
  getReportsSucceed,
  addReport,
  addReportFailed,
  addReportSucceed,
  editReport,
  editReportFailed,
  editReportSucceed,
  createReport,
  createReportFailed,
  createReportSucceed,
  updateReport,
  updateReportFailed,
  updateReportSucceed, 
  deleteReport,
  deleteReportSucceed, 
  deleteReportFailed
  
} from './reportActions';

const defaultState = {
  reports: [],
  error: null,
  loading: true,
  reloading: false,
  report: null,
  id: null,
  status: false,
  total_count: 0,
};

const reducer = handleActions(
  {
    [getReports](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getReportsFailed](
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
    [getReportsSucceed](
      state,
      {
        payload: { reports }
      }
    ) {
      return {
        ...state,
        reports: reports.question_reports,
        total_count: reports.total_count,
        loading: false
      };
    },
    [addReport](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addReportFailed](
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
    [addReportSucceed] (
      state,
      {
        payload: { report }
      }
    ) {
      return {
        ...state,
        reports: [
          ...state.reports,
          report
        ],
        error: null,
        loading: false
      };
    },
    [editReport](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editReportFailed](
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
    [editReportSucceed] (
      state,
      {
        payload: { report }
      }
    ) {
      return {
        ...state,
        report: report,
        error: null,
        loading: false
      };
    },
    [createReport](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createReportFailed](
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
    [createReportSucceed] (
      state,
      {
        payload: { report }
      }
    ) {
      return {
        ...state,
        report: report,
        error: null,
        loading: false
      };
    },
    [updateReport](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateReportFailed](
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
    [updateReportSucceed] (
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
    [deleteReport](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteReportFailed](
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
    [deleteReportSucceed] (
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
