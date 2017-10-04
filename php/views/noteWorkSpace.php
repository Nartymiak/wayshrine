<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		if(!$_POST['props']){

		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$id = $json->{'noteID'};
			$userTypes = $json->{'userTypes'};
			$note = getNoteWorkspace($id);
			$eventTypes = getEventTypes();
			$exhibitions = getExhibitions();
			$existsID = exists($note[0]['Name']);
		?>

			<div class="row">
		 		<div class="col-sm-12">
		 			<h3 class="viewTitle">Edit Note</h3>
		 		</div>
		 	</div>

			<form id="eventNoteForm">
				<input name="EventID" type="hidden" class="form-control" id="eventID" value="<?php echo $note[0]['EventID'];?>">
				<input name="AltruID" type="hidden" class="form-control" id="altruID" value="<?php echo $note[0]['AltruID'];?>">
		
		<?php if(!empty($existsID)){ ?>
				<div class="row">
			 		<div class="form-group col-sm-12">
						<div class="alert alert-warning">
							<p><span class="glyphicon glyphicon-warning-sign"></span> &nbsp; An event in our DB has the same name! See <strong><a style="color:inherit;" class="finalCopyLink" href="<?php echo $existsID ?>"><?php echo $note[0]['Name'];?></strong></a>.</p>
					 	</div>
					 </div>
				</div>
		<?php } ?>

				<div class="row">
			 		<div class="form-group col-sm-12">
						<div class="navbar navbar-default">
							<p class="navbar-text"><small class="form-text text-muted"><strong>Altru ID:</strong> <?php echo $note[0]['AltruID'];?></small></p>
					 	</div>
					 </div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteName">Name</label>
						<input name="Name" type="text" class="form-control" id="noteName" value="<?php echo htmlentities($note[0]['Name']);?>">
					</div>
				</div>
				<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteEventType">Event Type</label>
						<select class="form-control" id="noteEventType" name="EventTypeID">

						<?php foreach($eventTypes as $et){  ?>
							<option <?php if($et['KeywordID'] === $note[0]['EventTypeID']){echo " selected "; } ?> value="<?php echo $et['KeywordID']; ?>"><?php echo $et['Word']; ?></option> 
						<?php } ?>

						</select>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteRelatedExhibition">Related Exhibition</label>
						<select class="form-control" id="noteRelatedExhibition" name="ExhibitionID">
						<option value="" selected>Leave blank for none</option> 
						<?php foreach($exhibitions as $exh){  ?>
							<option <?php if($exh['ExhibitionID'] === $note[0]['ExhibitionID']){echo " selected "; } ?> value="<?php echo $exh['ExhibitionID']; ?>"><?php echo $exh['Title']; ?></option> 
						<?php } ?>

						</select>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-4">
						<label for="noteStartDate">Date</label>
						<input name="StartDate" type="date" class="form-control" id="noteStartDate" value="<?php echo $note[0]['StartDate'];?>">
					</div>
				 	<div class="form-group col-sm-4">
						<label for="noteStartTime">Start Time</label>
						<input name="StartTime" type="time" class="form-control" id="noteStartTime" value="<?php echo $note[0]['StartTime'];?>">
					</div>
				 	<div class="form-group col-sm-4">
						<label for="noteEndTime">End Time</label>
						<input name="EndTime" type="time" class="form-control" id="noteEndTime" value="<?php echo $note[0]['EndTime'];?>">
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteWhen">Other Dates &amp; Times / Date Range</label>
						<textarea name="NoteWhen" class="form-control" id="noteWhen" rows="3"><?php echo $note[0]['NoteWhen'];?></textarea>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-12">
				 		<label for="registrationEndDate">Registration End Date</label>
						<div class="input-group">
							<span class="input-group-addon">
								<input name="RegistrationCheck" class="registrationCheck" type="checkbox" value="1" <?php if(!empty($note[0]['RegistrationEndDate'])){ echo 'checked = "checked"'; }?>>
							</span>
							<input  name="RegistrationEndDate" type="date" class="form-control registrationEndDate" id="registrationEndDate" value="<?php echo $note[0]['RegistrationEndDate'];?>">
						</div>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-12">
						<label for="noteAdmissionCharge">Admission Charge</label>
						<textarea name="AdmissionCharge" id="noteAdmissionCharge"><?php echo $note[0]['AdmissionCharge'];?></textarea>
					</div>
				</div>
				<div class="row">
				 	<div class="form-group col-sm-12">
				 		<label for="noteAltruButton">Show Altru Button (link to registration/purchase ticket page)</label>
						<div class="input-group">
							<span class="input-group-addon">
								<input name="AltruButton" id="draftAltruButton" type="checkbox" value="1" <?php if(!empty($note[0]['AltruButton']) || $note[0]['AltruButton'] != 0 ){ echo 'checked = "checked"'; }?>>
							</span>
							<input  name="AltruLink" type="text" class="form-control" id="draftAltruLink" value="<?php echo $note[0]['AltruLink'];?>" placeholder="paste altru link here...">
						</div>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteWho">Who</label>
						<textarea name="NoteWho" class="form-control" id="noteWho" rows="3"><?php echo $note[0]['NoteWho'];?></textarea>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteWhat">What</label>
						<textarea name="NoteWhat" class="form-control" id="noteWhat" rows="6"><?php echo $note[0]['NoteWhat'];?></textarea>
					</div>
				</div>
				<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteWhere">Where</label>
						<textarea name="NoteWhere" class="form-control" id="noteWhere" rows="3"><?php echo $note[0]['NoteWhere'];?></textarea>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="noteWhy">Why</label>
						<textarea name="NoteWhy" class="form-control" id="noteWhy" rows="6"><?php echo $note[0]['NoteWhy'];?></textarea>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="imageSuggestions">Image Suggestions</label>
						<textarea name="ImageSuggestions" class="form-control" id="imageSuggestions" rows="6"><?php echo $note[0]['ImageSuggestions'];?></textarea>
						<div class="navbar navbar-default">
							<p class="navbar-text"><small class="form-text text-muted"><strong>Examples:</strong><br><strong>&#183;</strong> a picture of a horse<br><strong>&#183;</strong> <a style="text-decoration:none;color:inherit;" href="smb://file/Collection Images/NBMAA Collection Images/tif/Borglum,SolonH.,TheHorse'sHead,1973.tif">smb://file/Collection&nbsp;Images/NBMAA&nbsp;Collection Images/tif/Borglum,SolonH.,TheHorse'sHead,1973.tif</a><br><strong>&#183;</strong> <a style="color:inherit;text-decoration:none;" target="_blank" href="http://pixel.nymag.com/imgs/daily/vulture/2015/07/21/21-raphael-bob-waksberg-chatroom-silo.w245.h368.png">http://www.nymag.com/imgs/daily/vulture/2015/07/21/21/horseman.png</a></small></p>
						</div>
					</div>
				</div>
			 	<div class="row">
			 		<div class="form-group col-sm-12">
						<label for="sponsors">Sponsors</label>
						<textarea name="Sponsors" class="form-control" id="sponsors" rows="3"><?php echo $note[0]['Sponsors'];?></textarea>
					</div>
				</div>
				<div class="row">
			 		<div class="form-group col-sm-12">
			 			<button type="submit" class="btn btn-primary pull-right submit" data-ctrl="editEventNote">Update</button>
			 			<?php if(userHasClearance(3, $userTypes)){ echo '<button type="button" style="margin-right:15px;" class="btn btn-success pull-right sendNoteToDraft" data-ctrl="sendNoteToDraft">Make draft</button>';}?>
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