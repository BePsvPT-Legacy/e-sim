
$(function() {
  $('[data-toggle="tooltip"]').tooltip()

  $('table.data-table').DataTable({
    info: false,
    pageLength: 30,
    language: require('./i18n/data-table')
  });
});
