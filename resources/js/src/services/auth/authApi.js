import { wrapRequest, xapi } from '../../utils';

const login = wrapRequest(async (email, password) =>
  xapi().post('/api/login', {
    email,
    password
  })
);

const getUser = wrapRequest(async () => xapi().get('/api/me'));

export { login, getUser };
