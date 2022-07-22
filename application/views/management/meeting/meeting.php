<div class="wrapper" ng-controller="meetingCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Meeting
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Meeting Management</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary" style="padding-left:10px; padding-right:10px;">
              <div class="box-header with-border">

                <div class="box-title">
                  <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" ng-model="search" class="form-control" placeholder="Search">

                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>

                <div class="box-title pull-right" style="margin-right: 5px;">
                  <a ng-click="refreshTable()"><button type="button" class="btn btn-block btn-primary" title="Refresh Table"><i class="fa fa-refresh"> </i></button></a>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                  <!--
                    <th ng-click="sort('mtnId')">Id
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>-->
                    <th></th>
                    <th ng-click="sort('mtnName')">Meeting Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnCustomerName')">Customer Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnCustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnUserName')">Assign To
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnDate')">Date
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnTime')">Time
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnVisited')">Visited
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnVisited'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnSignature')">Signature
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnSignature'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnPicture')">Picture
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnPicture'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnRemarks')">Remarks
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnRemarks'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnCompleted')">Completed
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnCompleted'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnNextVisit')">Next Visit
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnNextVisit'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnParentName')">Created By
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th style="width: 90px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr dir-paginate-start="meeting in meetings|orderBy:sortKey:reverse|filter:search|itemsPerPage:5" ng-click="expanded = !expanded">
                    <!--<td>{{meeting.mtnId}}</td>-->
                    <td>
                      <span ng-bind="expanded ? '&#8650' : '&#8649'"></span>
                    </td>
                    <td>{{meeting.mtnName}}</td>
                    <td>{{meeting.mtnCustomerName}}</td>
                    <td>{{meeting.mtnUserName}}</td>
                    <td>{{meeting.mtnDate}}</td>
                    <td>{{meeting.mtnTime}}</td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnVisited)'>{{meeting.mtnVisited}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnSignature)'>{{meeting.mtnSignature}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnPicture)'>{{meeting.mtnPicture}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnRemarks)'>{{meeting.mtnRemarks}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnCompleted)'>{{meeting.mtnCompleted}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnNextVisit)'>{{meeting.mtnNextVisit}}</span></td>
                    <td>{{meeting.mtnParentName}}</td>
                    <td>
                      <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-meeting-updates" ng-click="viewMeetingUpdates(meeting)" title="View Meeting Updates"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(meeting)" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>
                  </tr>
                  <tr dir-paginate-end ng-show="expanded">
                      <td></td>
                      <td colspan="2">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Visited Details</strong></span></div>
                        <div><span><strong>Visited: </strong>{{meeting.mtnVisited}}</span></div>
                        <div><span><strong>Visited Date: </strong>{{meeting.mtnVisitedDate}}</span></div>
                        <div><span><strong>Visited Time: </strong>{{meeting.mtnVisitedTime}}</span></div>
                        <!--<div><span><strong>Visited Message: </strong>{{meeting.mtnVisitedMessage}}</span></div>-->
                      </td>
                      <td colspan="3">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Signature Details</strong></span></div>
                        <div><span><strong>Signature Updated: </strong>{{meeting.mtnSignature}}</span></div>
                        <div><span><strong>Updated Date: </strong>{{meeting.mtnSignatureDate}}</span></div>
                        <div><span><strong>Updated Time: </strong>{{meeting.mtnSignatureTime}}</span></div>
                        <div><span><strong>Signature Image: </strong></span></div>
                        <div><img src="{{meeting.mtnSignatureImage}}" width="180" height="130"></div>
                      </td>
                      <td colspan="3">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Picture Details</strong></span></div>
                        <div><span><strong>Picture Updated: </strong>{{meeting.mtnPicture}}</span></div>
                        <div><span><strong>Updated Date: </strong>{{meeting.mtnPictureDate}}</span></div>
                        <div><span><strong>Updated Time: </strong>{{meeting.mtnPictureTime}}</span></div>
                        <div><span><strong>Picture Image: </strong></span></div>
                        <div><img src="{{meeting.mtnPictureImage}}" width="180" height="130" style="border: 1px solid black;"></div>
                      </td>
                      <td colspan="4">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Remarks Details</strong></span></div>
                        <div><span><strong>Remarks Updated: </strong>{{meeting.mtnRemarks}}</span></div>
                        <div><span><strong>Updated Date: </strong>{{meeting.mtnRemarksDate}}</span></div>
                        <div><span><strong>Updated Time: </strong>{{meeting.mtnRemarksTime}}</span></div>
                        <div><span><strong>Remarks: </strong>{{meeting.mtnRemarksMessage}}</span></div>
                      </td>
                  </tr>
                </tbody>
                </table>
                </div>

                <div ng-if="dataloading" class="row" align="center">
                  <div class="row">
                    <img src="<?php echo base_url();?>assets/dist/img/boxc.gif"> 
                  </div>
                </div>

                <dir-pagination-controls
                  max-size="5"
                  direction-links="true"
                  boundary-links="true" >
                </dir-pagination-controls>
            </div>
            <!-- /.box -->
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('include/footer'); ?>


