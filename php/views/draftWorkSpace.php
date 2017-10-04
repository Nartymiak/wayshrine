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
						<label for="draftAltruID">AltruID</label>
						<input name="AltruID" type="text" class="form-control" id="draftAltruID" value="<?php echo $draft[0]['AltruID'];?>">
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
						<div id="draftDescription"><?php echo $draft[0]['Description'];?></div>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-9">
						<label for="draftAdmissionCharge">Admission Charge</label>
						<div  name="AdmissionCharge" id="draftAdmissionCharge"><?php echo $draft[0]['AdmissionCharge'];?></div>
					</div>
				 	<div class="form-group col-sm-3">
						<label for="draftAltruButton">Show Altru Button</label>
						<input name="AltruButton" type="checkbox" style="display: block;" id="draftAltruButton" value="1" <?php if(!empty($draft[0]['AltruButton'])){ echo 'checked = "checked"'; }?>>
					</div>
				</div>
				<div class=" draftDatTimeSection">
					<div class="row draftDateTimeRow">
					 	<div class="form-group col-sm-4">
							<label for="draftStartDate">Date</label>
							<input name="StartDate[]" type="date" class="form-control" id="draftStartDate" value="<?php echo $draft[0]['StartDate'];?>">
						</div>
					 	<div class="form-group col-sm-4">
							<label for="draftStartTime">Start Time</label>
							<input name="StartTime[]" type="time" class="form-control" id="draftStartTime" value="<?php echo $draft[0]['StartTime'];?>">
						</div>
					 	<div class="form-group col-sm-4 hasAddBtn">
							<label for="draftEndTime">End Time</label>
							<div class="input-group">
								<input name="EndTime[]" type="time" class="form-control" id="draftEndTime" value="<?php echo $draft[0]['EndTime'];?>">
		                    	<span class="input-group-btn">
		                            <button class="btn btn-success btn-add addTime" type="button">
		                                <span class="glyphicon glyphicon-plus"></span>
		                            </button>
		                        </span>
		                    </div>
						</div>
					</div>
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