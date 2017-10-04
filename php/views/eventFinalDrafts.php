<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    if(checkToken()){

        ?>

            <h3 class="viewTitle">Final Drafts</h3>
            <table id="eventFinalDraftsTable"></table>

            <script type="text/javascript">
                $("#eventFinalDraftsTable").bootstrapTable({
                    height: 400,
                    search: true,
                    showRefresh: true,
                    showColumns: true,
                    url: 'php/tables/eventFinalDrafts.php',
                    columns: [{
                        field: 'ID',
                        title: 'ID',
                        sortable: true,
                        visible: false
                    },{
                        field: 'noteID',
                        title: 'Event Note ID',
                        sortable: true,
                        visible: false
                    },{
                        field: 'OkToPub',
                        title: 'Proofed By',
                        sortable: true
                    }, {
                        field: 'Print',
                        title: 'Print',
                        sortable: true
                    },{
                        field: 'EventTitle',
                        title: 'Title',
                        sortable: true
                    },{
                        field: 'EventType',
                        title: 'Type',
                        sortable: true
                    },{
                        field: 'RelatedExhibition',
                        title: 'Exhibition',
                        sortable: true
                    }, {
                        field: 'StartDate',
                        title: 'Date',
                        sortable: true,
                        formatter: dateFormatter,
                        width: 125
                    }, {
                        field: 'Description',
                        title: 'Desc',
                        sortable: true
                    }, {
                        field: 'Img',
                        title: 'Img',
                        sortable: true
                    }, {
                        field: 'Sponsors',
                        title: 'Sponsors',
                        sortable: true
                    }]
                });

                $("#eventFinalDraftsTable").on("click-row.bs.table", function(e, row, $element) {
                    window.openFinalDraftWorkSpace(row.ID, row.noteID, row.EventTitle);
                });
            </script>
        
        <?php
    }
?>