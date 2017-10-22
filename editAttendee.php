<div role="dialog"  class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="EditAttendeeModal">
   <form method="POST" action="<?= admin_path()?>users/postAddattendee" accept-charset="UTF-8" class="ajax">
       <input type="hidden" name="order_id" id="order_id" value="<?=$order_id;?>" />
       <input type="hidden" name="user_id" id="user_id" value="<?=$user_id;?>" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-edit"></i>
                    Edit <b><?= $userAttendeeDetails['ATD_FNAME'];?> <?=$userAttendeeDetails['ATD_LNAME'];?> <b></b></b></h3><b><b>
            </b></b></div><b><b>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (count($ticketDetails>0)){?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="ticket_id" class="control-label required">Ticket</label>
                                   <select class="form-control" id="ticket_id" name="ticket_id">
                                    <?php foreach($ticketDetails as $key=>$val){
                                       // echo $userAttendeeDetails['ORD_T_NAME'];
                                         $selected='';
                                    if($userAttendeeDetails['ORD_T_NAME']==$key)  {
                                     $selected='selected="selected"';

                                    } else{
                                        $selected='';
                                    }
                                      ?>
                                        <option value="<?=$key?>" <?php echo $selected;?>><?php echo $val;?></option>
                					<?php }?>
                                  </select>
                                </div>
                            </div>
							<div class="col-md-6">
							    <?php if($userAttendeeDetails['ORD_CAT_TYPE']=='D')  {
                                     $selectedD='checked="checked"';
                                        $selectedPG='';
                                    } else{
                                       $selectedPG='checked="checked"';
                                       $selectedD='';
                                    }  ?>
									<div class="form-group">
								 <label for="cat_type" class="control-label required">User type</label>
								 <div class="form-control">
								<input type="radio" <?php echo $selectedD;?> name="cat_type" id="cat_type" value="D"/>Delegate
								<input type="radio"  <?php echo $selectedPG;?> name="cat_type" id="cat_type" value="PG"/>PG
								</div>
								</div>
							</div>
                        </div>
                         <?php  } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="control-label required">FIRST&nbsp;NAME</label>
                                    <input class="form-control" name="first_name" value="<?= $userAttendeeDetails['ATD_FNAME'];?>" id="first_name" type="text">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="control-label">LAST&nbsp;NAME</label>
                                    <input class="form-control" name="last_name" value="<?= $userAttendeeDetails['ATD_LNAME'];?>" id="last_name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email" class="control-label required">Email</label>

                                    <input class="form-control" name="email" value="<?= $userAttendeeDetails['ATD_EMAIL'];?>" id="email" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               <input name="attendee_id" value="3" type="hidden">
               <button class="btn modal-close btn-danger" data-dismiss="modal" type="button">Cancel</button>
               <input class="btn btn-success" value="Edit Attendee" type="submit">
            </div>
        </b></b></div><!-- /end modal content--><b><b>

    </b></b></div></form><b><b>
</b></b></div>