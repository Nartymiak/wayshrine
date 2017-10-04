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
			$exhibitions = getExhibitions();
			// for dateTimes
			$i = 0;
			$dCount = count($draft);
		?>

			<div class="row">
		 		<div class="col-sm-12">
		 			<h3 class="viewTitle">Edit Draft</h3>
		 		</div>
		 	</div>

			<div class="row">
		 		<div class="col-sm-12">

		 			 <ul class="nav nav-pills">
						<li role="presentation" class="active"><a href="#draftCopy" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Copy</a></li>
						<li role="presentation"><a href="#draftImages" data-toggle="tab"><span class="glyphicon glyphicon-picture"></span> Images</a></li>
					</ul>
		 		</div>
		 	</div>
		 	<div class="tab-content">
			 	<div class="tab-pane active" id="draftCopy">
					<form id="eventDraftForm">
						<input name="UserID" type="hidden" class="form-control" id="userID" value="<?php echo $json->{'userID'};?>">
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
								<input name="Title" type="text" class="form-control" id="draftTitle" value="<?php echo htmlentities($draft[0]['Title']);?>">
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
								<label for="draftRelatedExhibition">Related Exhibition</label>
								<select class="form-control" id="draftRelatedExhibition" name="ExhibitionID">
								<option value="" selected>Leave blank for none</option> 
								<?php foreach($exhibitions as $exh){  ?>
									<option <?php if($exh['ExhibitionID'] === $draft[0]['ExhibitionID']){echo " selected "; } ?> value="<?php echo $exh['ExhibitionID']; ?>"><?php echo $exh['Title']; ?></option> 
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
						 		<label for="registrationEndDate">Registration End Date</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input name="RegistrationCheck" class="registrationCheck" type="checkbox" value="1" <?php if(!empty($draft[0]['RegistrationEndDate'])){ echo 'checked = "checked"'; }?>>
									</span>
									<input  name="RegistrationEndDate" type="date" class="form-control registrationEndDate" id="registrationEndDate" value="<?php echo $draft[0]['RegistrationEndDate'];?>">
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
						 		<label for="draftPrint">Use In Print Material</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input name="PrintCheck" class="registrationCheck" type="checkbox" value="1" <?php if(!empty($draft[0]['Print']) || $draft[0]['Print'] === 0 ){ echo 'checked = "checked"'; }?>>
									</span>
									<input placeholder="Invitation, flyer, etc..." name="Print" type="text" class="form-control" id="draftPrint" <?php if(!empty($draft[0]['Print']) || $draft[0]['Print'] === 0){ echo 'value="' .$draft[0]['Print'].'"'; }?>">
								</div>
							</div>
						</div>
						<div class="row">
						 	<div class="form-group col-sm-12">
						 		<label for="draftOkToPub">Mark OK To Be Published</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input name="OkToPubCheck" class="registrationCheck" type="checkbox" value="1" <?php if(!empty($draft[0]['OkToPub']) || $draft[0]['OkToPub'] === 0 ){ echo 'checked = "checked"'; }?>>
									</span>
									<input disabled name="OkToPub" type="text" class="form-control" id="draftOkToPub" value=" <?php if(!empty($draft[0]['OkToPub']) || $draft[0]['OkToPub'] === 0){ $user = getUser($draft[0]['OkToPub']); echo 'Marked OK by ' .$user[0]['Fname']. ' '.$user[0]['Lname']; }else{echo "none";}?>">
								</div>
							</div>
						</div>
						<div class="row">
					 		<div class="form-group col-sm-12">
					 			<a target="_blank" href="http://www.nbmaa.org/event/<?php echo $draft[0]['Link'] ?>" class="btn btn-primary pull-right">Preview</a>
					 			<?php if(userHasClearance(3, $userTypes)){ echo '<button style="margin-right:15px;"  type="submit" class="btn btn-success pull-right submit" data-ctrl="editEventDraft">Update</button>';}?>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane" id="draftImages">
					<div class="row">
						<div class="form-group col-sm-12">
							<label>Current Image</label>
							<div id="imgPreview">
							<?php if($draft[0]['ImgFilePath'] !== '' && $draft[0]['ImgFilePath'] !== NULL ){
								echo '<img src="http://www.nbmaa.org/images/event-page-images/'.$draft[0]['ImgFilePath'].'">';
							}else{
								echo '<p>No image</p>';
								} ?>
							</div>
						</div>
					</div>
					<form id="eventDraftImagesForm">
						<input name="UserID" type="hidden" class="form-control" id="userID" value="<?php echo $json->{'userID'};?>">
						<input name="EventID" type="hidden" class="form-control" id="eventID" value="<?php echo $draft[0]['EventID'];?>">
						<input name="Link" type="hidden" class="form-control" id="eventLink" value="<?php echo $draft[0]['Link'] ?>">
						<div class="row">
							<div class="form-group col-sm-12">
								<label for="imgFileName">Upload a new file or type the name of a file already on our website</label>
								<div class="input-group file-preview">
								<?php if($draft[0]['ImgFilePath'] !== '' && $draft[0]['ImgFilePath'] !== NULL ){
									echo '<input placeholder="Leave blank to remove any images" type="text" class="form-control file-preview-filename" id="imgFileName" name="ImgFileName" value="'.$draft[0]['ImgFilePath'].'">
';
								}else{
									echo '<input placeholder="Leave blank to remove any images" type="text" class="form-control file-preview-filename" id="imgFileName" name="ImgFileName">
';
									} ?>
									<span class="input-group-btn"> 
										<div class="btn btn-default filePreviewBtn">
											<span class="glyphicon glyphicon-folder-open"></span> <span class="filePreviewInputTitle">Browse</span>
											<input type="file" accept="image/*" name="inputFilePreview" class="inputFilePreview">
										</div>
									</span>
								</div>
							</div>
						</div>
						<div class="navbar navbar-default">
							<p class="navbar-text"><small class="form-text text-muted">
								<strong>Notes: </strong>
								<br>You can copy/paste a url from our website
								<br>Newly uploaded file names will be converted to all lower case shish-ka-bob.
								<br>Ex. This Is a File Name.jpg -> this-is-a-file-name.jpg
								<br>A file name cannot be changed once uploaded</small></p>
						</div>
					 	<div class="row">
					 		<div class="form-group col-sm-12">
								<label for="imgCaption">Image Caption</label>
								<textarea name="ImgCaption" id="imgCaption"><?php echo $draft[0]['ImgCaption'] ?></textarea>
							</div>
						</div>
						<a target="_blank" href="http://www.nbmaa.org/event/<?php echo $draft[0]['Link'] ?>" class="btn btn-primary pull-right">Preview</a>
						<?php if(userHasClearance(3, $userTypes)){ echo '<button type="button" style="margin-right:15px;" class="btn btn-labeled btn-success uploadImgBtn pull-right" data-ctrl="uploadDraftImg">Update</button>' ;}?>
					</form>	
				</div>
			</div>
			<div class="row">
		 		<div class="form-group col-sm-12">
		 			<div class="row">
						<div id="debug" class="col-sm-12"></div>
					</div>
				</div>
			</div>
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