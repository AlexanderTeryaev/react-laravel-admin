import { wrapRequest, xapi } from '../../utils';

const dashboard = wrapRequest(async () =>
  xapi().get('/api/home')
);

const userInstallation = wrapRequest(async () =>
  xapi().get('/api/get-user-installation-chart-data')
);

const questionsAnswers = wrapRequest(async () =>
  xapi().get('/api/get-questions-answers-data')
);

const repartition = wrapRequest(async () =>
  xapi().get('/api/get-repartition-data')
);


export { dashboard, userInstallation, questionsAnswers, repartition };
