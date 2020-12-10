import { createActions } from 'redux-actions';

const {
  getRecipients,
  getRecipientsFailed,
  getRecipientsSucceed,
  addRecipient,
  addRecipientFailed,
  addRecipientSucceed,
  deleteRecipient,
  deleteRecipientFailed,
  deleteRecipientSucceed,
} = createActions({
  GET_RECIPIENTS: (params) => ({params}),
  GET_RECIPIENTS_FAILED: error => ({ error }),
  GET_RECIPIENTS_SUCCEED: recipients => ({ recipients }),
  ADD_RECIPIENT: (params) => ({params}),
  ADD_RECIPIENT_FAILED: error => ({ error }),
  ADD_RECIPIENT_SUCCEED: recipient => ({ recipient }),
  DELETE_RECIPIENT: (id) => ({id}),
  DELETE_RECIPIENT_FAILED: error => ({ error }),
  DELETE_RECIPIENT_SUCCEED: id => ({ id }),
});

export {
  getRecipients,
  getRecipientsFailed,
  getRecipientsSucceed,
  addRecipient,
  addRecipientFailed,
  addRecipientSucceed,
  deleteRecipient,
  deleteRecipientFailed,
  deleteRecipientSucceed,
};
