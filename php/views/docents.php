<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

        $entries;
        // begin payload
        $entries = getEntries();

        echo '  <div class="row">
                    <div class="col-sm-10">
                        <table id="docentTable"
                        data-toggle="table"
                        data-sort-name="docents"
                        data-sort-order="desc"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="true"
                        data-show-columns="true">
                            <thead>
                                <tr>
                                    <th data-field="profileID" data-sortable="true">Profile ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="phone" data-sortable="true">Phone</th>
                                    <th data-field="email" data-sortable="true">Email</th>
                                    <th data-field="museum" data-sortable="true">Museum</th>
                                    <th data-field="street" data-sortable="true">Street</th>
                                    <th data-field="city" data-sortable="true">City</th>
                                    <th data-field="state" data-sortable="true"> State</th>
                                    <th data-field="zip" data-sortable="true">Zip</th>
                                    <th data-field="spin1" data-sortable="true">1st Spinout Choice</th>
                                    <th data-field="spin2" data-sortable="true">2nd Spinout Choice</th>
                                    <th data-field="spin3" data-sortable="true">3rd Spinout Choice</th>
                                    <th data-field="lunch1" data-sortable="true">1st Lunch Tour Choice</th>
                                    <th data-field="lunch2" data-sortable="true">2nd Lunch Tour Choice</th>
                                    <th data-field="lunch3" data-sortable="true">3rd Lunch Tour Choice</th>
                                    <th data-field="specialRequest" data-sortable="true">Special Request</th>
                                    <th data-field="submitOn" data-sortable="true">Submitted on</th>

                                </tr>
                            </thead>
                            <tbody>
        ';

                            foreach($entries as $entry) {
                            ?>
                                <tr>
                                    <td><?php echo $entry['profileID']?></td>
                                    <td><?php echo $entry['Fname']. " ".$entry['Lname']?></td>
                                    <td><?php echo $entry['Phone']?></td>
                                    <td><?php echo $entry['Email']?></td>
                                    <td><?php echo $entry['Museum']?></td>
                                    <td>
                                        <?php echo $entry['Address1']?>
                                        <?php if(!empty($entry['Address2'])){ echo '<br>' .$entry['Address2'];}
                                        ?>
                                    </td>
                                    <td><?php echo $entry['City']?></td>
                                    <td><?php echo $entry['State']?></td>
                                    <td><?php echo $entry['Zip']?></td>
                                    <td><?php echo $entry['SpinOutFirst']?></td>
                                    <td><?php echo $entry['SpinOutSecond']?></td>
                                    <td><?php echo $entry['SpinOutThird']?></td>
                                    <td><?php echo $entry['LunchTourFirst']?></td>
                                    <td><?php echo $entry['LunchTourSecond']?></td>
                                    <td><?php echo $entry['LunchTourThird']?></td>
                                    <td><?php echo $entry['SpecialRequest']?></td>
                                    <td><?php echo $entry['CreatedOn']?></td>

                                </tr>
                            <?php
                            }
        echo '              </tbody>
                        </table>
                    </div>
                    <div class="col-sm-10">';
        


        echo '      </div>
                </div>
                        <script type="text/javascript">$("#docentTable").bootstrapTable();</script>

        ';


    }
?>