<div class="modal modal-meeting-updates">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Meeting Updates</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px; padding-bottom: 10px;">
              <legend style="width: 150px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Meeting Details </legend>
                <div class="row">
                  <div class="col-md-4"><strong>Meeting Name : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Customer Name : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnCustomerName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Meeting Date : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnDate}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Meeting Time : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnTime}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Assign To : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnUserName}}</div>
                </div>
             </fieldset>
          </div>

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px; padding-bottom: 10px;">
              <legend style="width: 150px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Visited Details </legend>
                <div class="row">
                  <div class="col-md-4"><strong>Visited : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnVisited}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Visited Date : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnVisitedDate}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Visited Time : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnVisitedTime}}</div>
                </div>
                <!--
                <div class="row">
                  <div class="col-md-4"><strong>Visited Message : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnVisitedMessage}}</div>
                </div>-->
             </fieldset>
          </div>

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px;">
              <legend style="width: 160px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Signature Details </legend>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4"><strong>Signature Updated : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnSignature}}</div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"><strong>Update Date : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnSignatureDate}}</div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"><strong>Update Time : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnSignatureTime}}</div>
                      </div>
                    </div>
                    <div class="col-md-12" align="center" style="margin-bottom: 10px; margin-top: 10px;">
                      <img src="{{activeMeeting.mtnSignatureImage}}" width="300">
                    </div>
                  </div>
             </fieldset>
          </div>

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px;">
              <legend style="width: 160px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Picture Details </legend>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4"><strong>Picture Updated : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnPicture}}</div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"><strong>Update Date : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnPictureDate}}</div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"><strong>Update Time : </strong></div>
                        <div class="col-md-8">{{activeMeeting.mtnPictureTime}}</div>
                      </div>
                    </div>
                    <div class="col-md-12" align="center" style="margin-bottom: 10px; margin-top: 10px;">
                      <img src="{{activeMeeting.mtnPictureImage}}" width="300" height="300" style="border: 1px solid black;">
                    </div>
                  </div>
             </fieldset>
          </div>

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px; padding-bottom: 10px;">
              <legend style="width: 150px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Remarks Details </legend>
                <div class="row">
                  <div class="col-md-4"><strong>Remarks Updated : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnRemarks}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Remarks : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnRemarksMessage}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Update Date : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnRemarksDate}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Update Time : </strong></div>
                  <div class="col-md-8">{{activeMeeting.mtnRemarksTime}}</div>
                </div>
             </fieldset>
          </div>

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px; padding-bottom: 10px;">
              <legend style="width: 150px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Other Details </legend>
                <div class="row">
                  <div class="col-md-5"><strong>Meeting Completed : </strong></div>
                  <div class="col-md-7">{{activeMeeting.mtnCompleted}}</div>
                </div>
                <div class="row">
                  <div class="col-md-5"><strong>Next Meeting Required : </strong></div>
                  <div class="col-md-7">{{activeMeeting.mtnNextVisit}}</div>
                </div>
             </fieldset>
          </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




</div>
<!-- ./wrapper -->