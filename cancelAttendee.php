<div role="dialog"  class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
>

   <form method="POST" action="<?=admin_path()?>users/postCancel/<?=$AttendeeDetails['ATD_ID']?>/<?=$AttendeeDetails['ATD_ORD_ID']?>" accept-charset="UTF-8" class="ajax closeModalAfter">
   <input type="hidden" name="attendee_id" id="attendee_id" value="<?php echo $AttendeeDetails['ATD_ID'];?>"/>
   <input type="hidden" name="attendee_email" id="attendee_email" value="<?php echo $AttendeeDetails['ATD_EMAIL'];?>"/>
   <input type="hidden" name="order_id" id="order_id" value="<?php echo $AttendeeDetails['ATD_ORD_ID'];?>"/>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cancel"></i>
                    Cancel <b><?php echo $AttendeeDetails['ATD_FNAME']." ".$AttendeeDetails['ATD_LNAME'];?><b></b></b></h3><b><b>
            </b></b></div><b><b>
            <div class="modal-body">
                <p>
                    Cancelling Attendees will remove them from the attendee list.
                </p>

                <br>
                <div class="form-group">
                    <div class="checkbox custom-checkbox">
                        <input name="notify_attendee" id="notify_attendee" value="1" type="checkbox">
                        <label for="notify_attendee">&nbsp;&nbsp;Notify <b><?php echo $AttendeeDetails['ATD_FNAME']." ".$AttendeeDetails['ATD_LNAME'];?></b> their ticket has been cancelled.</label>
                    </div>
                </div>
                        <div class="form-group">
                            <div class="checkbox custom-checkbox">
                                <input name="refund_attendee" id="refund_attendee" value="1" type="checkbox">
                                <label for="refund_attendee">&nbsp;&nbsp;Refund <b><?php echo $AttendeeDetails['ATD_FNAME']." ".$AttendeeDetails['ATD_LNAME'];?></b> for their ticket.</label>
                            </div>
						</div>
                            </div> <!-- /end modal body-->
            <div class="modal-footer">
               <button class="btn modal-close btn-danger" data-dismiss="modal" type="button">Cancel</button>
               <input class="btn btn-success" value="Confirm Cancel Attendee" type="submit">
            </div>
        </b></b></div><!-- /end modal content--><b><b>

    </b></b></div></form><b><b>
</b></b></div>