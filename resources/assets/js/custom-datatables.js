$.extend($.fn.dataTable.defaults, {
    'paging': true,
    'info': true,
    'ordering': true,
    'autoWidth': false,
    'pageLength': 10,
    'language': {
        'search': '',
        'sSearch': 'Search',
        'emptyTable': Lang.get('messages.new_keys.data_not_available'),
        'sZeroRecords': Lang.get('messages.new_keys.no_matching_records_found'),
        'sInfoEmpty': Lang.get('pagination.showing') + ' 0 ' + Lang.get('pagination.to') + ' 0 ' + Lang.get('pagination.of') + ' 0 ' + Lang.get('pagination.entries'),
        'sInfo': Lang.get('pagination.showing') + ' _START_ ' + Lang.get('pagination.to') + ' _END_ ' + Lang.get('pagination.of') + ' _TOTAL_ ' + Lang.get('pagination.entries'),
        'sInfoFiltered': '(' + Lang.get('messages.new_keys.filter_from') + ' _MAX_ ' + Lang.get('pagination.total_entries') + ')',
        'sLengthMenu': Lang.get('messages.new_keys.show') + ' _MENU_ ' + Lang.get('pagination.total_entries'),
        'sProcessing': Lang.get('messages.processing'),
        'oPaginate': {
            'sNext': Lang.get('pagination.next'), // Next page button text
            'sPrevious': Lang.get('pagination.previous') // Previous page button text
        }
    },
    'preDrawCallback': function () {
        customSearch();
    },
});

function customSearch() {
    $('.dataTables_filter input').addClass('form-control');
    $('.dataTables_filter input').attr('placeholder', Lang.get('messages.search'));
}
