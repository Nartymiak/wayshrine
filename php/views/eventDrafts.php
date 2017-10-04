<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

        $eventDrafts;
        // begin payload
        $eventDrafts = getEventDrafts();

        echo '  <div class="row">
                    <div class="col-sm-12 workSpaceList">
                        <h3>Drafts</h3>
                        <table id="eventDraftsTable"
                        data-toggle="table"
                        data-sort-name="eventDrafts"
                        data-sort-order="desc"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="true"
                        data-show-columns="true">
                            <thead>
                                <tr>
                                    <th data-field="EventTitle" data-sortable="true">Name</th>
                                    <th data-field="StartDate" data-sortable="true" data-width="200" data-formatter="dateFormatter">Start date</th>
                                </tr>
                            </thead>
                            <tbody>
        ';

                            foreach($eventDrafts as $draft) {
                            ?>
                                <tr>
                                    <td><?php echo $draft['Title']?></td>
                                    <td class="draftStartDate"><?php echo $draft['StartDate']?></td>
                                </tr>
                            <?php
                            }
        echo '
                            </tbody>
                        </table>
                    </div>';
        
        echo '  
                </div>
                <script type="text/javascript">
                    $("#eventDraftsTable").bootstrapTable({height:400});
                </script>

        ';


    }
?>