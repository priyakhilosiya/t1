<div class="container-fluid">
        <div class="page-title">
            <h1 class="title"><i class="ico-users"></i>
Sponsor
</h1>
        </div>
                <!--  header -->
        <div class="page-header page-header-block row">
            <div class="row">

<div class="col-md-9">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-responsive">
            <button data-toggle="modal"   href="javascript:void(0);"  data-href="<?=admin_path()?>sponsor/addSponsor"  class="loadModal btn btn-success" type="button"><i class="ico-user-plus"></i> Add  Sponsor</button>
        </div>
	</div>
</div>
<!--<div class="col-md-3">
   <form method="GET" action="" accept-charset="UTF-8">
    <div class="input-group">
        <input name="q" value="" placeholder="Search sponsors.." class="form-control" type="text">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
    </div>
   </form>
</div>-->
            </div>
        </div>
        <!--/  header -->

        <!--Content-->
<!--Start Sponsor table-->
<div class="row">

	 <?php 
	 
	// if($allSponsorDetails!="0" && count($allSponsorDetails)>0){  ?>	
    <div class="col-md-12">
                <div class="panel">
            <div class="table-responsive">

				<table id="sponsorTable" class="table table-bordered table-striped dt-responsive nowrap" width="100%" cellspacing="0">
					<thead>
						<tr>
							<!--<th>Category ID</th>-->
							<th>Name</th>
							<th>Email</th>
							<th>No of Ticket</th>
							<th>Action</th>
						</tr>
					</thead>

				</table>

                <!--<table class="table" >
                    <thead>
                        <tr>
                            <th>
                               <a href="?sort_by=first_name&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Name<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th>
                               <a href="?sort_by=email&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">Email<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th>
                               <a href="?sort_by=ticket_id&amp;sort_order=asc&amp;q=&amp;page=1" class="col-sort ">No of Ticket<i class="ico-arrow-up22"></i></a>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                   

                    <?php foreach ($allSponsorDetails as $key=>$val){?>
                    <tr class="sponsor_5 ">
                            <td> <?php echo $val['U_FNAME']." ".$val['U_LNAME'];?></td>
                            <td>
                                <a data-modal-id="Messagesponsor" class="loadModal" href="javascript:void(0);"  data-href="<?=admin_path()?>sponsor/messageSponsor"> <?php echo $val['U_EMAIL'];?></a>
                            </td>
                            <td><?php echo $val['ORG_TICKETS']?></td>
                            <td class="text-center">
                                <a href="javascript:void(0);"  data-href="<?=admin_path()?>sponsor/editSponsor/<?=$val['U_ID'];?>" class="loadModal btn btn-xs btn-primary"> Edit</a>

                                <a href="javascript:void(0);"  data-href="<?=admin_path()?>sponsor/cancelSponsor/<?=$val['U_ID'];?>" class="loadModal btn btn-xs btn-danger"> Delete</a>
                            </td>
                      </tr>
                      <?php }?>
                   </tbody>

                </table>-->
            </div>
        </div>
            </div>

			<?php //}else{?>
			<!--<div class="col-md-12">
				
						<style>
							.page-header {
								/*opacity: .1;*/
							}
						</style>
						<div class="col-lg-6 col-lg-offset-3">
							<div class="panel panel-minimal" style="margin-top:10%;">
								<div class="panel-body text-center">
									<i class="    ico-search
						  fsize112"></i>
								</div>
								<div class="panel-body text-center">
									<h1 style="font-weight: 100;" class=" text-center fsize32 mb10 mt0">
											No Search Results
									</h1>
									<h5 style="font-size: 20px; font-weight: 100; line-height: 1.5em;" class=" pa10 text-primary text-center ">
											There was nothing found matching the term 'aaa'
									</h5>
										
								</div>
							</div>
						</div>        
						</div>-->
					 <?php //}?>
    <div class="col-md-12">

    </div>
</div>    <!--/End sponsors table-->

        <!--/Content-->
    </div>