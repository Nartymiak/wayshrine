<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		if(!$_POST['props']){

			echo '
				<div class="row">
					<div class="col-sm-1"></div>
					<div class="col-sm-11">
						<h3 style="text-align:right;"><span id="viewWorkSpaceButton" class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></h3>
					</div>
				</div>
			';

		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$id = $json->{'noteID'};
			$draft = getEventDraft($id);
		?>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-11">
					<h3 style="text-align:right;"><span id="viewWorkSpaceButton" class="glyphicon glyphicon-menu-left rotate" aria-hidden="true"></span></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-11">
					<div class="row col-sm-12"><h2>&nbsp;</h2></div>
					<div class="row col-sm-12" id="noteChat"></div>
					<form id="eventNoteForm">
						<input name="EventID" type="hidden" class="form-control" id="eventID" value="<?php echo $draft[0]['EventID'];?>">
						<div class="row col-sm-12">
							<div class="navbar navbar-default">
								<p class="navbar-text"><small class="form-text text-muted"><strong>Altru ID:</strong> <?php echo $draft[0]['AltruID'];?></small></p>
						 	</div>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteName">Name</label>
							<input name="Name" type="text" class="form-control" id="noteName" value="<?php echo $draft[0]['Name'];?>">
						</div>
						<div class="row">
						 	<div class="form-group col-sm-5">
								<label for="noteStartDate">Date</label>
								<input name="StartDate" type="date" class="form-control" id="noteStartDate" value="<?php echo $draft[0]['StartDate'];?>">
							</div>
						 	<div class="form-group col-sm-3">
								<label for="noteStartTime">Start Time</label>
								<input name="StartTime" type="time" class="form-control" id="noteStartTime" value="<?php echo $draft[0]['StartTime'];?>">
							</div>
						 	<div class="form-group col-sm-3">
								<label for="noteEndTime">End Time</label>
								<input name="EndTime" type="time" class="form-control" id="noteEndTime" value="<?php echo $draft[0]['EndTime'];?>">
							</div>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteWhen">Other Dates &amp; Times / Date Range</label>
							<textarea name="NoteWhen" class="form-control" id="noteWhen" rows="3"><?php echo $draft[0]['NoteWhen'];?></textarea>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteWho">Who</label>
							<textarea name="NoteWho" class="form-control" id="noteWho" rows="3"><?php echo $draft[0]['NoteWho'];?></textarea>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteWhat">What</label>
							<textarea name="NoteWhat" class="form-control" id="noteWhat" rows="6"><?php echo $draft[0]['NoteWhat'];?></textarea>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteWhere">Where</label>
							<textarea name="NoteWhere" class="form-control" id="noteWhere" rows="3"><?php echo $draft[0]['NoteWhere'];?></textarea>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="noteWhy">Why</label>
							<textarea name="NoteWhy" class="form-control" id="noteWhy" rows="6"><?php echo $draft[0]['NoteWhy'];?></textarea>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="imageSuggestions">Image Suggestions</label>
							<textarea name="ImageSuggestions" class="form-control" id="imageSuggestions" rows="3"><?php echo $draft[0]['ImageSuggestions'];?></textarea>
							<div class="navbar navbar-default">
								<p class="navbar-text"><small class="form-text text-muted"><strong>Examples:</strong><br><strong>&#183;</strong> a picture of a horse<br><strong>&#183;</strong> <a style="text-decoration:none;color:inherit;" href="smb://file/Collection Images/NBMAA Collection Images/tif/Borglum,SolonH.,TheHorse'sHead,1973.tif">smb://file/Collection&nbsp;Images/NBMAA&nbsp;Collection Images/tif/Borglum,SolonH.,TheHorse'sHead,1973.tif</a><br><strong>&#183;</strong> <a style="color:inherit;text-decoration:none;" target="_blank" href="http://pixel.nymag.com/imgs/daily/vulture/2015/07/21/21-raphael-bob-waksberg-chatroom-silo.w245.h368.png">http://www.nymag.com/imgs/daily/vulture/2015/07/21/21/horseman.png</a></small></p>
							</div>
						</div>
					 	<div class="row form-group col-sm-12">
							<label for="sponsors">Sponsors</label>
							<textarea name="Sponsors" class="form-control" id="sponsors" rows="3"><?php echo $draft[0]['Sponsors'];?></textarea>
						</div>
						<div class="row form-group col-sm-12">
							<button type="submit" class="btn btn-primary pull-right submit" data-ctrl="editEventNote">Update</button>
						</div>
						<div class="row col-sm-12">
							<div id="debug" class="row col-sm-12"></div>
						</div>
					</form>
				</div>
			</div>
			<script type="text/javascript">loginLogic();</script>
<?php
		}
	}

?>