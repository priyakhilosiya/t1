<div role="dialog" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="AttendeeModal">
   <form method="POST" action="<?= admin_path()?>/users/postAddattendee" accept-charset="UTF-8" class="ajax closeModalAfter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-user"></i>
                    Invite Attendee</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
						if (count($ticketDetails>0)){?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="ticket_id" class="control-label required">Ticket</label>
                                   <select class="form-control" id="ticket_id" name="ticket_id">
                                    <?php foreach($ticketDetails as $key=>$val){   ?>
                                        <option value="<?=$key?>"><?php echo $val;?></option>
                					<?php }?>
                                  </select>
                                </div>
                            </div>
	
							<div class="col-md-6">
								<div class="form-group">
								 <label for="cat_type" class="control-label required">User type</label>
								  <div class="form-control">
									<input type="radio" checked='checked' name="cat_type" id="cat_type" value="D"/>Delegate
									<input type="radio" name="cat_type" id="cat_type" value="PG"/>PG
								</div>
								</div>
							</div>

                        </div>
                        <?php  }?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="first_name" class="control-label required">First Name</label>

                                <input class="form-control" name="first_name" id="first_name" type="text">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="last_name" class="control-label">Last Name</label>

                                <input class="form-control" name="last_name" id="last_name" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label required">Email Address</label>
                            <input class="form-control" name="email" id="email" type="text">
                        </div>

                        <div class="form-group">
                            <div class="checkbox custom-checkbox">
                                <input name="email_ticket" id="email_ticket" value="1" type="checkbox">
                                <label for="email_ticket">&nbsp;&nbsp;Send invitation &amp; ticket to attendee.</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               <button class="btn modal-close btn-danger" data-dismiss="modal" type="button">Cancel</button>
               <input class="btn btn-success" value="Invite Attendee" type="submit">
            </div>
        </div><!-- /end modal content-->

    </div>
    </form>
</div>