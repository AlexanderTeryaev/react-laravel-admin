import { createActions } from 'redux-actions';

const {
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
  deleteReportFailed,
} = createActions({
  GET_REPORTS: (params) => ({params}),
  GET_REPORTS_FAILED: error => ({ error }),
  GET_REPORTS_SUCCEED: reports => ({ reports }),
  ADD_REPORT: (params) => ({params}),
  ADD_REPORT_FAILED: error => ({ error }),
  ADD_REPORT_SUCCEED: report => ({ report }),
  EDIT_REPORT: (id) => ({id}),
  EDIT_REPORT_FAILED: error => ({ error }),
  EDIT_REPORT_SUCCEED: report => ({ report }),
  CREATE_REPORT: () => ({}),
  CREATE_REPORT_FAILED: error => ({ error }),
  CREATE_REPORT_SUCCEED: report => ({ report }),
  UPDATE_REPORT: (id, params) => ({id: id, params: params}),
  UPDATE_REPORT_FAILED: error => ({ error }),
  UPDATE_REPORT_SUCCEED: id => ({ id }),
  DELETE_REPORT: (id) => ({id}),
  DELETE_REPORT_SUCCEED: (id) => ({id}),
  DELETE_REPORT_FAILED: (error) => ({error}),
});

export {
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
  deleteReportFailed,
};
