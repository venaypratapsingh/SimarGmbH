/*
 Template Name: Veltrix - Responsive Bootstrap 4 Admin Dashboard
 Author: Themesbrand
 File: Datatable js
 */

$(document).ready(function() {
    // Get current locale from HTML lang attribute
    var locale = $('html').attr('lang') || 'en';
    
    // Define language options
    var langOptions = {
        'de': {
            "sProcessing": "Wird verarbeitet...",
            "sLengthMenu": "_MENU_ Einträge anzeigen",
            "sZeroRecords": "Keine Einträge gefunden",
            "sInfo": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
            "sInfoEmpty": "Zeige 0 bis 0 von 0 Einträgen",
            "sInfoFiltered": "(gefiltert von _MAX_ Einträgen)",
            "sInfoPostFix": "",
            "sSearch": "Suchen:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Erste",
                "sPrevious": "Zurück",
                "sNext": "Nächste",
                "sLast": "Letzte"
            }
        },
        'en': {
            "sProcessing": "Processing...",
            "sLengthMenu": "Show _MENU_ entries",
            "sZeroRecords": "No matching records found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty": "Showing 0 to 0 of 0 entries",
            "sInfoFiltered": "(filtered from _MAX_ total entries)",
            "sInfoPostFix": "",
            "sSearch": "Search:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Previous",
                "sNext": "Next",
                "sLast": "Last"
            }
        }
    };

    $('#datatable').DataTable({
        language: langOptions[locale] || langOptions['en']
    });

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: ['colvis'],
        language: langOptions[locale] || langOptions['en']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
} );