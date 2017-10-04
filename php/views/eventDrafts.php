<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    if(checkToken()){

        ?>

            <h3 class="viewTitle">Drafts</h3>
            <table id="eventDraftsTable"></table>

            <script type="text/javascript">
                $("#eventDraftsTable").bootstrapTable({
                    height: 400,
                    search: true,
                    showRefresh: true,
                    showColumns: true,
                    url: 'php/tables/eventDrafts.php',
                    columns: [{
                        field: 'ID',
                        title: 'ID',
                        sortable: true,
                        visible: false
                    },{
                        field: 'EventTitle',
                        title: 'Title',
                        sortable: true
                    }, {
                        field: 'StartDate',
                        title: 'Date',
                        sortable: true,
                        formatter: dateFormatter,
                        width: 125
                    }]
                });

                $("#eventDraftsTable").on("click-row.bs.table", function(e, row, $element) {
                    window.openDraftWorkSpace(row.ID, row.Title);
                });
            </script>
        
        <?php
    }
?>