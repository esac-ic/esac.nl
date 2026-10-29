import DataTable from 'datatables.net-bs4';
import 'datatables.net-responsive-bs4';

import 'datatables.net-bs4/css/dataTables.bootstrap4.css';
import 'datatables.net-responsive-bs4/css/responsive.bootstrap4.css';

// The <x-data-table datatable> component marks its table with js-datatable.
document.querySelectorAll('.js-datatable').forEach(table => new DataTable(table));
