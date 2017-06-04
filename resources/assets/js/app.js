
$(function() {
  $('[data-toggle="tooltip"]').tooltip()

  $('table.data-table').DataTable({
    info: false,
    pageLength: 30,
    pagingType: 'full',
    processing: true,
    language: require('./i18n/data-table')
  });
});
