<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

        $eventFinalCopies;
        // begin payload
        $eventFinalCopies = getEventFinalCopies();

        echo '  <div class="row">
                    <div class="col-sm-12 workSpaceList">
                        <h3>Published Events</h3>
                        <table id="eventFinalCopies"
                        data-toggle="table"
                        data-sort-name="eventFinalCopies"
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

                            foreach($eventFinalCopies as $copies) {
                            ?>
                                <tr>
                                    <td><?php echo $copies['Title']?></td>
                                    <td class="finalStartDate"><?php echo $copies['StartDate']?></td>
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
                    $("#eventFinalCopies").bootstrapTable({height:400});
                </script>

        ';


    }
?>