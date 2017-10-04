<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		if(!$_POST['props']){


		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$id = $json->{'eventID'};
			$userTypes = $json->{'userTypes'};
			$draft = getDraftWorkspace($id);
			$eventTypes = getEventTypes();
			// for dateTimes
			$i = 0;
			$dCount = count($draft);
		?>

			<div class="row">
		 		<div class="col-sm-12">
		 			<h3 class="viewTitle">Edit Draft</h3>
		 		</div>
		 	</div>

			<form id="eventDraftForm">
				<input name="EventID" type="hidden" class="form-control" id="eventID" value="<?php echo $draft[0]['EventID'];?>">
				<input name="AltruID" type="hidden" class="form-control" id="altruID" value="<?php echo $draft[0]['AltruID'];?>">

				<div class="row">
			 		<div class="form-group col-sm-12">
						<div class="navbar navbar-default">
							<p class="navbar-text"><small class="form-text text-muted"><strong>Altru ID:</strong> <?php echo $draft[0]['AltruID'];?></small></p>
					 	</div>
					 </div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="draftName">Name</label>
						<input name="Title" type="text" class="form-control" id="draftTitle" value="<?php echo $draft[0]['Title'];?>">
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="draftEventType">Event Type</label>
						<select class="form-control" id="draftEventType" name="EventTypeID">

						<?php foreach($eventTypes as $et){  ?>
							<option <?php if($et['KeywordID'] === $draft[0]['EventTypeID']){echo " selected "; } ?> value="<?php echo $et['KeywordID']; ?>"><?php echo $et['Word']; ?></option> 
						<?php } ?>

						</select>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="description">Description</label>
						<textarea name="Description" id="draftDescription"><?php echo $draft[0]['Description'];?></textarea>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-12">
						<label for="draftAdmissionCharge">Admission Charge</label>
						<textarea name="AdmissionCharge" id="draftAdmissionCharge"><?php echo $draft[0]['AdmissionCharge'];?></textarea>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-12">
				 		<label for="draftAltruButton">Show Altru Button (link to registration/purchase ticket page)</label>
						<div class="input-group">
							<span class="input-group-addon">
								<input name="AltruButton" id="draftAltruButton" type="checkbox" value="1" <?php if(!empty($draft[0]['AltruButton'])){ echo 'checked = "checked"'; }?>>
							</span>
							<input  name="AltruLink" type="text" class="form-control" id="draftAltruLink" value="<?php echo $draft[0]['AltruLink'];?>" placeholder="paste altru link here...">
						</div>
					</div>
				</div>
				<div class=" draftDateTimeSection">
					<div class="row">
						<div class="col-sm-4">
							<label for="draftStartDate">Date</label>
						</div>
					 	<div class="col-sm-4">
							<label for="draftStartTime">Start Time</label>
						</div>
					 	<div class="col-sm-4">
							<label for="draftEndTime">End Time</label>
						</div>
					</div>
					<?php foreach ($draft as $d){ ?>

						<div class="row draftDateTimeRow">
						 	<div class="form-group col-sm-4">
								<input name="StartDate[]" type="date" class="form-control" id="draftStartDate" value="<?php echo $draft[$i]['StartDate'];?>">
							</div>
						 	<div class="form-group col-sm-4">
								<input name="StartTime[]" type="time" class="form-control" id="draftStartTime" value="<?php echo $draft[$i]['StartTime'];?>">
							</div>
						 	<div class="form-group col-sm-4 hasAddBtn">
								<div class="input-group">
									<input name="EndTime[]" type="time" class="form-control" id="draftEndTime" value="<?php echo $draft[$i]['EndTime'];?>">
			                    	<span class="input-group-btn">	
			                    	<?php if($i < $dCount - 1) { ?> 
			                    		<button class="btn btn-danger removeTime" type="button">
			                    			<span class="glyphicon glyphicon-minus"></span>
			                    		</button>
			                    	<?php } else {?> 
			                    		<button class="btn btn-success addTime" type="button">
			                    			<span class="glyphicon glyphicon-plus"></span>
			                    		</button>
			                        <?php }?>
			                        </span>
			                    </div>
							</div>
						</div>

					<?php $i++; } ?>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="sponsors">Sponsors</label>
						<textarea name="Sponsors" class="form-control" id="draftSponsors" rows="3"><?php echo $draft[0]['Sponsors'];?></textarea>
					</div>
				</div>
				<div class="row">
			 		<div class="form-group col-sm-12">
			 			<a target="_blank" href="http://www.nbmaa.org/event/<?php echo $draft[0]['Link'] ?>" class="btn btn-primary pull-right">Preview</a>
			 			<?php if(userHasClearance(3, $userTypes)){ echo '<button style="margin-right:15px;"  type="submit" class="btn btn-success pull-right submit" data-ctrl="editEventDraft">Update</button>';}?>
					</div>
				</div>
				<div class="row">
			 		<div class="form-group col-sm-12">
			 			<div class="row">
							<div id="debug" class="col-sm-12"></div>
						</div>
					</div>
				</div>
			</form>
<?php
		}
	}

	function userHasClearance($level, $types){
		foreach($types as $t){
			if($t <= $level){return true;}
		}
		return false;
	}

?>