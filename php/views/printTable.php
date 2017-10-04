<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    if(checkToken()){

        ?>

            <h3 class="viewTitle">To Print</h3>
            <table id="printEvents"></table>

            <script type="text/javascript">
                $("#printEvents").bootstrapTable({
                    silent: true,
                    height: 800,
                    search: true,
                    showRefresh: true,
                    showColumns: true,
                    url: 'php/tables/printTable.php',
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
                        sortable: true,
                        visible: false
                    }, {
                        field: 'Print',
                        title: 'Print',
                        sortable: true,
                        visible: false
                    }, {
                        field: 'StartDate',
                        title: 'Date',
                        sortable: true,
                        formatter: printDateFormatter,
                        width: 125
                    }, {
                        field: 'StartTime',
                        title: 'Start Time',
                        formatter: timeFormatter,
                        sortable: true
                    },{
                        field: 'EndTime',
                        title: 'End Time',
                        formatter: timeFormatter,
                        sortable: true
                    }, {
                        field: 'EventType',
                        title: 'Type',
                        sortable: true
                    }, {
                        field: 'EventTitle',
                        title: 'Title',
                        sortable: true
                    }, {
                        field: 'Description',
                        title: 'Desc',
                        sortable: true,
                        visible: false
                    },{
                        field: 'AdmissionCharge',
                        title: 'Admission',
                        sortable: true,
                        visible: false
                    }, {
                        field: 'Img',
                        title: 'Img',
                        sortable: true,
                        visible: false
                    }, {
                        field: 'Sponsors',
                        title: 'Sponsors',
                        sortable: true,
                        visible: false
                    }, {
                        field: 'RelatedExhibition',
                        title: 'Exhibition',
                        sortable: true,
                        visible: false
                    }]
                });
            </script>
        
        <?php
    }
?>