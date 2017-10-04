<?php
    include_once('../fns/checkToken.php');

	if(checkToken()){

        ?>

            <h3 class="viewTitle">Notes</h3>
            <table id="eventNotesTable"></table>

            <script type="text/javascript">
                $("#eventNotesTable").bootstrapTable({
                    height: 400,
                    search: true,
                    showRefresh: true,
                    showColumns: true,
                    pagination: true,
                    pageSize: 100,
                    maintainSelected: true,
                    checkboxHeader: true,
                    url: 'php/tables/eventNotes.php',
                    columns: [{
                        field: 'state',
                        checkbox:true,
                        visible: true
                    },{
                        field: 'ID',
                        title: 'ID',
                        sortable: true,
                        visible: false
                    },{
                        field: 'InDraft',
                        title: 'Drafted',
                        sortable: true
                    },{
                        field: 'Name',
                        title: 'Name',
                        sortable: true
                    }, {
                        field: 'StartDate',
                        title: 'Date',
                        sortable: true,
                        formatter: dateFormatter,
                        width: 125
                    }, {
                        field: 'StartTime',
                        title: 'Start Time',
                        sortable: true,
                        formatter: timeFormatter
                    }, {
                        field: 'EndTime',
                        title: 'End Time',
                        sortable: true
                    }, {
                        field: 'NoteWho',
                        title: 'Who',
                        sortable: true
                    }, {
                        field: 'NoteWhat',
                        title: 'What',
                        sortable: true
                    }, {
                        field: 'NoteWhere',
                        title: 'Where',
                        sortable: true
                    }, {
                        field: 'NoteWhy',
                        title: 'Why',
                        sortable: true
                    }, {
                        field: 'ImageSuggestions',
                        title: 'Images',
                        sortable: true
                    }, {
                        field: 'Sponsors',
                        title: 'Sponsors',
                        sortable: true
                    },{
                        field: 'Exists',
                        title: 'DB',
                        sortable: true
                    }]
                });

                $("#eventNotesTable").on("click-row.bs.table", function(e, row, $element) {
                    window.openNoteWorkSpace(row.ID, row.Name);
                });

            </script>
        
        <?php
    }
?>