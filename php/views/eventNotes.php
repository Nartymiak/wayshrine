<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

        $eventNotes;
        // begin payload
        $eventNotes = getEventNotes();

        echo '  <div class="row">
                    <div class="col-sm-12">
                        <h3>Event Notes</h3>
                        <table id="eventNotesTable"
                        data-toggle="table"
                        data-sort-name="eventNotes"
                        data-sort-order="desc"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="true"
                        data-show-columns="true">
                            <thead>
                                <tr>
                                    <th data-field="Name" data-sortable="true" data-width="200">Name</th>
                                    <th data-field="StartDate" data-sortable="true" data-formatter="dateFormatter" data-width="125">Date</th>
                                    <th data-field="StartTime" data-sortable="true" data-formatter="timeFormatter">Start Time</th>
                                    <th data-field="EndTime" data-sortable="true"  data-cell-style="centerTableCell">End Time</th>
                                    <th data-field="NoteWho" data-cell-style="centerTableCell" data-sortable="true">Who</th>
                                    <th data-field="NoteWhat" data-cell-style="centerTableCell" data-sortable="true">What</th>
                                    <th data-field="NoteWhere" data-cell-style="centerTableCell" data-sortable="true">Where</th>
                                    <th data-field="NoteWhy" data-cell-style="centerTableCell" data-sortable="true"> Why</th>
                                    <th data-field="ImageSuggestions" data-cell-style="centerTableCell" data-sortable="true">Images</th>
                                    <th data-field="Sponsors" data-cell-style="centerTableCell" data-sortable="true"> Sponsors</th>
                                </tr>
                            </thead>
                            <tbody>
        ';

                            foreach($eventNotes as $note) {
                            ?>
                                <tr id="<?php echo $note['EventID']?>">
                                    <td><?php echo $note['Name']?></td>
                                    <td class="startDate"><?php echo $note['StartDate']?></td>
                                    <td class="startTime"><?php echo $note['StartTime']?></td>
                                    <td class="endTime">
                                        <?php 
                                            if(!empty($note['EndTime']) || $note['EndTime'] !== null){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(!empty($note['NoteWho'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                     <td>
                                        <?php 
                                            if(!empty($note['NoteWhat'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(!empty($note['NoteWhere'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(!empty($note['NoteWhy'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(!empty($note['ImageSuggestions'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(!empty($note['Sponsors'])){ 
                                                echo '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
                                            } else {
                                                 echo '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
                                            }
                                        ?>
                                    </td>

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
                    $("#eventNotesTable").bootstrapTable({height:400});
                    $("#eventNotesTable").on("click-row.bs.table", function(e, row, $element) {
                        callNoteWorkSpace($element.attr("id"));
                    });
                </script>

        ';


    }
?>