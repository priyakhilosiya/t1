<div class="container-fluid">
        <div class="page-title">
            <h1 class="title"><i class="ico-users"></i>
Attendees
</h1>
        </div>
                <!--  header -->
				<?php 
	$hide=0;
	if($userRole=='S'){
		$totaltickets=$userSponsorDetails['ORG_TICKETS'];
		if($attendeecnt>=$totaltickets){
			$hide=1;
		}else{
			$hide=0;
		}
	}else{
		$hide=0;
	}

	?>
        <div class="page-header page-header-block row">
            <div class="row">

<div class="col-md-9">
    <div class="btn-toolbar" role="toolbar">
		<?php if($hide==0){?>
        <div class="btn-group btn-group-responsive">
            <button data-toggle="modal"   href="javascript:void(0);"  data-href="<?=admin_path()?>users/addattendee"  class="loadModal btn btn-success" type="button"><i class="ico-user-plus"></i> Invite Attendee</button>
        </div>
		<?php }?>

        <!--<div class="btn-group btn-group-responsive">
            <button data-toggle="modal"   href="javascript:void(0);"  data-href="<?=admin_path()?>users/inviteAttendees" class="loadModal btn btn-success" type="button"><i class="ico-file"></i> Invite Attendees</button>
        </div>-->

        <!--<div class="btn-group btn-group-responsive">
            <a class="btn btn-success" href="<?=admin_path()?>users/export/print" target="_blank"><i class="ico-print"></i> Print Attendee List</a>
        </div>-->
        <div class="btn-group btn-group-responsive">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="ico-users"></i> Export <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <!--<li><a href="<?=admin_path()?>users/export/xlsx">Excel (XLSX)</a></li>-->
                <li><a href="<?=admin_path()?>users/export/xls">Excel (XLS)</a></li>
                <li><a href="<?=admin_path()?>users/export/csv">CSV</a></li>
                <!--<li><a href="<?=admin_path()?>users/export/html">HTML</a></li>-->
            </ul>
        </div>
        <div class="btn-group btn-group-responsive">
            <button data-toggle="modal"   href="javascript:void(0);"  data-href="<?=admin_path()?>users/messageAll" class="loadModal btn btn-success" type="button"><i class="ico-envelope"></i> Message</button>
        </div>
    </div>
</div>
<div class="col-md-3">
   <form method="GET" action="" accept-charset="UTF-8">
    <div class="input-group">
        <input name="q" value="" placeholder="Search Attendees.." class="form-control" type="text">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
    </div>
   </form>
</div>
            </div>
        </div>
        <!--/  header -->

        <!--Content-->

<!--Start Attendees table-->
<div class="row">
    <div class="col-md-12">
                <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               <a href="?sort_by=first_name&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Name<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th>
                               <a href="?sort_by=email&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Email<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th>
                               <a href="?sort_by=ticket_id&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Ticket<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th>
                               <a href="?sort_by=order_reference&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Order Ref.<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($allUserDetails!="0" && count($allUserDetails)>0){  ?>

                        <?php foreach ($allUserDetails as $key=>$val){?>
                    <tr class="attendee_5 ">
                            <td> <?php echo $val['ATD_FNAME']." ".$val['ATD_LNAME'];?></td>
                            <td>
                                <a data-modal-id="MessageAttendee" class="loadModal" href="javascript:void(0);"  data-href="<?=admin_path()?>users/messageAttendee/<?=$val['ATD_ID'];?>"> <?php echo $val['ATD_EMAIL'];?></a>
                            </td>
                            <td>
                                <?php echo $ticketDetails[$val['ORD_T_NAME']];?>
                            </td>
                            <td>
                                <a href="javascript:void(0);"  data-href="<?=admin_path()?>users/orderView/<?=$val['ORD_ID'];?>" title="View Order '<?=$val['ORD_REFERENCE'];?>'" class="loadModal">
                                     <?php echo $val['ORD_REFERENCE'];?>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
										<li><a href="javascript:void(0);"  data-modal-id="MessageAttendee" data-href="<?=admin_path()?>users/messageAttendee/<?=$val['ATD_ID'];?>" class="loadModal"> Message</a></li>
										<li><a href="javascript:void(0);"  data-modal-id="MessageAttendee" data-href="<?=admin_path()?>users/viewAccompany/<?=$val['ORD_ID'];?>" class="loadModal"> View Accompany details</a></li>
										<li><a href="javascript:void(0);"  data-href="<?=admin_path()?>users/resendTicket/<?=$val['ORD_ID'];?>/<?=$val['U_ID'];?>" class="loadModal"> Resend Ticket</a></li>
                                        <li><a href="<?=admin_path()?>users/downloadPdf/<?=$val['ORD_ID'];?>/<?=$val['U_ID'];?>">Download PDF Ticket</a></li>
                                    </ul>
                                </div>

                                <a href="javascript:void(0);"  data-href="<?=admin_path()?>users/editAttendee/<?=$val['ORD_ID'];?>/<?=$val['U_ID'];?>" class="loadModal btn btn-xs btn-primary"> Edit</a>

                                <a href="javascript:void(0);"  data-href="<?=admin_path()?>users/cancelAttendee/<?=$val['ATD_ID'];?>/<?=$val['ATD_ID'];?>" class="loadModal btn btn-xs btn-danger"> Cancel</a>
                            </td>
                      </tr>
                      <?php }?>
                      <?php }else{?>

                      <tr><td><div class="alert alert-info" role="alert">No records Found</div></td></tr>

                      <?php }?>


                   </tbody>

                </table>
            </div>
        </div>
            </div>
    <div class="col-md-12">

    </div>
</div>    <!--/End attendees table-->

        <!--/Content-->
    </div>