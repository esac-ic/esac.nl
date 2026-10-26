import axios from 'axios';

// Laravel recognises these as Ajax requests; axios sends the CSRF token from Laravel's XSRF-TOKEN cookie itself.
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default axios;
