<?php
	include 'headers/header_forum.php';
?>
    <div class="container">
        <div class="row">
            <div class="jumbotron">
                <h1>Bootstrap 3.1.0 - Modal Demo</h1>
                <h4>Powered By <a href="http://www.sitepoint.com">SitePoint.com</a></h4>
                <a href="" class="btn btn-sm btn-primary">Back to Tutorial</a>
            </div>
        </div>
        <div class="row text-center">
            <h3>The Basic Modal</h3>
            <a href="#" class="btn btn-lg btn-success" data-toggle="modal" data-target="#basicModal">Click to open Modal</a>
        </div>
        <hr>
        <div class="row text-center">
            <h3>The Large Modal</h3>
            <a href="#" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#largeModal">Click to open Modal</a>
        </div>
        <hr>
        <div class="row text-center">
            <h3>The Small Modal</h3>
            <a href="#" data-toggle="modal" data-target="#smallModal"><h3>The Small Modal</h3></a>
        </div>
        <hr>
        <div class="row text-center">
            <h3>Load Remote Content in Modal</h3>
            <a  class="btn btn-lg btn-default" data-toggle="modal" data-target="#remoteModal" href="http://www.sitepoint.com">Click to open Modal</a>
            <p>Demo to load SitePoint.com in the modal</p>
        </div>
        <hr>
        <br>
        <div class="row text-center">
            <p>&copy; 2014 | <a href="http://www.sitepoint.com">SitePoint Pty Ltd</a></p>
        </div>

    </div>

    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Basic Modal</h4>
          </div>
          <div class="modal-body">
            <h3>Modal Body</h3>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Large Modal</h4>
          </div>
          <div class="modal-body">
            <h3>Modal Body</h3>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Small Modal</h4>
          </div>
          <div class="modal-body">
            <h3>Modal Body</h3>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Remote Modal</h4>
          </div>
          <div class="modal-body">
            <h3>Loading Content....</h3>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

<?php
	include 'footer.php';
?>