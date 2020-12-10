import { error } from 'jquery';
import { handleActions } from 'redux-actions';

import {
  getRecipients,
  getRecipientsFailed,
  getRecipientsSucceed,
  addRecipient,
  addRecipientFailed,
  addRecipientSucceed,
  deleteRecipient,
  deleteRecipientFailed,
  deleteRecipientSucceed,
} from './recipientActions';
// import {  } from './recipientSaga';

const defaultState = {
  recipients: [],
  error: null,
  loading: true,
  reloading: false,
  recipient: null,
  id: null,
  total_count: 0
};

const reducer = handleActions(
  {
    [getRecipients](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getRecipientsFailed](
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
    [getRecipientsSucceed](
      state,
      {
        payload: { recipients }
      }
    ) {
      return {
        ...state,
        recipients: recipients.insight_recipients,
        total_count: recipients.total_count,
        loading: false
      };
    },
    [addRecipient](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addRecipientFailed](
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
    [addRecipientSucceed] (
      state,
      {
        payload: { recipient }
      }
    ) {
      return {
        ...state,
        recipients: [
          ...state.recipients,
          recipient
        ],
        error: null,
        loading: false
      };
    },
    [deleteRecipient](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [deleteRecipientFailed](
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
    [deleteRecipientSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      let recipients = JSON.parse(JSON.stringify(state.recipients));
      recipients.forEach((item, index) => {
        if (item.id == id) {
          recipients.splice(index, 1);
        }
      });
      return {
        ...state,
        error: null,
        recipients: recipients,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
