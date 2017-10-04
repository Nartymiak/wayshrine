<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    if(checkToken()){

        ?>

            <h3 class="viewTitle">Final Copies</h3>
            <table id="eventFinalCopiesTable"></table>

            <script type="text/javascript">
                $("#eventFinalCopiesTable").bootstrapTable({
                    height: 400,
                    search: true,
                    showRefresh: true,
                    showColumns: true,
                    url: 'php/tables/eventFinalCopies.php',
                    columns: [{
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
            </script>
        
        <?php
    }
?>